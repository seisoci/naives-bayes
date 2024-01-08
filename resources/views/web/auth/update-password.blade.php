<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable">

<head>

  <meta charset="utf-8"/>
  <title>{{ config('app.name') ?? '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('storage') }}/{{ $settings->favicon ?? '' }}">
  @include('layouts.style')

</head>

<body>

<div class="auth-page-wrapper pt-5">
  <!-- auth page bg -->
  <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
    <div class="bg-overlay"></div>
  </div>

  <!-- auth page content -->
  <div class="auth-page-content">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="text-center mt-sm-5 mb-4 text-white-50">
            <p class="mt-3 fs-15 fw-medium"></p>
          </div>
        </div>
      </div>
      <!-- end row -->

      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
          <div class="card mt-4">

            <div class="card-body p-4">
              <div class="text-center mt-2">
                <h5 class="text-primary">{{ config('app.name') ?? '-' }}</h5>
              </div>
              <div class="p-2 mt-4">
                <x-auth-session-status class="mb-4" :status="session('status')"/>

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors"/>
                <form method="POST" action="{{ route('update-password.update') }}">
                  @csrf
                  <!-- Password Reset Token -->
                  <input type="hidden" name="token" value="{{ $request['token'] }}">
                  <input type="hidden" name="email" value="{{ $request['email'] }}">
                  <!-- Password -->
                  <div class="mb-3">
                    <label for="password">{{__('Password')}}</label>
                    <input id="password" class="form-control" type="password" name="password" required/>
                  </div>
                  <!-- Confirm Password -->
                  <div class="mb-3">
                    <label for="password_confirmation">{{__('Ulangi Password')}}</label>

                    <input id="password_confirmation" class="form-control"
                           type="password"
                           name="password_confirmation" required/>
                  </div>
                  <button class="btn btn-primary w-100 mt-4">
                    {{ __('Reset Password') }}
                  </button>

                </form>
              </div>
            </div>
            <!-- end card body -->
          </div>
          <!-- end card -->

        </div>
      </div>
    </div>
  </div>

</div>

<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>
<script src="{{ asset('assets/libs/particles.js/particles.js') }}"></script>
<script src="{{ asset('assets/js/pages/particles.app.js') }}"></script>
<script src="{{ asset('assets/js/pages/password-addon.init.js') }}"></script>

</body>

</html>
