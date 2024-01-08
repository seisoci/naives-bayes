@extends('layouts.master')

@section('content')
  <div>
    <form id="formStore" action="{{ $config['form']->action }}" method="POST">
      @method($config['form']->method)
      @csrf
      <div class="row">
        <div class="col-sm-12 col-lg-8">
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
                  <label class="control-label col-sm-3 align-self-center mb-0" for="title">Judul :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="title" name="title" value="{{ $data->title ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="description">Deskripsi :</label>
                  <div class="col-sm-9">
                    <textarea name="description" id="description" cols="30" rows="10"
                              class="form-control">{{ $data->description ?? '' }}</textarea>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="selectType">Jenis Naskah :</label>
                  <div class="col-sm-9">
                    <select id="selectType" name="type" class="form-select">
                      <option value="Single" @selected(isset($data) && $data->type == 'Single')>Single</option>
                      <option value="Multiple" @selected(isset($data) && $data->type == 'Multiple')>Multi Choice</option>
                      <option value="Essay" @selected(isset($data) && $data->type == 'Essay')>Essay</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="selectType">Pendahuluan :</label>
                  <div class="col-sm-9">
                    <select id="selectExtend" name="extended" class="form-select">
                      <option value="N" @selected(isset($data['introduction']))>Tidak</option>
                      <option value="Y" @selected(isset($data['introduction']))>Ya</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3 {{ !isset($data['introduction']) ? 'd-none' : '' }}">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="e_duration">Waktu CountDownWaktu CountDown (Detik) :</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="e_duration" name="introduction[duration]" value="{{ $data->introduction->duration ?? '' }}">
                  </div>
                </div>
                <div class="form-group row mb-3 {{ !isset($data['introduction']) ? 'd-none' : '' }}">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="e_description">Deskripsi Pendahuluan :</label>
                  <div class="col-sm-9">
                    <textarea name="introduction[description]" id="e_description" cols="30" rows="10"
                              class="form-control">{{ $data->introduction->description ?? '' }}</textarea>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#select2Active').select2({
        width: '100%'
      });

      $('#selectExtend').on('change', function () {
        let val = $(this).val();
        if(val === 'Y'){
          $('#e_duration, #e_description').parent().parent().removeClass('d-none');
        }else{
          $('#e_duration, #e_description').parent().parent().addClass('d-none');
        }
      });

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

      $('#description, #e_description').summernote({
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['fontsize',['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video', 'math']],
          ['view', ['fullscreen', 'codeview', 'help']],
        ]
      });

    });
  </script>
@endpush
