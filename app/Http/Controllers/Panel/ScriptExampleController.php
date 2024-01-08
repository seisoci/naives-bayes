<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Script;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ScriptExampleController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:script-example-list', ['only' => ['index', 'show']]);
    $this->middleware('can:script-example-create', ['only' => ['create', 'store']]);
    $this->middleware('can:script-example-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:script-example-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Contoh Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Contoh Naskah Soal"],
    ];
    if ($request->ajax()) {
      $data = Script::selectRaw('
           `scripts`.*,
           `categories`.`name` AS `category_name`
        ')
        ->leftJoin('categories', 'categories.id', '=', 'scripts.category_id')
        ->example(true);

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          return '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                 <li><a href="'.route('panel.scripts-example.edit', $row['id']).'" class="dropdown-item">Ubah</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'. $row['id'] .'" >Hapus</a></li>
              </ul>
          </div>';
        })->make();
    }
    return view('panel.scripts-example.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Contoh Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.scripts.index'), 'title' => "Contoh Naskah Soal"],
      ['url' => '#', 'title' => "Tambah Contoh Naskah Soal"],
    ];

    $config['form'] = (object) [
      'method' => 'POST',
      'action' => route('panel.scripts-example.store')
    ];

    return view('panel.scripts-example.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'type' => 'required',
      'introduction.duration' => 'nullable|integer'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $request->merge(['example_question' => 1]);

        $script = Script::create($request->all());
        if($request['extended'] == 'Y'){
          $data = $request['introduction'];
          $script->introduction()->create($data);
        }

        DB::commit();
        $response = response()->json($this->responseStore(true, '', route('panel.scripts-example.index')));
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
    $config['title'] = "Edit Contoh Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.scripts.index'), 'title' => "Contoh Naskah Soal"],
      ['url' => '#', 'title' => "Edit Contoh Naskah Soal"],
    ];

    $config['form'] = (object) [
      'method' => 'PUT',
      'action' => route('panel.scripts-example.update', $id)
    ];

    $data = Script::with('category', 'introduction')->findOrFail($id);

    return view('panel.scripts-example.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'type' => 'required'
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = Script::findOrFail($id);
        $data->update($request->all());
        $data->introduction()->delete();
        if($request['extended'] == 'Y'){
          $extend = $request['introduction'];
          $data->introduction()->create($extend);
        }

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.scripts-example.index')));
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
    $config['title'] = "Detail Contoh Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.scripts.index'), 'title' => "Contoh Naskah Soal"],
      ['url' => '#', 'title' => "Detail Contoh Naskah Soal"],
    ];

    $script = Script::with('category')->findOrFail($id);
    $questions = $script->questions()->paginate(10);

    return view('panel.scripts-example.show', compact('config', 'script', 'questions'));
  }

  public function destroy($id)
  {
    $data = Script::find($id);
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

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Script::where('title', 'LIKE', '%'.$request->q.'%')
      ->example(true)
      ->orderBy('title')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, title as text')
      ->get();

    $count = Script::where('title', 'LIKE', '%'.$request->q.'%')
      ->example(true)
      ->get()
      ->count();

    $endCount = $offset + $resultCount;
    $morePages = $count > $endCount;

    $results = array(
      "results" => $data,
      "pagination" => array(
        "more" => $morePages
      )
    );

    return response()->json($results);
  }
}
