<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Rules\MatchOldPassword;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:admins-list', ['only' => ['index', 'show']]);
    $this->middleware('can:admins-create', ['only' => ['create', 'store']]);
    $this->middleware('can:admins-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:admins-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Admin";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Admin"],
    ];
    if ($request->ajax()) {
      $data = Admin::with('roles');

      if ($request->filled('role_id')) {
        $data->whereRoleId($request['role_id']);
      }

      return DataTables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
          $actionBtn = '
          <div class="dropdown d-inline-block">
              <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ri-more-fill align-middle"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="'.route('panel.admins.edit', $row->id).'">Edit</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalReset" data-bs-id="'.$row->id.'" class="dropdown-item">Reset Password</a></li>
                <li><a class="dropdown-item btn-delete" href="#" data-id ="'.$row->id.'" >Hapus</a></li>
              </ul>
          </div>';

          return $actionBtn;
        })->make();
    }
    return view('panel.admins.index', compact('config'));
  }

  public function create()
  {
    $config['title'] = "Tambah Admin";
    $config['breadcrumbs'] = [
      ['url' => route('panel.admins.index'), 'title' => "Role"],
      ['url' => '#', 'title' => "Tambah Admin"],
    ];
    $config['form'] = (object) [
      'method' => 'POST',
      'action' => route('panel.admins.store')
    ];
    return view('panel.admins.form', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'role_id' => 'required|integer',
      'name' => 'required',
      'password' => 'required|between:6,255|confirmed',
      'email' => 'required|unique:users,email|email',
      'active' => 'required|between:0,1',
      'image' => 'image|mimes:jpg,png,jpeg',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $img = isset($request->image) && !empty($request->image) ? FileUpload::uploadImage('image', $dimensions) : null;
        $data = Admin::create([
          'role_id' => $request['role_id'],
          'name' => ucwords($request['name']),
          'image' => $img,
          'email' => $request['email'],
          'password' => Hash::make($request['password']),
          'active' => $request['active'],
        ]);

        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.admins.index')));
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
    $config['title'] = "Edit Admin";
    $config['breadcrumbs'] = [
      ['url' => route('panel.admins.index'), 'title' => "Admin"],
      ['url' => '#', 'title' => "Edit Admin"],
    ];
    $data = Admin::with('roles')->where('id', $id)->first();
    $config['form'] = (object) [
      'method' => 'PUT',
      'action' => route('panel.admins.update', $id)
    ];
    return view('panel.admins.form', compact('config', 'data'));
  }

  public function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'role_id' => 'required|integer',
      'name' => 'required',
      'password' => 'between:6,255|confirmed|nullable',
      'email' => 'required|email|unique:users,email,'.$request['email'].',email',
      'active' => 'required|between:0,1',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $image = null;
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $data = Admin::findOrFail($id);
        if (isset($request['image']) && !empty($request['image'])) {
          $image = FileUpload::uploadImage('image', $dimensions, 'storage', $data['image']);
        }
        $data->update([
          'role_id' => $request['role_id'],
          'name' => ucwords($request['name']),
          'email' => $request['email'],
          'active' => $request['active'],
          'image' => $image,
        ]);
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.admins.index')));
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
    $data = Admin::find($id);
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

  public function resetpassword(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|integer',
    ]);

    if ($validator->passes()) {
      $data = Admin::find($request->id);
      $data->password = Hash::make($data['email']);
      if ($data->save()) {
        $response = response()->json($this->responseUpdate(true));;
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function changepassword()
  {
    $config['title'] = "Ganti Password";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Ganti Password"],
    ];
    $config['form'] = (object) [
      'method' => 'POST',
      'action' => route('update-change-password', auth()->id())
    ];
    return view('users.change-password', compact('config'));
  }

  public function updatechangepassword(Request $request)
  {
    $data = Auth::user();

    $validator = Validator::make($request->all(), [
      'old_password' => ['required', new MatchOldPassword(Auth::id())],
      'password' => 'required|between:6,255|confirmed',
    ]);

    if ($validator->passes()) {
      $data->password = Hash::make($request['password']);
      if ($data->save()) {
        $response = response()->json($this->responseUpdate(true, route('dashboard')));
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function select2(Request $request)
  {
    $page = $request->page;
    $resultCount = 10;
    $offset = ($page - 1) * $resultCount;
    $data = Admin::where('name', 'LIKE', '%'.$request->q.'%')
      ->orderBy('name')
      ->skip($offset)
      ->take($resultCount)
      ->selectRaw('id, name as text')
      ->get();

    $count = Admin::where('name', 'LIKE', '%'.$request->q.'%')
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
