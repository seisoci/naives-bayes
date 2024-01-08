
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

  <meta charset="utf-8" />
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
                  <h4 class="text-primary fw-bolder pb-3">{{ $settings->title ?? '-' }}</h4>
                  <img src="{{ asset('storage') }}/{{ $settings->logo ?? '' }}" alt="" style="height: 80px">
              </div>
              <div class="p-2 mt-4">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
                <form method="POST" action="{{ route('panel.login') }}" data-toggle="validator" class="">
                  {{ csrf_field() }}
                  <div class="mb-3">
                    <label for="username" class="form-label">Email</label>
                    <input type="text" class="form-control"  name="email" value="{{ old('email') }}" placeholder="Enter email / username">
                  </div>

                  <div class="mb-3">
                   {{-- <div class="float-end">
                      <a href="auth-pass-reset-basic.html" class="text-muted">Lupa Password?</a>
                    </div>--}}
                    <label class="form-label" for="password-input">Password</label>
                    <div class="position-relative auth-pass-inputgroup mb-3">
                      <input type="password" class="form-control pe-5 password-input" placeholder="********"  name="password" required id="password-input">
                      <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                    </div>
                  </div>

                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                  </div>

                  <div class="mt-4">
                    <button class="btn btn-primary w-100" type="submit">Masuk</button>
                  </div>

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
