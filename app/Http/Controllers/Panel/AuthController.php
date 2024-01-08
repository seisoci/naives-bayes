<?php

namespace App\Http\Controllers\Panel;

use App\Providers\RouteServiceProvider;
use App\Traits\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  use ThrottlesLogins;

  public function __construct()
  {
    $this->middleware('guest:panel')->except('logout');
  }

  public function showLoginForm()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $this->validateLogin($request);
    if (method_exists($this, 'hasTooManyLoginAttempts') &&
      $this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);
      $this->sendLockoutResponse($request);
    }

    $remember = $request->has('remember');

    $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $data = [
      $fieldType => $request['email'],
      'password' => $request['password']
    ];

    if (Auth::guard('panel')->attempt($data, $remember)) {
      return redirect(RouteServiceProvider::HOME);
    }

    $this->incrementLoginAttempts($request);
    $this->sendFailedLoginResponse($request);
    return redirect(RouteServiceProvider::LOGIN_PANEL);
  }

  protected function validateLogin(Request $request)
  {
    $request->validate([
      'email' => 'required|string',
      'password' => 'required|string',
    ]);
  }

  protected function guard()
  {
    return Auth::guard();
  }

  protected function sendFailedLoginResponse(Request $request)
  {
    throw ValidationException::withMessages([
      'email' => [trans('auth.failed')],
    ]);
  }

  public function logout(Request $request)
  {
    $redirect = redirect(RouteServiceProvider::LOGIN_PANEL);
    Auth::guard('panel')->logout();
    return $redirect;
  }

  public function redirectPath()
  {
    if (method_exists($this, 'redirectTo')) {
      return $this->redirectTo();
    }

    return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
  }
}

