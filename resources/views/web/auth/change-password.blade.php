@extends('layouts.web.master')

@section('content')
<div class="container py-3">
  <div class="row justify-content-center">
    <div class="col-md-6 col-12">
      <h1 class="fw-bolder fs-2 text-uppercase text-center my-3">Profile</h1>
      <form id="formStore" action="{{ route('change-password.store') }}" method="POST">
        @csrf
        <div class="card">
          <div class="card-body">
            @if(session('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
            <div class="row">
              <div class="col-md-12">
                <div class="mb-3">
                  <label class="form-label" for="current_password">Password Lama</label>
                  <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control pe-5 password-input" id="current_password" name="current_password">
                    <button
                      class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                      type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                    @error('current_password')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="new_password">Password Baru</label>
                  <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control pe-5 password-input" id="new_password" name="new_password">
                    <button
                      class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                      type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                    @error('new_password')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label" for="password_confirmation">Ulangi Password Baru</label>
                  <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control pe-5 password-input" id="password_confirmation" name="password_confirmation">
                    <button
                      class="btn btn-link position-absolute end-0 top-0 text-decoration-none shadow-none text-muted password-addon"
                      type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                    @error('password_confirmation')
                    <span class="invalid-feedback d-block" role="alert">
                         <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary float-end"><i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('style')
@endpush

@push('script')
  <script src="{{ asset('assets/js/pages/password-addon.init.js') }}"></script>
@endpush
