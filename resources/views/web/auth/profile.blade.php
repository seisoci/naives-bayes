@extends('layouts.web.master')

@section('content')
  <div class="container py-3">
    <div class="row justify-content-center">
      <div class="col-md-10 col-12-8">
        <h1 class="fw-bolder fs-2 text-uppercase text-center my-3">Profile</h1>
        <form id="formStore" action="{{ route('profile.store') }}" method="POST">
          @csrf
          <div class="card">
            <div class="card-body">
              <div id="errorCreate" style="display:none;">
                <div class="alert alert-danger" role="alert">
                  <div class="alert-text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label class="mb-2 text-bold d-block">Foto</label>
                    <img id="avatar"
                         @if(isset(auth()->user()->picture))
                           src="{{ auth()->user()->picture != NULL ? asset("storage/images/thumbnail/".auth()->user()->picture) : asset('assets/images/no-content.svg') }}"
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
                <div class="col-md-6"></div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="fullname" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fullname" id="fullname"
                           value="{{ auth()->user()->fullname }}">
                  </div>

                  <div class="mb-3">
                    <label for="birthday" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="text" class="form-control flatpickr flatpickr-input" name="birthday" id="birthday"
                           value="{{ auth()->user()->birthday }}" readonly="readonly">
                  </div>
                  <div class="mb-3">
                    <label for="sex" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="sex" id="sex" class="form-select">
                      <option value="Male" @selected(auth()->user()->sex == 'Male')>Laki-laki</option>
                      <option value="Female" @selected(auth()->user()->sex == 'Female')>Perempuan</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="education" class="form-label">Pendidikan</label>
                    <select name="education" id="education" class="form-select">
                      <option value="SD" @selected(auth()->user()->education == 'SD')>SD</option>
                      <option value="SMP" @selected(auth()->user()->education == 'SMP')>SMP</option>
                      <option value="SMA" @selected(auth()->user()->education == 'SMA')>SMA/SMK</option>
                      <option value="S1" @selected(auth()->user()->education == 'S1')>S1/D3</option>
                      <option value="S2" @selected(auth()->user()->education == 'S2')>S2</option>
                      <option value="S3" @selected(auth()->user()->education == 'S3')>S3</option>
                      <option value="Other" @selected(auth()->user()->education == 'Other')>Lainnya</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="hp" class="form-label">No HP</label>
                    <input type="text" class="form-control" name="hp" id="hp" value="{{ auth()->user()->hp }}">
                  </div>
                  <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea class="form-control" name="address" id="address">{{ auth()->user()->address }}</textarea>
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
  <script>
    $(document).ready(function () {
      $(".flatpickr").flatpickr();

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
