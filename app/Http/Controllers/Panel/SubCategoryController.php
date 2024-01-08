<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SubCategoryController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:sub-category-list', ['only' => ['index', 'show']]);
    $this->middleware('can:sub-category-create', ['only' => ['create', 'store']]);
    $this->middleware('can:sub-category-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:sub-category-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Sub Kategori";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Sub Kategori"],
    ];
    if ($request->ajax()) {
      $data = SubCategory::selectRaw('
          `sub_categories`.*,
          `categories`.`id` AS `category_id`,
          `categories`.`name` AS `category_name`
      ')
      ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id');

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $actionBtn = '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                 <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-id="'.$row->id.'"
                            data-bs-name="'.$row->name.'"
                            data-bs-category_id="'.$row->category_id.'"
                            data-bs-category_name="'.$row->category_name.'"
                            class="dropdown-item">Ubah</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'.$row->id.'" >Hapus</a></li>
              </ul>
          </div>';
          return $actionBtn;
        })->make();
    }
    return view('panel.sub-category.index', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:categories',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        SubCategory::create($request->all());
        DB::commit();
        $response = response()->json($this->responseStore(true));
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

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required|unique:categories,name,' . $id,
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $data = SubCategory::findOrFail($id);
        $data->update($request->all());
        DB::commit();
        $response = response()->json($this->responseUpdate(true));
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
    $data = SubCategory::find($id);
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
    $data = SubCategory::where('name', 'LIKE', '%'.$request->q.'%')
      ->where('category_id', $request['category_id'])
      ->orderBy('name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, name as text')
      ->get();

    $count = SubCategory::where('name', 'LIKE', '%'.$request->q.'%')
      ->where('category_id', $request['category_id'])
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
