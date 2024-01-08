<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

trait ValidateLogin
{

  protected function sendFailedLoginResponse(Request $request)
  {
    throw ValidationException::withMessages([
      'login' => [trans('auth.failed')],
    ]);
  }

  protected function guard()
  {
    return Auth::guard();
  }

  protected function validateLogin(Request $request)
  {
    $request->validate([
      'email' => 'required|string',
      'password' => 'required|string',
    ]);
  }
}
