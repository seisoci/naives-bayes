<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  use ThrottlesLogins;

  public function __construct()
  {
    $this->middleware('guest:web')->except('logout');
  }

  public function showLoginForm()
  {
    return view('web.auth.login');
  }

  public function register(Request $request)
  {
    return view('web.auth.register');
  }

  public function forgotPassword(Request $request)
  {
    return view('web.auth.forgot-password');
  }

  public function storeRegister(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'fullname' => 'required',
      'password' => 'required|between:6,255|confirmed',
      'email' => 'required|email|unique:users,email',
      'birthday' => 'required',
      'sex' => 'required',
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {

        $hashedPassword = Hash::make($request['password']);
        $data = $request->all();
        $data['password'] = $hashedPassword;
        $user = User::create($data);

        $user->save();
        $user->markEmailAsVerified();

        Auth::guard('web')->login($user);

        DB::commit();
        return redirect()->route('panel.dashboard.index');

      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
      }
    } else {
      $response = redirect()->back()->withErrors($validator->errors())->withInput();
    }
    return $response;
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

//    $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    $data = [
      'email' => $request['email'],
      'password' => $request['password']
    ];

    if (Auth::guard('web')->attempt($data, $remember)) {
      return redirect(RouteServiceProvider::USER_HOME);
    }

    $this->incrementLoginAttempts($request);
    $this->sendFailedLoginResponse($request);
    return redirect(RouteServiceProvider::USER_LOGIN_PANEL);
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
    $redirect = redirect(RouteServiceProvider::USER_LOGIN_PANEL);
    Auth::guard('web')->logout();
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

