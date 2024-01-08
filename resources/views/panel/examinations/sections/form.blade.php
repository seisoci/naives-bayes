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
                    <h6 class="m-0 text-uppercase fw-bold">Sesi Naskah Soal</h6>
                    <div class="btn-toolbar ms-auto">
                        <div class="btn-group btn-group-sm me-2">
                            <a href="{{ route('panel.examinations.sections.index',['examination'=>$examination]) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
                  <div class="col-md-1">
                        <div class="form-group mb-3">
                            <label for="number" class="mb-1">No Urut</label>
                            <input id="number" type="text" class="form-control" name="number" value="{{ $section['number'] ?? '' }}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-5">
                            <label for="select2Script" class="mb-1">Naskah Soal</label>
                            <select id="select2Script" class="form-select" name="script_id">
                                <option value="{{ $section['script_id'] ?? '' }}" selected>{{ $section['script']['title'] ?? '' }}</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                            <label for="sorting_mode" class="mb-1">Penomoran Soal</label>
                            <select id="sorting_mode" class="form-select" name="sorting_mode">
                                <option value="Normal" @selected(isset($section['sorting_mode']) && $section['sorting_mode'] == 'Normal')>Normal</option>
                                <option value="Random" @selected(isset($section['sorting_mode']) && $section['sorting_mode'] == 'Random')>Acak</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                            <label for="control_mode" class="mb-1">Kontrol Soal</label>
                            <select id="control_mode" class="form-select" name="control_mode">
                                <option value="1" @selected(isset($section['control_mode']) && $section['control_mode'] == 1)>Ya</option>
                                <option value="0" @selected(isset($section['control_mode']) && $section['control_mode'] == 0)>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group mb-3">
                            <label for="auto_next" class="mb-1">Auto Next</label>
                            <select id="auto_next" class="form-select" name="auto_next">
                                <option value="0" @selected(isset($section['auto_next']) && $section['auto_next'] == 0)>Tidak</option>
                                <option value="1" @selected(isset($section['auto_next']) && $section['auto_next'] == 1)>Ya</option>
                            </select>
                        </div>
                    </div>
                        <div class="col-lg-1">
                            <div class="form-group mb-3">
                                <label for="duration" class="mb-1">Durasi</label>
                                <input id="duration" type="number" class="form-control" name="duration" value="{{ isset($section['duration']) ? $section['duration'] / 60  : 0 }}">
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
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $('#select2Script').select2({
      dropdownParent: $('#select2Script').parent(),
      placeholder: "Cari Naskah Soal",
      ajax: {
        url: "{{ route('panel.scripts.select2') }}",
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
