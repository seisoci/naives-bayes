<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:settings-list', ['only' => ['index', 'show']]);
    $this->middleware('can:settings-create', ['only' => ['create', 'store']]);
    $this->middleware('can:settings-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:settings-delete', ['only' => ['destroy']]);
  }

  public function general()
  {
    $data = json_decode(Storage::get('public/app.json'), TRUE);
    return view('panel.settings.form', compact('data'));
  }

  public function general_update(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'favicon' => 'required|file',
      'logo' => 'required|file',
//      'logodark' => 'required|file',
      'title' => 'required',
      'description' => 'required'
    ]);


    try {
      $input = $validator->safe();
      if ($request->file('favicon')->isValid()) {
        $extension = $request->favicon->getClientOriginalExtension();
        $input['favicon'] = $request->favicon->storeAs('base', 'favicon.' . $extension, 'public');
      }

      if ($request->file('logo')->isValid()) {
        $extension = $request->logo->getClientOriginalExtension();
        $input['logo'] = $request->logo->storeAs('base', 'logo.' . $extension, 'public');
      }

//      if ($request->file('logodark')->isValid()) {
//        $extension = $request->logodark->getClientOriginalExtension();
//        $input['logo'] = $request->logodark->storeAs('base', 'logo-dark.' . $extension, 'public');
//      }

      $data = array_merge(json_decode(Storage::get('public/app.json'), TRUE), $input->toArray());
      Storage::put('public/app.json', json_encode($data));

      $response = response()->json($this->responseUpdate(true));
    } catch (\Throwable $throw) {
      DB::rollBack();
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }
}
