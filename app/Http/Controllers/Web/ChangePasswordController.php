<?php

namespace App\Http\Controllers\Web;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
  public function index(Request $request)
  {

    $config['title'] = "Ubah Password";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Ubah Password"],
    ];

    return view('web.auth.change-password', compact('config'));
  }

  public function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'current_password' => ['required', new MatchOldPassword],
      'new_password' => ['required'],
      'password_confirmation' => ['same:new_password'],
    ]);
    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        DB::commit();
        $response = redirect()->back()->with('success', 'Password Berhasil Diubah');;
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = redirect()->back()->withErrors($throw->getMessage())->withInput();
      }
    } else {
      $response = redirect()->back()->withErrors($validator->errors())->withInput();
    }
    return $response;
  }

}
