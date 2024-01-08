<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Score;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ScriptExampleQuestionController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:script-example-list', ['only' => ['index', 'show']]);
    $this->middleware('can:script-example-create', ['only' => ['create', 'store']]);
    $this->middleware('can:script-example-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:script-example-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Script $script)
  {
    $config['title'] = "Kelola Butir Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Kelola Butir Soal"],
    ];

    if ($request->ajax()) {
      $model = Question::with(['choices', 'scores.choices', 'discussion'])->where('script_id', $script->id);
      return DataTables::of($model)->make(true);
    }

    return view('panel.scripts-example.questions.index', compact('config', 'script'));
  }

  public function create(Request $request, Script $script)
  {
    $config['title'] = "Tambah Kelola Butir Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Tambah Kelola Butir Soal"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.scripts-example.questions.store', $script['id'])
    ];

    return view('panel.scripts-example.questions.form', compact('config', 'script'));
  }

  public function store(Request $request, Script $script)
  {
    $validator = Validator::make($request->all(), [
      'number' => 'required',
      'sentence' => 'nullable',
      'heading' => 'nullable',
      'choices' => 'nullable|array',
      'choices.*.key' => 'required',
      'choices.*.content' => 'required',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $question_data = $validator->safe()->merge([
          'script_id' => $script->id,
        ])->except(['choices']);
        $choices_data = $validator->safe()->only(['choices'])['choices'] ?? [];
        $question = new Question;
        DB::transaction(function () use ($question, $question_data, $choices_data) {
          foreach ($question_data as $column => $value) {
            $question->{$column} = $value;
          }
          $question->save();
          $choices = [];
          foreach ($choices_data ?? [] as $index => $row) {
            array_push($choices, new Choice($row));
          }
          $question->choices()->saveMany($choices);

        });
        $question->refresh();
        $question->choices;

        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.scripts-example.questions.index', $script['id'])));
      } catch (\Throwable $throw) {
        DB::rollBack();
        Log::error($throw);
        $response = response()->json(['error' => $throw->getMessage()]);
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function edit(Request $request, Script $script, Question $question)
  {
    $config['title'] = "Edit Kelola Butir Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Edit Kelola Butir Soal"],
    ];

    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.scripts-example.questions.update', ['script' => $script['id'], 'question' => $question['id']])
    ];

    return view('panel.scripts-example.questions.form', compact('config', 'script', 'question'));
  }

  public function update(Request $request, Script $script, Question $question)
  {
    $validator = Validator::make($request->all(), [
      'number' => 'required',
      'sentence' => 'nullable',
      'heading' => 'nullable',
      'choices' => 'required|array',
      'choices.*.key' => 'required',
      'choices.*.content' => 'required',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {

        $question_data = $validator->safe()->merge([
          'script_id' => $script->id,
        ])->except(['choices']);
        $choices_data = $validator->safe()->only(['choices'])['choices'];

        DB::transaction(function () use ($question, $question_data, $choices_data) {
          $question->choices()->delete();
          foreach ($question_data as $column => $value) {
            $question->{$column} = $value;
          }
          $question->save();
          $choices = [];
          foreach ($choices_data as $index => $row) {
            array_push($choices, new Choice($row));
          }
          $question->choices()->saveMany($choices);

        });
        $question->refresh();
        $question->choices;

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.scripts-example.questions.index', $script['id'])));
      } catch (\Throwable $throw) {
        DB::rollBack();
        Log::error($throw);
        $response = response()->json(['error' => $throw->getMessage()]);
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(Script $script, Question $question)
  {
    DB::beginTransaction();
    try {
      $question->delete();
      DB::commit();
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dihapus'
      ]);
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
