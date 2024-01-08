@extends('layouts.master')

@section('content')
  <div class="container-fluid">
    <div class="d-flex mb-2 align-items-center">
      <h4 class="m-0 text-uppercase fw-bold">Ujian</h4>
      <div class="btn-toolbar ms-auto">
        <div class="btn-group btn-group-sm me-2">
          <a href="{{ route('panel.examinations.index') }}" class="btn btn-outline-secondary">Daftar Ujian</a>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        @include('panel.examinations.parts.info')
      </div>
      <div class="card-header">
        @include('panel.examinations.parts.navs')
      </div>
      <div class="card-body">
        <form id="formStore" action="{{ $config['form']->action }}" method="POST">
          @method($config['form']->method)
          @csrf
          <div class="d-flex mb-2 align-items-center">
            <h6 class="m-0 text-uppercase fw-bold">Tiket</h6>
            <div class="btn-toolbar ms-auto">
              <div class="btn-group btn-group-sm me-2">
                <a href="{{ route('panel.examinations.tickets.index',['examination'=>$examination]) }}"
                   class="btn btn-outline-secondary">Batal</a>
              </div>
              <div class="btn-group btn-group-sm">
                <button type="submit" class="btn btn-primary">Generate</button>
              </div>
            </div>
          </div>
          <div id="errorCreate" class="mb-3" style="display:none;">
            <div class="alert alert-danger" role="alert">
              <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
              <div class="alert-text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-2">
              <div class="form-group mb-3">
                <label for="qty" class="mb-1">Jumlah Tiket</label>
                <input id="qty" type="text" class="form-control" name="qty">
                <div class="invalid-feedback"></div>
              </div>
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
  <script>
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
  </script>
@endpush
