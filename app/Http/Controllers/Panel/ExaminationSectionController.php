<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Section;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ExaminationSectionController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:examinations-list', ['only' => ['index', 'show']]);
    $this->middleware('can:examinations-create', ['only' => ['create', 'store']]);
    $this->middleware('can:examinations-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:examinations-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Examination $examination)
  {
    $config['title'] = "Tambah Sesi Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Sesi Naskah Soal"],
    ];

    if ($request->ajax()) {
      $model = Section::where('examination_id', $examination['id'])
        ->with(['script.category', 'script.subcategory']);

      return DataTables::of($model)
        ->make(true);
    }

    return view('panel.examinations.sections.index', compact('config', 'examination'));
  }

  public function create(Examination $examination)
  {
    $config['title'] = "Tambah Sesi Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Sesi Naskah Soal"],
      ['url' => '#', 'title' => "Tambah Sesi Naskah Soal"],
    ];
    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.examinations.sections.store', $examination['id'])
    ];

    return view('panel.examinations.sections.form', compact('config', 'examination'));
  }

  public function store(Request $request, Examination $examination)
  {
    $request->merge([
      'examination_id' => $examination->id
    ]);
    $validator = Validator::make($request->all(), [
      'number' => 'required|numeric',
      'sorting_mode' => 'required',
      'control_mode' => 'required',
      'auto_next' => 'required',
      'duration' => 'numeric|nullable',
      'script_id' => 'required|exists:scripts,id',
      'examination_id' => 'required|exists:examinations,id',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = $validator->safe();
        $section = new Section;
        foreach ($data as $column => $value) {
          if ($column == 'duration') {
            $value = $value * 60;
          }
          $section->{$column} = $value;
        }
        $section->save();

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.examinations.sections.index', $examination['id'])));
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

  public function edit(Examination $examination, Section $section)
  {
    $config['title'] = "Edit Sesi Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Sesi Naskah Soal"],
      ['url' => '#', 'title' => "Edit Sesi Naskah Soal"],
    ];
    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.examinations.sections.update', [
        'examination' => $examination['id'],
        'section' => $section['id'],
      ])
    ];

    return view('panel.examinations.sections.form', compact('config', 'examination', 'section'));
  }

  public function update(Request $request, Examination $examination, Section $section)
  {
    $request->merge([
      'examination_id' => $examination->id
    ]);
    $validator = Validator::make($request->all(), [
      'number' => 'required|numeric',
      'sorting_mode' => 'required',
      'control_mode' => 'required',
      'auto_next' => 'required',
      'duration' => 'numeric|nullable',
      'script_id' => 'required|exists:scripts,id',
      'examination_id' => 'required|exists:examinations,id',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = $validator->safe();
        foreach ($data as $column => $value) {
          if ($column == 'duration') {
            $value = $value * 60;
          }
          $section->{$column} = $value;
        }
        $section->save();

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.examinations.sections.index', $examination['id'])));
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

  public function destroy(Request $request, Examination $examination, Section $section)
  {
    DB::beginTransaction();
    try {
      $section->delete();
      DB::commit();

      $response = response()->json($this->responseDelete(true));
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
