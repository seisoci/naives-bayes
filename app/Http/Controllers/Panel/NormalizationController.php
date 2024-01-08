<?php

namespace App\Http\Controllers\Panel;

use App\Exports\NormalizationExport;
use App\Http\Controllers\Controller;
use App\Imports\NormalizationImport;
use App\Imports\ScriptQuestionImport;
use App\Models\Examination;
use App\Models\Normalization;
use App\Models\Participant;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class NormalizationController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:normalization-list', ['only' => ['index', 'show']]);
    $this->middleware('can:normalization-create', ['only' => ['create', 'store']]);
    $this->middleware('can:normalization-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:normalization-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Examination $examination)
  {
    $config['title'] = "Tabel Normalisasi";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Tabel Normalisasi"],
    ];

    if ($request->ajax()) {
      $data = Normalization::selectRaw('
           CASE
                WHEN min = max THEN min
                ELSE CONCAT(min, " - ", max)
           END AS range_name,
          `normalizations`.*
      ');

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                 <li><a href="'.route('panel.normalization.edit', $row['id']).'" class="dropdown-item">Ubah</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'. $row['id'] .'" >Hapus</a></li>
              </ul>
          </div>';
        })->make();
    }

    return view('panel.normalizations.index', compact('config', 'examination'));
  }

  public function create()
  {
    $config['title'] = "Tambah Tabel Normalisasi";
    $config['breadcrumbs'] = [
      ['url' => route('panel.normalization.index'), 'title' => "Tabel Normalisasi"],
      ['url' => '#', 'title' => "Tambah Naskah Soal"],
    ];

    $config['form'] = (object) [
      'method' => 'POST',
      'action' => route('panel.normalization.store')
    ];

    return view('panel.normalizations.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'type' => 'required',
      'min' => 'required',
      'max' => 'required',
      'value' => 'required'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        Normalization::create($request->all());
        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.normalization.index')));
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

  public function edit($id)
  {
    $config['title'] = "Edit Tabel Normalisasi";
    $config['breadcrumbs'] = [
      ['url' => route('panel.normalization.index'), 'title' => "Tabel Normalisasi"],
      ['url' => '#', 'title' => "Edit Naskah Soal"],
    ];

    $config['form'] = (object) [
      'method' => 'PUT',
      'action' => route('panel.normalization.update', $id)
    ];

    $data = Normalization::findOrFail($id);

    return view('panel.normalizations.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'type' => 'required',
      'min' => 'required',
      'max' => 'required',
      'value' => 'required'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = Normalization::findOrFail($id);
        $data->update($request->all());
        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.normalization.index')));
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

  public function show($id){
    $config['title'] = "Struktur Upload Tabel Normalisasi";
    $config['breadcrumbs'] = [
      ['url' => route('panel.normalization.index'), 'title' => "Tabel Normalisasi"],
      ['url' => '#', 'title' => "Edit Naskah Soal"],
    ];


    return view('panel.normalizations.show', compact('config'));
  }

  public function destroy($id)
  {
    $data = Normalization::find($id);
    DB::beginTransaction();
    try {
      $data->delete();
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

  public function import(Request $request)
  {
    try {
      Excel::import(new NormalizationImport(), request()->file('file'));
      $response = response()->json([
        'status' => 'success',
        'message' => 'Data berhasil dimport'
      ]);
    } catch (\Throwable $throw) {
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

  public function exportTemplate()
  {
    return Excel::download(new NormalizationExport(), 'normalization.xlsx');
  }

}
