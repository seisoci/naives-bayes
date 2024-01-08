<?php


namespace App\Traits;

use App\Models\Normalization;
use App\Models\VPoints;

trait ResultHelper
{

  public function eppsMatrix()
  {
    $numRows = 5;
    $numColumns = 15;
    $startValue = [1, 75, 150];

    foreach ($startValue as $item):
      for ($i = 0; $i < $numRows; $i++) {
        $row = array();
        for ($j = 0; $j < $numColumns; $j++) {
          $row[] = $item + $i + $j * $numRows + 1;
        }
        $matrix[] = $row;
      }
    endforeach;

    return $matrix;
  }

  public function eppsFormula($participant)
  {
    $formula = [
      [
        "title" => "ACH",
        "R" => [6, 11, 16, 21, 26, 31, 36, 41, 46, 51, 56, 61, 66, 71],
        "C" => [2, 3, 4, 5, 76, 77, 78, 79, 80, 151, 152, 153, 154, 155],
      ],
      [
        "title" => "DEF",
        "R" => [2, 12, 17, 22, 27, 32, 37, 42, 47, 52, 57, 62, 67, 72],
        "C" => [6, 8, 9, 10, 81, 82, 83, 84, 85, 156, 157, 158, 159, 160,],
      ],
      [
        "title" => "ORD",
        "R" => [3, 8, 18, 23, 28, 33, 38, 43, 48, 53, 58, 63, 68, 73],
        "C" => [11, 12, 14, 15, 86, 87, 88, 89, 90, 161, 162, 163, 164, 165],
      ],
      [
        "title" => "EXH",
        "R" => [4, 9, 14, 24, 29, 34, 39, 44, 49, 54, 59, 64, 69, 74],
        "C" => [16, 17, 18, 20, 91, 92, 93, 94, 95, 166, 167, 168, 169, 170],
      ],
      [
        "title" => "AUT",
        "R" => [5, 10, 15, 20, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75],
        "C" => [21, 22, 23, 24, 96, 97, 98, 99, 100, 171, 172, 173, 174, 175],
      ],
      [
        "title" => "AFF",
        "R" => [76, 81, 86, 91, 96, 106, 111, 116, 121, 126, 131, 136, 141, 146],
        "C" => [26, 27, 28, 29, 30, 102, 103, 104, 105, 176, 177, 178, 179, 180],
      ],
      [
        "title" => "INT",
        "R" => [77, 82, 87, 92, 97, 102, 112, 117, 122, 127, 132, 137, 142, 147],
        "C" => [31, 32, 33, 34, 35, 106, 108, 109, 110, 181, 182, 183, 184, 185],
      ],
      [
        "title" => "SUC",
        "R" => [78, 83, 88, 93, 96, 103, 108, 118, 123, 128, 133, 138, 143, 148],
        "C" => [36, 37, 38, 39, 40, 111, 112, 114, 115, 186, 187, 188, 189, 190],
      ],
      [
        "title" => "DOM",
        "R" => [79, 84, 89, 94, 99, 104, 109, 114, 124, 129, 134, 139, 144, 149],
        "C" => [41, 42, 43, 44, 45, 116, 117, 118, 120, 191, 192, 193, 194, 195],
      ],
      [
        "title" => "ABA",
        "R" => [80, 85, 90, 95, 100, 105, 110, 115, 120, 130, 135, 140, 145, 150],
        "C" => [46, 47, 48, 49, 50, 121, 122, 123, 124, 196, 197, 198, 199, 200],
      ],
      [
        "title" => "NUR",
        "R" => [151, 156, 161, 166, 171, 176, 181, 186, 191, 196, 206, 211, 216, 221],
        "C" => [51, 52, 53, 54, 55, 126, 127, 128, 129, 130, 202, 203, 204, 205],
      ],
      [
        "title" => "CHG",
        "R" => [152, 157, 162, 167, 172, 177, 182, 187, 192, 197, 202, 212, 217, 222],
        "C" => [56, 57, 58, 59, 60, 131, 132, 133, 134, 135, 206, 208, 209, 210],
      ],
      [
        "title" => "END",
        "R" => [153, 158, 163, 168, 173, 178, 183, 188, 193, 198, 203, 208, 218, 223],
        "C" => [61, 62, 63, 64, 65, 136, 137, 138, 139, 140, 211, 212, 214, 215],
      ],
      [
        "title" => "HET",
        "R" => [154, 159, 164, 169, 174, 179, 184, 189, 194, 199, 204, 209, 214, 224],
        "C" => [66, 67, 68, 69, 70, 141, 142, 143, 144, 145, 216, 217, 218, 220],
      ],
      [
        "title" => "AGG",
        "R" => [155, 160, 165, 170, 175, 180, 185, 190, 195, 200, 205, 210, 215, 220],
        "C" => [71, 72, 73, 74, 75, 146, 147, 148, 149, 150, 221, 222, 223, 224],
      ],
    ];

    $result = [];
    foreach ($formula as $key => $item):
      $result[$key]['title'] = $item['title'];
      $result[$key]['R'] = $this->eppsCountPoint($participant, $item['R'])->point_r;
      $result[$key]['C'] = $this->eppsCountPoint($participant, $item['R'])->point_c;
    endforeach;

    return $result;
  }

  public function eppsCountPoint($participant, $arr = [])
  {
    return VPoints::selectRaw('
          COUNT(CASE WHEN `answers` = "A" THEN 1 END) AS point_r,
          COUNT(CASE WHEN `answers` = "B" THEN 1 END) AS point_c
        ')
      ->where('participant_id', $participant['id'])
      ->whereIn('number', $arr)
      ->orderBy('number')
      ->first();
  }

  public function papiFormula($participant)
  {
    $formula = [
      [
        "name" => "N",
        "number" => [2 => "B", 13 => "B", 24 => "B", 35 => "B", 46 => "B", 57 => "B", 68 => "B", 79 => "B", 90 => "B"],
        "desc" => "Kebutuhan Menye-",
        "desc2" => "lesaikan Tugas",
        "original_title" => "Kebutuhan Menyelesaikan Tugas"
      ],
      [
        "name" => "G",
        "number" => [1 => "A", 11 => "A", 21 => "A", 31 => "A", 41 => "A", 51 => "A", 61 => "A", 71 => "A", 81 => "A"],
        "desc" => "Peran",
        "desc2" => "Pekerja Keras",
        "original_title" => "Peran Pekerja Keras"
      ],
      [
        "name" => "A",
        "number" => [2 => "A", 3 => "B", 14 => "B", 25 => "B", 36 => "B", 47 => "B", 58 => "B", 69 => "B", 80 => "B"],
        "desc" => "Kebutuhan",
        "desc2" => "Berprestasi",
        "original_title" => "Kebutuhan Berprestasi"
      ],
      [
        "name" => "L",
        "number" => [12 => "A", 22 => "A", 32 => "A", 42 => "A", 52 => "A", 62 => "A", 72 => "A", 82 => "A", 81 => "B"],
        "desc" => "Peran",
        "desc2" => "Pemimpin",
        "original_title" => "Peran Pemimpin"
      ],
      [
        "name" => "P",
        "number" => [13 => "A", 3 => "A", 4 => "B", 15 => "B", 26 => "B", 37 => "B", 48 => "B", 59 => "B", 70 => "B"],
        "desc" => "Mengatur ",
        "desc2" => "Orang lain",
        "original_title" => "Peran Mengatur Orang Lain"
      ],
      [
        "name" => "I",
        "number" => [23 => "A", 33 => "A", 43 => "A", 53 => "A", 63 => "A", 73 => "A", 83 => "A", 82 => "B", 71 => "B"],
        "desc" => "Peran",
        "desc2" => "Membuat Keputusan",
        "original_title" => "Peran Membuat Keputusan"
      ],
      [
        "name" => "T",
        "number" => [34 => "A", 44 => "A", 54 => "A", 64 => "A", 74 => "A", 84 => "A", 83 => "B", 72 => "B", 61 => "B"],
        "desc" => "Peran",
        "desc2" => "Sibuk",
        "original_title" => "Peran Sibuk"
      ],
      [
        "name" => "V",
        "number" => [45 => "A", 55 => "A", 65 => "A", 75 => "A", 85 => "B", 84 => "B", 73 => "B", 62 => "B", 51 => "B"],
        "desc2" => "Peran",
        "desc" => "Penuh Semangat",
        "original_title" => "Peran Penuh Semangat"
      ],

      [
        "name" => "S",
        "number" => [56 => "A", 66 => "A", 76 => "A", 86 => "A", 85 => "B", 74 => "B", 63 => "B", 52 => "B", 41 => "B"],
        "desc" => "Hubungan Sosial",
        "desc2" => "Peran",
        "original_title" => "Peran Hubungan Sosial"
      ],
      [
        "name" => "B",
        "number" => [35 => "A", 25 => "A", 15 => "A", 5 => "A", 6 => "B", 17 => "B", 28 => "B", 39 => "B", 50 => "B"],
        "desc" => "Untuk Diterima",
        "desc2" => "Kebutuhan",
        "original_title" => "Kebutuhan Untuk Diterima"
      ],
      [
        "name" => "O",
        "number" => [46 => "A", 36 => "A", 26 => "A", 16 => "A", 6 => "A", 7 => "B", 18 => "B", 29 => "B", 40 => "B"],
        "desc2" => "Kebutuhan",
        "desc" => "Kedekatan",
        "original_title" => "Kebutuhan Kedekatan"
      ],
      [
        "name" => "X",
        "number" => [24 => "A", 14 => "A", 4 => "A", 5 => "B", 16 => "B", 27 => "B", 38 => "B", 49 => "B", 60 => "B"],
        "desc" => "Untuk Diperhatikan",
        "desc2" => "Kebutuhan",
        "original_title" => "Kebutuhan Untuk Diperhatikan"
      ],
      [
        "name" => "C",
        "number" => [89 => "A", 88 => "B", 77 => "B", 66 => "B", 55 => "B", 44 => "B", 33 => "B", 22 => "B", 11 => "B"],
        "desc2" => "Peran",
        "desc" => "Mengatur",
        "original_title" => "Peran Mengatur"
      ],
      [
        "name" => "D",
        "number" => [78 => "A", 88 => "A", 87 => "B", 76 => "B", 65 => "B", 54 => "B", 43 => "B", 32 => "B", 21 => "B"],
        "desc2" => "Peran",
        "desc" => "Bekerja Rinci",
        "original_title" => "Peran Bekerja Rinci"
      ],
      [
        "name" => "R",
        "number" => [67 => "A", 77 => "A", 87 => "A", 86 => "B", 75 => "B", 64 => "B", 53 => "B", 42 => "B", 31 => "B"],
        "desc2" => "Peran",
        "desc" => "Yang Teoritis",
        "original_title" => "Peran Yang Teoritis"
      ],
      [
        "name" => "Z",
        "number" => [57 => "A", 47 => "A", 37 => "A", 27 => "A", 17 => "A", 7 => "A", 8 => "B", 19 => "B", 30 => "B"],
        "reverse" => true,
        "desc2" => "Kebutuhan",
        "desc" => "Berubah",
        "original_title" => "Kebutuhan Berubah"
      ],
      [
        "name" => "E",
        "number" => [89 => "B", 78 => "B", 67 => "B", 55 => "B", 45 => "B", 34 => "B", 23 => "B", 12 => "B", 1 => "B"],
        "desc" => "Pengendalian",
        "desc2" => "Emosi",
        "original_title" => "Pengendalian Emosi"
      ],
      [
        "name" => "K",
        "number" => [68 => "A", 58 => "A", 48 => "A", 38 => "A", 28 => "A", 18 => "A", 8 => "A", 9 => "B", 20 => "B"],
        "reverse" => true,
        "desc" => "Agresif",
        "original_title" => "Agresif"
      ],
      [
        "name" => "F",
        "number" => [79 => "A", 69 => "A", 59 => "A", 49 => "A", 39 => "A", 29 => "A", 19 => "A", 9 => "A", 10],
        "desc" => "Kebutuhan",
        "desc2" => "Membantu Atasan",
        "original_title" => "Kebutuhan Membantu Atasan"
      ],
      [
        "name" => "W",
        "number" => [90 => "A", 80 => "A", 70 => "A", 60 => "A", 50 => "A", 40 => "A", 30 => "A", 20 => "A", 10 => "A"],
        "desc" => "Kebutuhan",
        "desc2" => "Mengikuti Atasan",
        "original_title" => "Kebutuhan Mengikuti Atasan"
      ],
    ];
    return $this->papiCountPoint($participant, $formula);
  }

  public function papiCountPoint($participant, $arr = [])
  {
    $result = $arr;
    foreach ($result as $key => $item):
      $query = VPoints::where('participant_id', $participant['id']);
      $query->where(function ($query) use ($item) {
        foreach ($item['number'] as $number => $answer):
          if ($number == array_key_first($item['number'])) {
            $query->where(function ($query) use ($number, $answer) {
              $query->where('number', $number)->where('answers', $answer);
            });
          } else {
            $query->orWhere(function ($query) use ($number, $answer) {
              $query->where('number', $number)->where('answers', $answer);
            });
          }
        endforeach;
      });
      $result[$key]['value'] = $query->count();
      $nomalization = Normalization::selectRaw('
          `value_converter`,
          `description`
        ')
        ->where('type', "PAPI")
        ->where('value', $item['name'])
        ->where('min', '<=', $query->count())
        ->where('max', '>=', $query->count())
        ->first();
      $result[$key]['group'] = $nomalization['description'] ?? '-';
      $result[$key]['norma'] = $nomalization['value_converter'] ?? '-';
      unset($result[$key]['number']);
    endforeach;

    return $result;

  }

  public function papiAreaPengembangan($result)
  {
    $resultArray = [];
    foreach ($result as $item) {
      $group = $item["group"];
      unset($item["group"]);
      $resultArray[$group][] = $item;
    }

    $newArray = [];
    foreach ($resultArray as $key => $item):
      $data['group'] = $key;
      $data['items'] = $item;

      $count = 0;
      foreach ($item as $itemColumn):
        $count += $itemColumn['value'];
      endforeach;

      $data['result_count'] = ($count / 3);
      if ($data['result_count'] >= 0 && $data['result_count'] <= 4) {
        $data['result'] = '<i class="fas fa-arrow-up"></i>';
      }

      if ($data['result_count'] >= 4 && $data['result_count'] <= 6) {
        $data['result'] = '<i class="fas fa-arrow-right"></i>';
      }

      if ($data['result_count'] >= 6 && $data['result_count'] <= 9) {
        $data['result'] = '<i class="fas fa-arrow-up"></i>';
      }

      array_push($newArray, $data);
    endforeach;

    return $newArray;
  }

  public function papiAreaPengembanganDescription($result)
  {
    usort($result, function ($a, $b) {
      return $a['result_count'] <=> $b['result_count'];
    });
    $lowestResult = $result[0];

    $flipZnK = array_combine(range(0, 9), array_reverse(range(0, 9)));

    if ($lowestResult == 'Sifat') {
      foreach ($lowestResult['items'] as $item):
        $lowestResult["items"] = array_map(function ($item) use ($flipZnK) {
          if (in_array($item['name'], ['Z', 'K']))
            $item['value'] = $flipZnK['value'];
          return $item;
        }, $lowestResult["items"]);
      endforeach;
    }
    usort($lowestResult['items'], function ($a, $b) {
      return $b['value'] <=> $a['value'];
    });
    $resultAreaPengembangan = reset($lowestResult['items']);
    $areaPengembangan = Normalization::where([
      ['type', 'PAPI-PENGEMBANGAN'],
      ['value', strtoupper($lowestResult['group'])],
      ['value_converter', strtoupper($resultAreaPengembangan['name'])],
    ])->first();
    return $areaPengembangan['description'] ?? '404 Not Found';
  }

  public function papiSkorEkstrim($result)
  {
    $resultsNormalization = Normalization::selectRaw('
          `value` AS `name`,
          `value_converter` AS `plus`,
          `description` AS `minus`
        ')
      ->where('type', "PAPI-EKSTRIM")
      ->where(function ($q) use ($result) {
        foreach ($result ?? [] as $key => $item):
          if ($key == 0) {
            $q->where(function ($sq) use ($item) {
              $sq->where('value', $item['name']);
              $sq->where('min', '<=', $item['value']);
              $sq->where('max', '>=', $item['value']);
            });
          } else {
            $q->orWhere(function ($sq) use ($item) {
              $sq->where('value', $item['name']);
              $sq->where('min', '<=', $item['value']);
              $sq->where('max', '>=', $item['value']);
            });
          }
        endforeach;
      });

    $resultsNormalization = $resultsNormalization->get()->keyBy('name')->toArray();
    $skorEkstrim = [];
    foreach ($result as $item):
      $group = $item['group'];
      if (isset($resultsNormalization[$item['name']])) {
        $skorEkstrim[$group][] = [
          'name' => $item['name'],
          'original_title' => $item['original_title'],
          'group' => $item['group'],
          'plus' => $resultsNormalization[$item['name']]['plus'] ?? '',
          'minus' => $resultsNormalization[$item['name']]['minus'] ?? ''
        ];
      }
    endforeach;

    return $skorEkstrim;
  }

  public function papiCiriBerdekatan($result)
  {
    $normalization = Normalization::where('type', "PAPI-AREABERDEKATAN")
      ->get();
    $result = collect($result)->keyBy('name')->toArray();
    $description = '';
    $result['T']['value'] = "3";
    $result['A']['value'] = "2";
    foreach ($normalization as $item):
      $item['description'];
      $exp = explode(",", $item['value']);
      $isCorrect = FALSE;
      foreach ($exp ?? [] as $itemValue):
        if ($item['min'] <= $result[trim($itemValue)]['value'] && $item['max'] >= $result[trim($itemValue)]['value']) {
          $isCorrect = TRUE;
        } else {
          $isCorrect = FALSE;
          break;
        }
      endforeach;

      if ($isCorrect) {
        $description .= "<p class='m-0'>Faktor {$item['value']} : {$item['description']}</p>";
      }
    endforeach;

    return $description;
  }

  public function rimbResult($participant)
  {
    $totalPoint = VPoints::selectRaw('
          `number`,
          SUM(`answers`) AS `total`
        ')
      ->where('participant_id', $participant['id'])
      ->orderBy('number')
      ->groupBy('number')
      ->get()
      ->keyBy('number')
      ->toArray();

    usort($totalPoint, function ($a, $b) {
      return $a['total'] <=> $b['total'];
    });

    $rank = 1;
    foreach ($totalPoint as &$item):
      $item['rank'] = $rank++;
    endforeach;

    $totalPoint = collect($totalPoint)->keyBy('number')->sortBy('number')->toArray();
    $total = 0;
    for ($i = 1; $i <= 12; $i++):
      $key = $i - 1;
      $result['answers'][$key][] = Normalization::where('type', 'RMIB')->where('min', $i)->groupBy('min')->first()['iq'] ?? '-';
      $arraymerge = VPoints::selectRaw('
          `number`,
          `answers`
        ')
        ->where('participant_id', $participant['id'])
        ->where('number', $i)
        ->get()
        ->pluck('answers')
        ->toArray();

      array_push($result['answers'][$key], ...$arraymerge);
      $result['answers'][$key][] = $totalPoint[$i]['total'];
      $result['answers'][$key][] = $totalPoint[$i]['rank'];

      $total +=$totalPoint[$i]['total'];
    endfor;

    $result['total_points'] = $total;
    $result['total_point_answers'] = $totalPoint;
    return $result;
  }

  public function rmibCountPoint($participant)
  {
    return VPoints::selectRaw('
          `number`,
          SUM(`answers`) AS `total`
        ')
      ->where('participant_id', $participant['id'])
      ->orderBy('number')
      ->groupBy('number')
      ->get()
      ->toArray();
  }

  public function rmibInterpretasi($result, $user)
  {
    usort($result, function ($a, $b) {
      return $a['total'] <=> $b['total'];
    });

    $result = array_slice($result, 0, 3);

    $gender['Female'] = 'Perempuan';
    $gender['Male'] = 'Laki-Laki';
    $vars = ['{nama}' => ucwords($user['fullname'])];

    $data = [];
    foreach ($result as $item):
      $normalization = Normalization::selectRaw('
          `iq` AS `type`,
          `value_converter` AS `deskripsi`,
          `description` AS `jurusan`,
          `description_2` AS `iq_1`,
          `description_3` AS `iq_2`
        ')
        ->where('value', $gender[$user['sex']])
        ->where('min', $item['number'])
        ->first()
        ->toArray();

      $normalization['deskripsi'] = strtr($normalization['deskripsi'], $vars);
      $normalization['jurusan'] = strtr($normalization['jurusan'], $vars);
      $normalization['number'] = $item['number'];
      $normalization['total'] = $item['total'];
      $data[] = $normalization;
    endforeach;
    return $data;

  }
}
