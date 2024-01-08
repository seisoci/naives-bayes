<?php

namespace App\Helpers;

use App\Models\Normalization;
use App\Models\Participant;
use App\Models\ParticipantResult;
use App\Models\User;
use App\Models\VPoints;
use App\Traits\PsikometriConverter;
use App\Traits\ResultHelper;

class ResultQuestion
{
    use PsikometriConverter, ResultHelper;

    private $participant_id;

    public function __construct($participant_id)
    {
        $this->participant_id = $participant_id;
    }

    public function reports($type = null)
    {
        $participant = $this->get_participant();
        $participantCategory = $participant['examination']['category']['name'];
        if ($participantCategory == 'IST') {
            return $this->ist($type);
        } elseif ($participantCategory == 'PAPI KOSTICK') {
            return $this->papi($type);
        } elseif ($participantCategory == 'RMIB') {
            return $this->rmib($type);
        }
    }

    public function get_participant()
    {
        $participant = Participant::with('examination.category')->findOrFail($this->participant_id);
        if (!$participant) {
            return '';
        }

        return $participant;
    }

    public function get_user()
    {
        $participant = $this->get_participant();
        $user = User::findOrFail($participant->user_id);
        if (!$user) {
            return '';
        }

        return $user;
    }

    public function answers($type, $subCategoryName = null)
    {
        return VPoints::select('number', 'answers')
            ->where('category_name', $type)
            ->when($subCategoryName, function ($q, $subCategoryName) {
                return $q->where('sub_category_name', $subCategoryName);
            })
            ->where('participant_id', $this->participant_id)
            ->orderBy('number')
            ->get()
            ->toArray();
    }

    public function ist($type = null)
    {
        $participant = $this->get_participant();
        $data = VPoints::selectRaw('
          `category_name`,
          `sub_category_name`,
          `age`,
           SUM(`score`) AS `score_rw`,
           CASE
            WHEN `sub_category_name` = "GE" THEN (
                SELECT `value`
                FROM `normalizations`
                WHERE `type` = "IST-GE-CONVERSION"
                AND SUM(`score`) BETWEEN `min` AND `max`
            )
            ELSE NULL
           END AS `score_ge`
        ')
            ->where('participant_id', $participant['id'])
            ->groupBy('participant_section_id')
            ->get()
            ->toArray();

        $result['user'] = $this->get_user();
        $result['user']['age'] = $participant->age;
        $result['total_rw'] = 0;

        foreach ($data as $item) :
            $subCategory = $item["sub_category_name"] ?? "";
            unset($item["sub_category_name"]);
            $rw = Normalization::selectRaw('
          `value_converter` AS `score_sw`
        ')
                ->where('type', "IST-{$subCategory}")
                ->where('min', '<=', $participant['age'])
                ->where('max', '>=', $participant['age'])
                ->where('value', $item['score_ge'] ?? $item['score_rw'])
                ->first();

            $kriteria['kriteria'] = $this->psikometriIST($rw['score_sw'], 'value');

            $answers['answers'] = $this->answers('IST', $subCategory);

            $result['total_rw'] += $item['score_rw'];
            $result['table'][$subCategory] = array_merge($item, $rw->toArray(), $answers, $kriteria);
        endforeach;

        $sort = ['SE', 'WA', 'AN', 'GE', 'ME', 'RA', 'ZR', 'FA', 'WU'];
        uksort($result, function ($key1, $key2) use ($sort) {
            return array_search($key1, $sort) - array_search($key2, $sort);
        });
        $result['total_sw'] = Normalization::select('value_converter')
            ->where('type', "IST-GESMAT")
            ->where('min', '<=', $participant['age'])
            ->where('max', '>=', $participant['age'])
            ->whereRaw("CAST(SUBSTRING_INDEX(TRIM(value), '-', 1) AS SIGNED) <= ?", [$result['total_rw']])
            ->whereRaw("CAST(SUBSTRING_INDEX(TRIM(value), '-', -1) AS SIGNED) >= ?", [$result['total_rw']])
            ->pluck('value_converter')
            ->first();

        $iq = Normalization::select('iq', 'value_converter')
            ->where('type', "IST-IQ")
            ->where('value', '<=', $result['total_sw'] ?? 0)
            ->where('value', '>=', $result['total_sw'] ?? 0)
            ->first();

        $result['total_iq'] = $iq['iq'] ?? 0;
        $result['total_iq_%'] = $iq['value_converter'] ?? 0;
        foreach ($result['table'] as $key => $item):
            $result['chart_labels'][] = $key;
            $result['chart_data'][] = $item['score_sw'];
        endforeach;
        unset($data);


        $psikometriIQConverter = Normalization::where('type', 'IST-IQ-PSIKOMETRI')
            ->where('min', '<=', $result['total_iq'])
            ->where('max', '>=', $result['total_iq'])
            ->first();

        $result['psikogram'] = [
            'iq_psikometri' => $psikometriIQConverter['value_converter'],
            'aritmatika' => $this->psikometriIST(($result['table']['RA']['score_sw'] + $result['table']['ZR']['score_sw']) / 2),
            'verbal' => $this->psikometriIST(($result['table']['SE']['score_sw'] + $result['table']['WA']['score_sw'] + $result['table']['AN']['score_sw'] + $result['table']['GE']['score_sw']) / 4),
            'analisa' => $this->psikometriIST(($result['table']['FA']['score_sw'] + $result['table']['WU']['score_sw']) / 2),
            'pengambilan_keputusan' => $this->psikometriIST(($result['table']['SE']['score_sw'] + $result['table']['AN']['score_sw'] + $result['table']['WU']['score_sw'] + $result['table']['ZR']['score_sw'] + $result['table']['RA']['score_sw']) / 5),
            'berbahasa' => $this->psikometriIST(($result['table']['WA']['score_sw'] + $result['table']['GE']['score_sw']) / 2),
            'kreatifitas' => $this->psikometriIST(($result['table']['AN']['score_sw'] + $result['table']['WU']['score_sw']) / 2),
            'komprehensif' => $this->psikometriIST(($result['table']['FA']['score_sw'] + $result['table']['GE']['score_sw']) / 2),
            'keunggulan' => ($result['table']['GE']['score_sw'] + $result['table']['RA']['score_sw']) > ($result['table']['AN']['score_sw'] + $result['table']['ZR']['score_sw']) ? "KAKU/ EKSAK" : "FLEKSIBEL",
            'pola_berifikir' => 'Verbal-Teoritis / Praktis – Kongkrit',
        ];

        return $result;
    }

    public function papi($type = null)
    {
        $participant = $this->get_participant();
        $result['user'] = $this->get_user();
        $result['user']['age'] = $participant->age;
        $result['graph'] = $this->papiFormula($participant);
        $result['answers'] = $this->answers('PAPI KOSTICK');
        $result['results'] = $this->papiAreaPengembangan($result['graph']);
        $result['area_pengembangan'] = $this->papiAreaPengembanganDescription($result['results']);
        $result['skor_ekstrim'] = $this->papiSkorEkstrim($result['graph']);
        $result['ciri_berdekatan'] = $this->papiCiriBerdekatan($result['graph']);

        return $result;
    }

    public function rmib($type = null)
    {
        $participant = $this->get_participant();
        $result['user'] = $this->get_user();
        $result['user']['exam_name'] = $participant->examination->name ?? '-';
        $result['user']['date_finish'] = $participant->date_finish ?? '-';
        $result['user']['age'] = $participant->age;
        $result['results'] = $this->rimbResult($participant);
        $result['interpretasi'] = $this->rmibInterpretasi($result['results']['total_point_answers'], $result['user']);
        return $result;
    }

    public function epps($type = null)
    {
        $participant = $this->get_participant();
        $user = $this->get_user();
        $matrix = $this->eppsMatrix();
        $answers = VPoints::selectRaw('
          `category_name`,
          `sub_category_name`,
          `number`,
          `answers`
        ')
            ->where('participant_id', $participant['id'])
            ->orderBy('number')
            ->get()
            ->toArray();

        $matrixNew = [];
        foreach ($matrix as $key => $item):
            foreach ($item as $questionNumber):
                $matrixNew[$key][$questionNumber]['answers'] = $answers[$questionNumber]['answers'] ?? NULL;
            endforeach;
        endforeach;

        dd($this->eppsFormula($participant));
    }

    public function view($type = null)
    {
        $participant = $this->get_participant();
        $result = ParticipantResult::where('participant_id', $participant->id)->first()['results'] ?? [];
        $result = json_decode($result);
        $participantCategory = $participant['examination']['category']['name'];

        if ($participantCategory == 'IST') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                'psikogram' => [
                    'view' => view('panel.examinations.results.type.IST.view_psikogram', compact('result'))->render()
                ],
                default => [
                    'view' => view('panel.examinations.results.type.IST.view', compact('result'))->render(),
                    'js' => view('panel.examinations.results.type.IST.js', compact('result'))->render()
                ]
            };
        }

        if ($participantCategory == 'PAPI KOSTICK') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                'psikogram' => [
                    'view' => view('panel.examinations.results.type.PAPI.view_psikogram', compact('result'))->render()
                ],
                default => [
                    'view' => view('panel.examinations.results.type.PAPI.view', compact('result'))->render(),
                    'js' => view('panel.examinations.results.type.PAPI.js', compact('result'))->render()
                ]
            };
        }

        if ($participantCategory == 'RMIB') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                default => [
                    'view' => view('panel.examinations.results.type.RMIB.view', compact('result'))->render(),
                    'js' => view('panel.examinations.results.type.RMIB.js', compact('result'))->render()
                ]
            };
        }

    }

    public function viewWeb($type = null)
    {
        $participant = $this->get_participant();
        $result = ParticipantResult::where('participant_id', $participant->id)->first()['results'] ?? [];
        $result = json_decode($result);
        $participantCategory = $participant['examination']['category']['name'];

        if ($participantCategory == 'IST') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                'psikogram' => [
                    'view' => view('web.results.type.IST.view_psikogram', compact('result'))->render()
                ],
                default => [
                    'view' => view('web.results.type.IST.view', compact('result'))->render(),
                    'js' => view('web.results.type.IST.js', compact('result'))->render()
                ]
            };
        }

        if ($participantCategory == 'PAPI KOSTICK') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                default => [
                    'view' => view('web.results.type.PAPI.view', compact('result'))->render(),
                    'js' => view('web.results.type.PAPI.js', compact('result'))->render()
                ]
            };
        }

        if ($participantCategory == 'RMIB') {
            return match ($type) {
                'data' => $result,
                'json' => json_encode($result),
                default => [
                    'view' => view('web.results.type.RMIB.view', compact('result'))->render(),
                    'js' => view('web.results.type.RMIB.js', compact('result'))->render()
                ]
            };
        }

    }
}
