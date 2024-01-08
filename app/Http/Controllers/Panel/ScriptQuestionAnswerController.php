<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Score;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScriptQuestionAnswerController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:script-list', ['only' => ['index', 'show']]);
    $this->middleware('can:script-create', ['only' => ['create', 'store']]);
    $this->middleware('can:script-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:script-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Script $script, Question $question)
  {
    $config['title'] = "Kunci Jawaban";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Kunci Jawaban"],
    ];

    $question->load(['choices', 'scores.choices']);

    $choice = [];
    if(isset($question['scores'])){
      $choice[] = [
        'value' => '',
        'choices' => $question['choices']
      ];
    }

    $answer = [];
    foreach ($question['scores'] as $item):
      $answer[] = [
        'value' => $item['value'],
        'choices' => $question['choices']->toArray(),
        'answer' => $item->choices->pluck('id')->toArray()
      ];
    endforeach;

    return view('panel.scripts.answers.index', compact('config', 'script', 'question', 'answer', 'choice'));
  }

  public function store(Request $request, Script $script, Question $question)
  {
    $validator = Validator::make($request->all(), [
      'choices' => 'required|array',
      'choices.*.value' => 'required|integer',
      'choices.*.choice_id' => 'required|array',
      'choices.*.choice_id.*' => 'required|integer',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        Score::where('question_id', $question['id'])->delete();
        foreach ($request['choices'] as $item):
          $score = Score::create([
            'question_id' => $question['id'],
            'value' => $item['value']
          ]);

          $score->choices()->sync($item['choice_id']);
        endforeach;

        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.scripts.questions.index', $script['id'])));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json(['error' => $throw->getMessage()]);
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }
}
