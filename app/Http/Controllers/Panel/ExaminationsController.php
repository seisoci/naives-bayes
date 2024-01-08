<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Choice;
use App\Models\Examination;
use App\Models\Question;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ExaminationsController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:examinations-list', ['only' => ['index', 'show']]);
    $this->middleware('can:examinations-create', ['only' => ['create', 'store']]);
    $this->middleware('can:examinations-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:examinations-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Tambah Ujian";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Ujian"],
    ];

    if ($request->ajax()) {
      $model = Examination::query();

      return DataTables::of($model)->make(true);
    }
    return view('panel.examinations.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Ujian";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Ujian"],
      ['url' => '#', 'title' => "Tambah Ujian"],
    ];
    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.examinations.store')
    ];

    return view('panel.examinations.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => [
        'required',
        'max:255',
        'unique:examinations'
      ],
      'description' => 'nullable',
      'instruction' => 'nullable',
      'price' => 'required|numeric|min:0',
      'status' => 'required',
      'category_id' => 'required|integer',
      'poster' => 'nullable|mimes:jpeg,jpg,png'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {

        $data = $validator->safe();
        if ($request->hasFile('poster')) {
          if ($request->file('poster')->isValid()) {
            $extension = $request->poster->getClientOriginalExtension();
            $filename = time() . '_' . str()->snake($data['name']) . '.' . $extension;
            $data['poster'] = $request->poster->storeAs('examinations', $filename, 'public');
          }
        }
        $examination = new Examination;
        foreach ($data as $column => $value) {
          if ($column == 'duration') {
            $value = $value * 60;
          }
          $examination->{$column} = $value;
        }
        $examination->save();

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.examinations.index')));
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

  public function edit(Examination $examination)
  {
    $config['title'] = "Edit Ujian";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Ujian"],
      ['url' => '#', 'title' => "Edit Ujian"],
    ];
    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.examinations.update', $examination['id'])
    ];

    $examination->load('category');

    return view('panel.examinations.form', compact('config', 'examination'));
  }

  public function update(Request $request, Examination $examination)
  {
    $validator = Validator::make($request->all(), [
      'name' => [
        'required',
        'max:255',
        Rule::unique('examinations')->ignore($examination['id'])
      ],
      'description' => 'nullable',
      'instruction' => 'nullable',
      'price' => 'required|numeric|min:0',
      'status' => 'required',
      'category_id' => 'required|integer',
      'poster' => 'nullable|mimes:jpeg,jpg,png'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $old_image = $examination->poster;

        $data = $validator->safe();
        if ($request->hasFile('poster')) {
          if ($request->file('poster')->isValid()) {
            $extension = $request->poster->getClientOriginalExtension();
            $filename = time() . '_' . str()->snake($data['name']) . '.' . $extension;
            $data['poster'] = $request->poster->storeAs('examinations', $filename, 'public');
          }
        }

        foreach ($data as $column => $value) {
          $examination->{$column} = $value;
        }
        $examination->save();
        if (!empty($old_image)) {
          Storage::disk('public')->delete($old_image);
        }

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.examinations.show', $examination['id'])));
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

  public function show(Examination $examination)
  {
    $examination = Examination::with([
        'sections.script'
      ])
      ->withSum('sections', 'duration')
      ->findOrFail($examination['id']);

    $examination['question_count'] = $examination->sections->map(function ($section) {
      return $section['script']['questions_count'];
    })->sum();

    return view('panel.examinations.show', compact('examination'));
  }

  public function destroy($id)
  {
    $data = Examination::find($id);
    DB::beginTransaction();
    try {
      $data->delete();
      DB::commit();

      $response = response()->json($this->responseDelete(true, route('panel.examinations.index')));
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

}
