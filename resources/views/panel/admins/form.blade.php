@extends('layouts.master')

@section('content')
  <div>
    <form id="formStore" action="{{ $config['form']->action }}" method="POST">
      @method($config['form']->method)
      @csrf
      <div class="row">
        <div class="col-sm-12 col-lg-6">
          <div class="card">
            <div class="card-header justify-content-between">
              <div class="header-title">
                <div class="row">
                  <div class="col-sm-6 col-lg-6">
                    <h4 class="card-title">{{ $config['title'] }}</h4>
                  </div>
                  <div class="col-sm-6 col-lg-6">
                    <div class="btn-group float-end" role="group" aria-label="Basic outlined example">
                      <a onclick="history.back()" class="btn btn-sm btn-outline-primary"><i
                          class="fa-solid fa-rotate-left"></i> Kembali</a>
                      <button type="submit" class="btn btn-sm btn-primary">Simpan <i
                          class="fa-solid fa-floppy-disk"></i></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <div id="errorCreate" class="mb-3" style="display:none;">
                  <div class="alert alert-danger" role="alert">
                    <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                    <div class="alert-text">
                    </div>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="name">Nama :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama Anda"
                           value="{{ $data->name ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="email">Email :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Masukan Email"
                           value="{{ $data->email ?? '' }}" {{ (isset($data) ? 'readonly' : '') }}>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="select2Role">Role :</label>
                  <div class="col-sm-9">
                    <select id="select2Role" style="width: 100% !important;" name="role_id">
                      @if(isset($data->role_id))
                        <option value="{{ $data->role_id }}">{{ $data->roles->name }}</option>
                      @endif
                    </select>
                  </div>
                </div>
                <div style="{{ isset($data) ? 'display:none' : '' }}">
                  <div class="form-group row mb-3">
                    <label class="control-label col-sm-3 align-self-center mb-0" for="password">Password :</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input type="password" class="form-control" placeholder="Enter Your password" name="password">
                        <button class="btn btn-light password-toggle" type="button">
                          <svg class="svg-inline--fa fa-eye fa-w-18" aria-hidden="true" focusable="false"
                               data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg"
                               viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                  d="M572.5 238.1C518.3 115.5 410.9 32 288 32S57.69 115.6 3.469 238.1C1.563 243.4 0 251 0 256c0 4.977 1.562 12.6 3.469 17.03C57.72 396.5 165.1 480 288 480s230.3-83.58 284.5-206.1C574.4 268.6 576 260.1 576 256C576 251 574.4 243.4 572.5 238.1zM432 256c0 79.45-64.47 144-143.9 144C208.6 400 144 335.5 144 256S208.5 112 288 112S432 176.5 432 256zM288 160C285.7 160 282.4 160.4 279.5 160.8C284.8 170 288 180.6 288 192c0 35.35-28.65 64-64 64C212.6 256 201.1 252.7 192.7 247.5C192.4 250.5 192 253.6 192 256c0 52.1 43 96 96 96s96-42.99 96-95.99S340.1 160 288 160z"></path>
                          </svg><!-- <i class="fa fa-eye"></i> --></button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group row mb-3">
                    <label class="control-label col-sm-3 align-self-center mb-0" for="confirm_password">Konfirmasi
                      Password :</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input type="password" class="form-control" placeholder="Enter Your Konfirmasi Password"
                               name="password_confirmation">
                        <button class="btn btn-light password-toggle" type="button">
                          <svg class="svg-inline--fa fa-eye fa-w-18" aria-hidden="true" focusable="false"
                               data-prefix="fas" data-icon="eye" role="img" xmlns="http://www.w3.org/2000/svg"
                               viewBox="0 0 576 512" data-fa-i2svg="">
                            <path fill="currentColor"
                                  d="M572.5 238.1C518.3 115.5 410.9 32 288 32S57.69 115.6 3.469 238.1C1.563 243.4 0 251 0 256c0 4.977 1.562 12.6 3.469 17.03C57.72 396.5 165.1 480 288 480s230.3-83.58 284.5-206.1C574.4 268.6 576 260.1 576 256C576 251 574.4 243.4 572.5 238.1zM432 256c0 79.45-64.47 144-143.9 144C208.6 400 144 335.5 144 256S208.5 112 288 112S432 176.5 432 256zM288 160C285.7 160 282.4 160.4 279.5 160.8C284.8 170 288 180.6 288 192c0 35.35-28.65 64-64 64C212.6 256 201.1 252.7 192.7 247.5C192.4 250.5 192 253.6 192 256c0 52.1 43 96 96 96s96-42.99 96-95.99S340.1 160 288 160z"></path>
                          </svg><!-- <i class="fa fa-eye"></i> --></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="active">Status :</label>
                  <div class="col-sm-9">
                    <select id="select2Active" name="active" placeholder="Status User ?">
                      <option value="1" {{ isset($data->active) && $data->active == 1 ? 'selected' : '' }}>Aktif
                      </option>
                      <option value="0" {{ isset($data->active) &&  $data->active != 1 ? 'selected' : '' }}>Tidak
                        Aktif
                      </option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="phone">Alamat :</label>
                  <div class="col-sm-9">
                    <textarea name="address" id="address" cols="30" rows="5"
                              class="form-control">{{ $data->address ?? '' }}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-lg-4 text-center">
          <div class="card">
            <div class="card-body">
              <label class="mb-2 text-bold d-block">Foto</label>
              <img id="avatar"
                   @if(isset($data['image']))
                     src="{{ $data['image'] != NULL ? asset("storage/images/original/".$data['image']) : asset('assets/images/no-content.svg') }}"
                   @else
                     src="{{ asset('assets/images/no-content.svg') }}"
                   @endif
                   style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto" height="200px"
                   width="200px" alt="">
              <input class="form-control image" type="file" id="customFile1" name="image" accept=".jpg, .jpeg, .png">
              <p class="text-muted ms-75 mt-50"><small>Allowed JPG, JPEG or PNG. Max
                  size of
                  2MB</small></p>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('style')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#select2Active').select2({
        width: '100%'
      });

      $('#select2Role').select2({
        dropdownParent: $('#select2Role').parent(),
        placeholder: "Cari Role",
        allowClear: true,
        ajax: {
          url: "{{ route('panel.roles.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      $("#formStore").submit(function (e) {
        e.preventDefault();
        let form = $(this);
        let btnSubmit = form.find("[type='submit']");
        let btnSubmitHtml = btnSubmit.html();
        let url = form.attr("action");
        let data = new FormData(this);
        $.ajax({
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          beforeSend: function () {
            btnSubmit.addClass("disabled").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop("disabled", "disabled");
          },
          success: function (response) {
            let errorCreate = $('#errorCreate');
            errorCreate.css('display', 'none');
            errorCreate.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              setTimeout(function () {
                if (response.redirect === "" || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
            } else {
              toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
              if (response.error !== undefined) {
                errorCreate.removeAttr('style');
                $.each(response.error, function (key, value) {
                  errorCreate.find('.alert-text').append('<span style="display: block">' + value + '</span>');
                });
              }
            }
          },
          error: function (response) {
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");
            toastr.error(response.responseJSON.message, 'Failed !');
          }
        });
      });

      $(".image").change(function () {
        let thumb = $(this).parent().find('img');
        if (this.files && this.files[0]) {
          let reader = new FileReader();
          reader.onload = function (e) {
            thumb.attr('src', e.target.result);
          }
          reader.readAsDataURL(this.files[0]);
        }
      });
    });
  </script>
@endpush
