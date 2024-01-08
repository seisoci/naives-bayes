<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\AnswerChoice;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Models\Section;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
  public function show(Request $request, Participant $participant, ParticipantSection $section, Answer $answer)
  {
    if (empty($answer)) {
      return response()->api(['_' => ['Not Found']], 404);
    }

    $section = Section::with('script')->find($section['section_id']);
    $data['type'] = $section['script']['type'];
    $data['answers'] = $answer->load(['question', 'choiced', 'answered_essay']);
    $data['answers']['answer'] = $answer['answered_essay']['answer'] ?? '';

    if ($data['type'] != 'Essay') {
      $data['answers'] = $answer->load(['question.choices', 'answered_choice']);
      $data['answers']['choice_id'] = $data['answers']->answered_choice->pluck('choice_id')->filter()->all();
    }

    return response()->api($data);
  }

  public function update_duration_used(Request $request, $participant, $participantSection)
  {
    if (isset($request->type) && $request->type == 'RMIB') {
      $score = 1;
      foreach ($request->answers ?? [] as $item):
        $answers = Answer::where([
          ['participant_section_id', $participantSection],
          ['question_id', $item['question_id']],
        ])->firstOrFail();

        $answers->answered_choice()->delete();
        $answers->answered_choice()->create([
          'answer' => $score
        ]);
        $score++;
      endforeach;
    }

    foreach ($request->answers ?? [] as $item) {
      if (isset($item['choice_id']) && !blank($item['choice_id'])) {
        AnswerChoice::where('answer_id', $item['id'])->delete();
      }

      if (isset($item['choice_id']) && is_array($item['choice_id'])) {
        foreach ($item['choice_id'] ?? [] as $itemChoice):
          $answer = AnswerChoice::create([
            'answer_id' => $item['id'],
            'choice_id' => $itemChoice,
          ]);
        endforeach;
      }

      if (isset($item['answer']) || isset($item['choice_id'])) {
        AnswerChoice::updateOrCreate([
          'answer_id' => $item['id'],
          'choice_id' => $item['choice_id'] ?? NULL,
          'answer' => $item['answer'] ?? NULL,
        ]);
      }
    }
    return response()->api();
  }

  public function update_status(Request $request, $participant, $participant_section)
  {
    $data = $request['participant'];
    ParticipantSection::findOrFail($participant_section)->update($data);
    return response()->api();
  }
}
