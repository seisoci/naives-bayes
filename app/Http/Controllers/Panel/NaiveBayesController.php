<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\NaiveBayesClassifier;
use App\Http\Controllers\Controller;
use App\Models\NaiveBayes;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class NaiveBayesController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:naives-bayes-list', ['only' => ['index', 'show']]);
    $this->middleware('can:naives-bayes-create', ['only' => ['create', 'store']]);
    $this->middleware('can:naives-bayes-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:naives-bayes-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Tambah Dataset";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Dataset"],
    ];

    if ($request->ajax()) {
      $model = NaiveBayes::selectRaw('
        `naive_bayes`.*
        ')
        ->with(['hero', 'hero_musuh']);

      return DataTables::of($model)
        ->addColumn('action', function ($row) {
          $actionBtn = '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="' . route('panel.naive-bayes.edit', $row->id) . '">Edit</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="' . $row->id . '" >Hapus</a></li>
              </ul>
          </div>';

          return $actionBtn;
        })
        ->make(true);
    }
    return view('panel.naives-bayes.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Dataset";
    $config['breadcrumbs'] = [
      ['url' => route('panel.naive-bayes.index'), 'title' => "Tabel Dataset"],
      ['url' => '#', 'title' => "Tambah Dataset"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.naive-bayes.store')
    ];

    return view('panel.naives-bayes.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'hero_id' => 'required',
      'hero_musuh_id' => 'required',
      'tipe_build' => 'required',
      'hasil' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        NaiveBayes::create($request->all());
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.naive-bayes.index')));
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
    $config['title'] = "Edit Dataset";
    $config['breadcrumbs'] = [
      ['url' => route('panel.naive-bayes.index'), 'title' => "Dataset"],
      ['url' => '#', 'title' => "Edit Dataset"],
    ];
    $data = NaiveBayes::with('hero', 'hero_musuh')->findOrFail($id);
    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.naive-bayes.update', $id)
    ];
    return view('panel.naives-bayes.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'hero_id' => 'required',
      'hero_musuh_id' => 'required',
      'tipe_build' => 'required',
      'hasil' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = NaiveBayes::findOrFail($id);
        $data->update($request->all());
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.naive-bayes.index')));
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

  public function show($id)
  {
    $config['title'] = "Prediksi Kemenangan";
    $config['breadcrumbs'] = [
      ['url' => route('panel.naive-bayes.index'), 'title' => "Tabel Dataset"],
      ['url' => '#', 'title' => "Prediksi Kemenangan"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.naive-bayes.store')
    ];

    return view('panel.naives-bayes.prediksi', compact('config'));
  }

  public function prediksi(Request $request)
  {
      $dataTraining = NaiveBayes::selectRaw('
        `hero_pick`.`nama` as `hero`,
        `hero_pick`.`nama` as `hero_musuh`,
        `naive_bayes`.`tipe_build`,
        `naive_bayes`.`emblem`,
        `naive_bayes`.`hasil`
      ')
      ->leftJoin('heroes AS hero_pick', 'hero_pick.id', '=', 'naive_bayes.hero_id')
      ->leftJoin('heroes AS hero_musuh', 'hero_musuh.id', '=', 'naive_bayes.hero_musuh_id')
      ->get()
      ->toArray();

    $naiveBayes = new NaiveBayesClassifier($dataTraining);
    $result = $naiveBayes->predict($request->all());
//    dd($naiveBayes->getTableCounts());
    $naiveBayesRumus = $naiveBayes->getRumusPredict();
    $naiveBayesClass = $naiveBayes->getClassCount();
    $naiveBayes = $naiveBayes->getTableCounts();
    $render = view('panel.naives-bayes.result', compact('result', 'naiveBayes', 'naiveBayesRumus', 'naiveBayesClass'))->render();

    return response()->json($render);
  }

  public function destroy($id)
  {
    $response = response()->json($this->responseDelete(false));
    $data = NaiveBayes::find($id);
    DB::beginTransaction();
    try {
      if ($data->delete()) {
        $response = response()->json($this->responseDelete(true));
      }
      DB::commit();
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
