<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ResponseStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:users-list', ['only' => ['index', 'show']]);
    $this->middleware('can:users-create', ['only' => ['create', 'store']]);
    $this->middleware('can:users-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:users-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Peserta";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Peserta"],
    ];
    if ($request->ajax()) {
      $data = User::query();

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('age', function ($row) {
          return Carbon::parse($row['birthday'])->age;
        })
        ->addColumn('action', function ($row) {
          $actionBtn = '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="'.route('panel.users.edit', $row->id).'">Edit</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'.$row->id.'" >Hapus</a></li>
              </ul>
          </div>';

          return $actionBtn;
        })->make();
    }
    return view('panel.users.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Peserta";
    $config['breadcrumbs'] = [
      ['url' => route('panel.users.index'), 'title' => "Role"],
      ['url' => '#', 'title' => "Tambah Peserta"],
    ];
    $config['form'] = (object) [
      'method' => 'POST',
      'action' => route('panel.users.store')
    ];
    return view('panel.users.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'password' => 'required|between:4,255|confirmed',
      'email' => 'required|unique:users,email|email',
      'fullname' => 'required',
      'birthday' => 'required',
      'gender' => 'required',
      'picture' => 'image|mimes:jpg,png,jpeg',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $img = isset($request->picture) && !empty($request->picture) ? FileUpload::uploadImage('picture', $dimensions) : null;

        $userRequest = $request->except('picture', 'fullname');
        $userRequest['fullname'] = ucwords($request['fullname']);
        $userRequest['picture'] = $img;
        $userRequest['password'] = Hash::make($request['password']);
        User::create($userRequest);

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.users.index')));
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
    $config['title'] = "Edit Peserta";
    $config['breadcrumbs'] = [
      ['url' => route('panel.users.index'), 'title' => "Peserta"],
      ['url' => '#', 'title' => "Edit Peserta"],
    ];
    $data = User::findOrFail($id);
    $config['form'] = (object) [
      'method' => 'PUT',
      'action' => route('panel.users.update', $id)
    ];
    return view('panel.users.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email|unique:users,email,'.$request['email'].',email',
      'fullname' => 'required',
      'birthday' => 'required',
      'gender' => 'required',
      'picture' => 'image|mimes:jpg,png,jpeg',
      'password' => 'nullable|between:4,255|confirmed',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $image = null;
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $data = User::findOrFail($id);
        if (isset($request['picture']) && !empty($request['picture'])) {
          $image = FileUpload::uploadImage('picture', $dimensions, 'storage', $data['picture']);
        }

        $userRequest = $request->all();
        $userRequest['fullname'] = ucwords($request['fullname']);
        $userRequest['picture'] = $image;
        $userRequest['password'] = Hash::make($request['password']) ?? $data['password'];

        $data->update($userRequest);

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.users.index')));
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
    $data = User::find($id);
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
    $data = User::where('fullname', 'LIKE', '%'.$request->q.'%')
      ->orderBy('fullname')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, fullname as text')
      ->get();

    $count = User::where('fullname', 'LIKE', '%'.$request->q.'%')
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
