<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable">

<head>

  <meta charset="utf-8"/>
  <title>{{ config('app.name') ?? '' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('storage') }}/{{ $settings->favicon ?? '' }}">
  @include('layouts.style')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet"/>
  <style>
    .flatpickr-months {
      background-color: #000000 !important;
    }
    .flatpickr-month{
      color: rgb(255 255 255) !important;
    }

    .flatpickr-months .flatpickr-prev-month, .flatpickr-months .flatpickr-next-month {
      color:rgb(255 255 255) !important;
      fill:rgb(255 255 255) !important;
    }
  </style>
</head>

<body>

<div class="auth-page-wrapper pt-5">
  <!-- auth page bg -->
  <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
    <div class="bg-overlay"></div>

{{--    <div class="shape">--}}
{{--      <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1440 120">--}}
{{--        <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>--}}
{{--      </svg>--}}
{{--    </div>--}}
  </div>

  <!-- auth page content -->
  <div class="auth-page-content">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6 col-xl-5">
          <div class="card mt-4">
            <div class="card-body p-4">
              <div class="text-center mt-2">
                <p class="fs-15 fw-medium">{{ config('app.name') ?? '' }}</p>
                <h5 class="text-primary">Buat Akun Baru</h5>
              </div>
              <div class="p-2 mt-4">
                <form class="login-form mt-4" action="{{ route('register-store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fullname" id="fullname" value="{{ old('fullname') }}">
                    @error('fullname')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="password-input">Password</label>
                    <div class="position-relative auth-pass-inputgroup">
                      <input type="password" class="form-control pe-5 password-input" id="password-input" name="password"
                             aria-describedby="passwordInput">
                      <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                      @error('password')
                      <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label" for="password-input">Ulangi Password</label>
                    <div class="position-relative auth-pass-inputgroup">
                      <input type="password" class="form-control pe-5 password-input" id="password-input" name="password_confirmation"
                             aria-describedby="passwordInput">
                      <button
                        class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                        type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                    </div>
                    @error('password_confirmation')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="mb-3">
                    <label for="birthday" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control flatpickr" name="birthday" id="birthday" value="{{ old('birthday') }}">
                    @error('birthday')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label for="sex" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="sex" id="sex" class="form-select">
                      <option value="Male" {{ old('birthday') === 'Male' ? 'selected' : '' }}>Laki-laki</option>
                      <option value="Female" {{ old('birthday') === 'Female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('sex')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="mb-4">
                    <p class="mb-0 fs-12 text-muted fst-italic">Dengan mendaftar, Anda menyetujui Ketentuan Penggunaan
                      {{ config('app.name') }} <a href="#" class="text-primary text-decoration-underline fst-normal fw-medium">Terms of
                        Use</a></p>
                  </div>

                  <div id="password-contain" class="p-3 bg-light mb-2 rounded">
                    <h5 class="fs-13">Password must contain:</h5>
                    <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
                    <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
                    <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
                    <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
                  </div>

                  <div class="mt-4">
                    <button class="btn btn-success w-100" type="submit">Daftar</button>
                  </div>

                </form>

              </div>
            </div>
            <div class="text-center mb-5">
              <p class="mb-0">Sudah Punya Akun ? <a href="{{ route('login') }}"
                                                    class="fw-semibold text-primary text-decoration-underline"> Masuk </a>
              </p>
            </div>
            <!-- end card body -->
          </div>
          <!-- end card -->

        </div>
      </div>
      <!-- end row -->
    </div>
    <!-- end container -->
  </div>
  <!-- end auth page content -->

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script>
  $(document).ready(function () {
    $(".flatpickr").flatpickr();
  });
</script>
</body>

</html>
