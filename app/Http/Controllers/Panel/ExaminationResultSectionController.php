<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Examination;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;

class ExaminationResultSectionController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:examinations-list', ['only' => ['index', 'show']]);
    $this->middleware('can:examinations-create', ['only' => ['create', 'store']]);
    $this->middleware('can:examinations-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:examinations-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Examination $examination, Participant $participant)
  {
    $config['title'] = "Lembar Jawaban";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Lembar Jawaban"],
    ];

    $participant->load('participant_sections.section.script');
    $participant_section_id = $request['participant_section_id'] ?? NULL;

    $section = ParticipantSection::find($participant_section_id);
    $questions = NULL;
    if ($participant_section_id) {
      $questions = Answer::with('points', 'question.choices', 'question.scores.choices', 'answered_choice.choice')
        ->when($participant_section_id, function ($q) use ($request) {
          return $q->where('answers.participant_section_id', $request['participant_section_id']);
        })
        ->leftJoin('v_points', 'v_points.answer_id', '=', 'answers.id')
        ->get();

      if (isset($section['section']['script']['type']) && $section['section']['script']['type'] != 'Essay') {
        $questions->each(function ($question) {
          $question->answers = $question->answered_choice->pluck('choice.key')->implode(', ');
        });
      } else {
        $questions->each(function ($question) {
          $question->answers = $question->answered_essay->answer ?? '';
        });
      }
    }

    return view('panel.examinations.results.sections.index', compact('config', 'examination', 'participant', 'questions', 'participant_section_id'));
  }
}
