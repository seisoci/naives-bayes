<?php

namespace App\Http\Controllers\Web;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Examination;
use App\Models\User;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
  use ResponseStatus;

  public function index(Request $request)
  {

    $config['title'] = "Profile";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Profile"],
    ];

    return view('web.auth.profile', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'picture' => 'image|mimes:jpg,png,jpeg|max:2000',
      'fullname' => 'required',
      'birthday' => 'required|date_format:Y-m-d',
      'sex' => 'required',
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      $image = NULL;
      $dimensions = [array('300', '300', 'thumbnail')];
      try {
        $data = User::findOrFail(auth()->id());
        if (isset($request['picture']) && !empty($request['picture'])) {
          $image = FileUpload::uploadImage('picture', $dimensions, 'storage', $data['picture']);
        }

        $userRequest = $request->all();
        $userRequest['fullname'] = ucwords($request['fullname']);
        $userRequest['picture'] = $image ?? $data['picture'];

        $data->update($userRequest);

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

}
