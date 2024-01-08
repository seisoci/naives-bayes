<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Models\Script;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
  public function store(Request $request, Participant $participant, ParticipantSection $section)
  {
    if ($request->ajax()) {
      $answers = [];


      $example = Script::with([
        'example.questions.discussion',
        'example.questions.choices',
        'example.questions.correct.choice',
      ])->find($section->section->script_id);

      $section = Script::find($example['example_id']);

      if (isset($request->only('answers')['answers'])) {
        $answers = collect($request->only('answers')['answers'])->groupBy('question_id')->map(function ($group) use($section) {
          if($section['type'] == 'Essay'){
            return $group->pluck('answer')->values()->toArray();
          }
          return $group->pluck('choice_id')->values()->toArray();
        })->toArray();
      }

      $example['example']['questions'] = collect($example['example']['questions'])->map(function ($question) use ($answers, $section) {
        $correct = collect($question['correct'])->groupBy('score_id')->map(function ($grouped) use ($answers, $section) {
          if($section['type'] == 'Essay'){
            return $grouped->pluck('choice.content')->toArray();
          }
          return $grouped->pluck('choice.key')->toArray();
        })->toArray();
        $question['my_answers'] = $answers[$question['id']] ?? [];
        $question['correct_answer'] = $correct;
        unset($question['correct']);
        return $question;
      })->toArray();


      $data['type'] = $section['type'];
      $data['answers'] = $example;


      return response()->api($data);
    }
  }

  function calculateScore($studentAnswer, $scoreTable)
  {
    $totalScore = 0;

    foreach ($scoreTable as $correctAnswers) {
      $isCorrect = true;

      foreach ($correctAnswers as $correctAnswer) {
        if (!in_array($correctAnswer, $studentAnswer)) {
          $isCorrect = false;
          break;
        }
      }

      if ($isCorrect) {
        $totalScore = 1;
      }
    }

    return $totalScore;
  }

}
