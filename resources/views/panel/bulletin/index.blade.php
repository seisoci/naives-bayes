@extends('layouts.master')

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <div id="errorCreate" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-text">
              </div>
            </div>
          </div>
          <form id="formStore" method="POST" action="{{ route('panel.bulletin.store') }}" enctype="multipart/form-data">
            @method('POST')
            @csrf
            <div class="d-flex mb-2 align-items-center mt-4">
              <h6 class="m-0 text-uppercase fw-bold">Pemberitahuan</h6>
              <div class="btn-toolbar ms-auto">
                <div class="btn-group btn-group-sm">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </div>
            </div>
            <div class="mt-3">
            <textarea id="announcement" class="form-control" name="bulletin[announcement]">
             {!! $data['announcement']['description'] ?? '' !!}
          </textarea>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css"/>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
  <script>
    $(document).ready(function () {

      $('#announcement').summernote({
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video', 'math']],
          ['view', ['fullscreen', 'codeview', 'help']],
        ]
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

    });
  </script>
@endpush
