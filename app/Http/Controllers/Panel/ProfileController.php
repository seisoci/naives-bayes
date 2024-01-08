<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Puskesmas;
use App\Models\Admin;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use ResponseStatus;

  public function index(Request $request)
  {
    $config['title'] = "Ubah Profile";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Ubah Profile"],
    ];

    $data = Admin::with('roles')->find(auth()->id());

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.profile.store')
    ];
    return view('panel.profile.index', compact('config', 'data'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'poster' => 'image|mimes:jpg,png,jpeg|max:5000',
      'name' => 'required',
      'email' => 'required|email|unique:admins,email,' . $request['email'] . ',email',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $image = NULL;
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $data = Admin::findOrFail(auth()->id());

        if (isset($request['image']) && !empty($request['image'])) {
          $image = FileUpload::uploadImage('image', $dimensions, 'storage', $data['image']);
        }
        $data->update([
          'name' => ucwords($request['name']),
          'email' => $request['email'],
          'image' => $image,
        ]);

        DB::commit();
        $response = response()->json($this->responseStore(true));
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
}
