<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExaminationSectionController extends Controller
{
  public function show(Request $request, Participant $participant, ParticipantSection $section)
  {
    $participant->load('examination');
    if ($request->ajax()) {
      $sectionType = Section::with('script')->find($section['section_id']);
      $data['type'] = $sectionType['script']['type'];
      if($participant->examination->category_id == 6){
        $data['answers'] = $section->load([
          'section.script.questions',
          'section.script.introduction',
          'section.script.example.introduction',
          'section.script.example.questions.discussion',
          'section.script.example.questions.choices',
          'participant',
          'examination',
        ])->toArray();

        $question = $data['answers']['section']['script']['questions'];
        $input =  $data['answers']['section']['number'] * -1;
        $splice = array_splice($question, ($input + 1));
        $data['answers']['section']['script']['questions'] = array_merge($splice, $question);

        return response()->api($data);
      }

      $data['answers'] = $section->load([
        'participant',
        'examination',
        'section.script.introduction',
        'section.script.example.introduction',
        'section.script.example.questions.discussion',
        'section.script.example.questions.choices',
        'answers.answered_essay',
        'answers'
      ]);
      if ($data['type'] != 'Essay') {
        $data['answers'] = $section->load([
          'participant',
          'examination',
          'section.script.introduction',
          'section.script.example.introduction',
          'section.script.example.questions.discussion',
          'section.script.example.questions.choices',
          'answers.answered_choice',
          'answers'
        ]);

        $data['answers']['choice_id'] = $data['answers']->answers->flatMap(function ($answer) {
          return $answer->answered_choice->pluck('choice_id')->filter();
        });
      }

      return response()->api($data);
    }

    return view('web.examinations.sections.show', ['participant' => $participant, 'participant_section' => $section]);
  }

  public function update(Request $request, Participant $participant, ParticipantSection $section)
  {
    DB::beginTransaction();
    try {
      $section->update($request['participant']);
      DB::commit();
      $response = response()->api('success');
    } catch (\Throwable $throw) {
      Log::error($throw);
      DB::rollBack();
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
