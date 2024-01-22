<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Hero;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HeroController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:hero-list', ['only' => ['index', 'show']]);
    $this->middleware('can:hero-create', ['only' => ['create', 'store']]);
    $this->middleware('can:hero-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:hero-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Tambah Hero";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Hero"],
    ];

    if ($request->ajax()) {
      $model = Hero::query();

      return DataTables::of($model)
        ->addColumn('action', function ($row) {
          $actionBtn = '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="'.route('panel.heroes.edit', $row->id).'">Edit</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'.$row->id.'" >Hapus</a></li>
              </ul>
          </div>';

          return $actionBtn;
        })
        ->make(true);
    }
    return view('panel.heroes.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Hero";
    $config['breadcrumbs'] = [
      ['url' => route('panel.heroes.index'), 'title' => "Hero"],
      ['url' => '#', 'title' => "Tambah Hero"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.heroes.store')
    ];

    return view('panel.heroes.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'nama' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $img = isset($request->image) && !empty($request->image) ? FileUpload::uploadImage('image', $dimensions) : null;

        Hero::create([
          'nama' => ucwords($request['nama']),
          'image' => $img,
          'deskripsi' => $request['deskripsi'],
        ]);

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.heroes.index')));
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
    $config['title'] = "Edit Hero";
    $config['breadcrumbs'] = [
      ['url' => route('panel.heroes.index'), 'title' => "Hero"],
      ['url' => '#', 'title' => "Edit Hero"],
    ];
    $data = Hero::where('id', $id)
      ->first();

    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.heroes.update', $id)
    ];
    return view('panel.heroes.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'nama' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $image = null;
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $data = Hero::findOrFail($id);
        if (isset($request['image']) && !empty($request['image'])) {
          $image = FileUpload::uploadImage('image', $dimensions, 'storage', $data['image']);
        }
        $data->update([
          'name' => ucwords($request['nama']),
          'deskripsi' => $request['deskripsi'],
          'image' => $image,
        ]);
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.heroes.index')));
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

  public function destroy($id)
  {
    $response = response()->json($this->responseDelete(false));
    $data = Hero::find($id);
    DB::beginTransaction();
    try {
      if ($data->delete()) {
        Storage::disk('public')->delete(["images/original/$data->image", "images/thumbnail/$data->image"]);
        $response = response()->json($this->responseDelete(true));
      }
      DB::commit();
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
    $data = Hero::where('nama', 'LIKE', '%'.$request->q.'%')
      ->orderBy('nama')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, nama as text')
      ->get();

    $count = Hero::where('nama', 'LIKE', '%'.$request->q.'%')
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
