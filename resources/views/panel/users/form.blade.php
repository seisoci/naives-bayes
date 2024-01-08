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
                    <input type="text" class="form-control" id="name" name="fullname" value="{{ $data->fullname ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="email">Email :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="email" name="email" value="{{ $data->email ?? '' }}" {{ (isset($data) ? 'readonly' : '') }}>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="name">Tgl Lahir :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control flatpickr" id="name" name="birthday" value="{{ $data->birthday ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="gender">Jenis Kelamin :</label>
                  <div class="col-sm-9">
                    <select id="select2Gender" name="gender">
                      <option value="Male" @selected(isset($data->active) && $data->active == 'Male')>Laki-Laki
                      </option>
                      <option value="Female" @selected(isset($data->active) && $data->active == 'Female')>Perempuan
                      </option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="name">No HP :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="hp" name="hp" value="{{ $data->hp ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="education">Pendidikan :</label>
                  <div class="col-sm-9">
                    <select name="education" id="education" class="form-select">
                      <option value="SD" @selected(isset($data->education) && $data->education == 'SD')>SD</option>
                      <option value="SMP" @selected(isset($data->education) && $data->education == 'SMP')>SMP</option>
                      <option value="SMA" @selected(isset($data->education) && $data->education == 'SMA')>SMA/SMK</option>
                      <option value="S1" @selected(isset($data->education) && $data->education == 'S1')>S1/D3</option>
                      <option value="S2" @selected(isset($data->education) && $data->education == 'S2')>S2</option>
                      <option value="S3" @selected(isset($data->education) && $data->education == 'S3')>S3</option>
                      <option value="Other" @selected(isset($data->education) && $data->education == 'Other')>Lainnya</option>
                    </select>
                  </div>
                </div>
                <div>
                  <div class="form-group row mb-3">
                    <label class="control-label col-sm-3 align-self-center mb-0" for="password">Password :</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input type="password" class="form-control" name="password">
                      </div>
                    </div>
                  </div>
                  <div class="form-group row mb-3">
                    <label class="control-label col-sm-3 align-self-center mb-0" for="confirm_password">Konfirmasi
                      Password :</label>
                    <div class="col-sm-9">
                      <div class="input-group">
                        <input type="password" class="form-control" name="password_confirmation">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-top mb-0" for="address">Alamat :</label>
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
                   @if(isset($data['picture']))
                     src="{{ $data['picture'] != NULL ? asset("storage/images/original/".$data['picture']) : asset('assets/images/no-content.svg') }}"
                   @else
                     src="{{ asset('assets/images/no-content.svg') }}"
                   @endif
                   style="object-fit: cover; border: 1px solid #d9d9d9" class="mb-2 border-2 mx-auto" height="200px"
                   width="200px" alt="">
              <input class="form-control image" type="file" id="customFile1" name="picture" accept=".jpg, .jpeg, .png">
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".flatpickr").flatpickr();

      $('#select2Gender').select2({
        'width': '100%'
      })

      $("#formStore").submit((e) => {
        e.preventDefault();
        const form = $(e.target);
        const btnSubmit = form.find("[type='submit']");
        const btnSubmitHtml = btnSubmit.html();
        const url = form.attr("action");
        const data = new FormData(e.target);

        $.ajax({
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          beforeSend: () => {
            btnSubmit.addClass("disabled").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...').prop("disabled", "disabled");
          },
          success: (response) => {
            const errorCreate = $('#errorCreate');
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
                $.each(response.error, (key, value) => {
                  errorCreate.find('.alert-text').append(`<span style="display: block">${value}</span>`);
                });
              }
            }
          },
          error: (response) => {
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
