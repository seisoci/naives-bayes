<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionDiscussion;
use App\Models\Score;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ScriptExampleQuestionDiscussionController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:script-example-list', ['only' => ['index', 'show']]);
    $this->middleware('can:script-example-create', ['only' => ['create', 'store']]);
    $this->middleware('can:script-example-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:script-example-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Script $script, Question $question)
  {
    $config['title'] = "Pembahasan Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Pembahasan Soal"],
    ];

    $question->load('discussion');

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.scripts-example.questions.discussions.store', ['script' => $script['id'], 'question' => $question['id']])
    ];

    return view('panel.scripts-example.discussions.form', compact('config', 'script', 'question'));
  }

  public function store(Request $request, Script $script, Question $question)
  {
    $validator = Validator::make($request->all(), [
      'content' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {

        QuestionDiscussion::updateOrCreate([
          'question_id' => $question['id']
        ],[
          'script_id' => $script['id'],
          'content' => $request['content'],
        ]);

        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.scripts-example.questions.index', $script['id'])));
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
