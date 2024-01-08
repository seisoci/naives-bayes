<?php

namespace App\Http\Controllers\Panel;

use App\Exports\ScriptQuestionExport;
use App\Http\Controllers\Controller;
use App\Imports\ScriptQuestionAnswersImport;
use App\Imports\ScriptQuestionImport;
use App\Models\Choice;
use App\Models\Question;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class ScriptQuestionController extends Controller
{

  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:script-list', ['only' => ['index', 'show']]);
    $this->middleware('can:script-create', ['only' => ['create', 'store']]);
    $this->middleware('can:script-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:script-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Script $script)
  {
    $config['title'] = "Kelola Butir Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Kelola Butir Soal"],
    ];

    if ($request->ajax()) {
      $model = Question::with(['choices', 'scores.choices'])->where('script_id', $script->id);
      return DataTables::of($model)->make(true);
    }

    return view('panel.scripts.questions.index', compact('config', 'script'));
  }

  public function create(Request $request, Script $script)
  {
    $config['title'] = "Tambah Kelola Butir Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Tambah Kelola Butir Soal"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.scripts.questions.store', $script['id'])
    ];

    return view('panel.scripts.questions.form', compact('config', 'script'));
  }

  public function store(Request $request, Script $script)
  {
    $validator = Validator::make($request->all(), [
      'number' => 'required',
      'sentence' => 'nullable',
      'heading' => 'nullable',
      'choices' => 'nullablearray',
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
        DB::transaction(function () use ($question, $question_data, $choices_data, $script) {
          foreach ($question_data as $column => $value) {
            $question->{$column} = $value;
          }
          $question->save();
          $choices = [];
          foreach ($choices_data ?? [] as $index => $row) {
            $row['content'] =  $script['type'] == 'Exam' ? strip_tags($row['content']) : $row['content'];
            $choices[] = new Choice($row);
          }
          if($choices){
            $question->choices()->saveMany($choices);
          }

        });
        $question->refresh();
        $question->choices;

        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.scripts.questions.index', $script['id'])));
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
      'action' => route('panel.scripts.questions.update', ['script' => $script['id'], 'question' => $question['id']])
    ];

    $question->load('choices');

    return view('panel.scripts.questions.form', compact('config', 'script', 'question'));
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

        DB::transaction(function () use ($question, $question_data, $choices_data, $script) {
          $question->choices()->delete();
          foreach ($question_data as $column => $value) {
            $question->{$column} = $value;
          }
          $question->save();
          $choices = [];
          foreach ($choices_data as $index => $row) {
            $row['content'] = $row['content'];
            $choices[] = new Choice($row);
          }
          $question->choices()->saveMany($choices);

        });
        $question->refresh();
        $question->choices;

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.scripts.questions.index', $script['id'])));
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

  public function export(Request $request, Script $script)
  {
    $rows = [
      [
        'number' => 'No',
        'heading' => 'Tajuk',
        'sentence' => 'Pertanyaan',
      ]
    ];

    $keysAlphabet = range('A', 'Z');
    $questions = $script->questions()->orderBy('number', 'asc')->get();
    $headerLengthChoice = 0;
    foreach ($questions as $question):
      $row = [
        'number' => $question->number,
        'heading' => $question->heading,
        'sentence' => $question->sentence,
      ];
      $headerLengthChoice = $headerLengthChoice < $question->choices->count() ? $question->choices->count() : $headerLengthChoice;
      foreach ($question->choices as $choice):
        $row[$choice->key] = $choice->content;
      endforeach;
      $rows[] = $row;
    endforeach;

    for ($i = 0; $i < $headerLengthChoice; $i++):
      $rows[0][$keysAlphabet[$i]] = "Pilihan {$keysAlphabet[$i]}";
    endfor;

    $documentTitle= str_replace(array("/", "\\", ":", "*", "?", "«", "<", ">", "|"), "-", $script['title']);

    return Excel::download(new ScriptQuestionExport($rows), "{$documentTitle}.xlsx");
  }

  public function import(Request $request, Script $script)
  {
    try {
      $script->questions()->delete();

      Excel::import(new ScriptQuestionImport($script->id), request()->file('file'));
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dimport'
      ]);
    } catch (\Throwable $throw) {
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

  public function exportAnswers(Request $request, Script $script)
  {
    $rows = [
      [
        'number' => 'No Soal',
        'value' => 'Point',
        'alias' => 'Alias',
        'choice_key' => 'Kunci Jawaban (A,B,C,D,E, Dst)',
      ]
    ];

    $questions = $script->questions()->orderBy('number', 'asc')->get();
    foreach ($questions as $item):
      $row['number'] = $item->number;
      foreach ($item->scores as $itemScore):
        $row['value'] = $itemScore['value'];
        $row['alias'] = $itemScore['alias'];
        $row['choice_key'] = $itemScore->choices()->pluck('key')->implode(', ');
        $rows[] = $row;
      endforeach;
      endforeach;
    return Excel::download(new ScriptQuestionExport($rows), "Kunci Jawaban {$script['title']}.xlsx");
  }

  public function importAnswers(Request $request, Script $script)
  {
    try {
      $script->questions->each(function ($question) {
        $question->scores()->delete();
      });

      Excel::import(new ScriptQuestionAnswersImport($script->id), request()->file('file'));
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dimport'
      ]);
    } catch (\Throwable $throw) {
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
