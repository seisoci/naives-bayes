@extends('layouts.master')

@section('content')
    <div class="card">
      <div class="card-header justify-content-between">
        <div class="header-title">
          <div class="row">
            <div class="col-sm-6 col-lg-6">
              <h4 class="card-title">{{ $config['title'] ?? '' }}</h4>
            </div>
            <div class="col-sm-12">
              <div class="d-flex justify-content-end">
                <a href="{{ route('panel.normalization.show', 'detail') }}" class="btn btn-warning me-2"><i class="fa-solid fa-link"></i> Konfigurasi Kolom
                </a>
                <a href="{{ route('panel.normalization.export-template') }}" class="btn btn-primary me-2">
                  <i class="fa-solid fa-download"></i> Download Template
                </a>
                <a href="#" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalImport">
                  <i class="fa-solid fa-file-excel"></i> Import Template
                </a>
                <a href="{{ route('panel.normalization.create') }}" class="btn btn-primary">
                  <i class="fa-solid fa-plus"></i> Tambah
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="dt" class="table table-bordered w-100">
            <thead>
            <tr>
              <th>Type</th>
              <th>Range</th>
              <th>Nilai</th>
              <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <div id="modalImport" class="modal fade" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form id="formStore" class="modal-content" method="POST" action="{{ route('panel.normalization.import') }}">
          @method('POST')
          @csrf
          <div class="modal-header p-2">
            <h5 class="modal-title"><strong>Import</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-2">
            <div class="alert alert-primary mb-3"><strong>Peringatan !</strong><br>Proses import yang salah akan menyebabkan tidak muncul pada laporan.</div>
            <div class="form-group">
              <input id="file" type="file" class="form-control" name="file">
              <div class="invalid-feedback"></div>
            </div>

          </div>
          <div class="modal-footer p-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
            <button type="submit" class="btn btn-sm btn-primary">Import</button>
          </div>
        </form>
      </div>
    </div>
@endsection

@push('style')
  <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.css" rel="stylesheet">
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.js"></script>
  <script>
    $(document).ready(function () {
      let DataTable = $('#dt').DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
          url: `{{ url()->current() }}`
        },
        columns: [
          {data: 'type', name: 'type', width: '250px'},
          {
            data: 'range_name',
            name: 'range_name',
            searchable: false,
            width: '50px'
          },
          {
            data: 'value',
            name: 'value',
            width: '50px'
          },
          {
            data: 'action',
            name: 'action',
            width: '25px',
            className: "text-center",
            orderable: false,
            searchable: false
          },
        ],
        rowCallback : function(row, data) {
          let api = this.api();
          $(row).find('.btn-delete').click(function(){
            let pk = $(this).data('id'),
              url = `{{ url()->current() }}/` + pk;
            Swal.fire({
              title   : "Anda Yakin ?",
              text    : "Data tidak dapat dikembalikan setelah di hapus!",
              icon    : "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Ya, Hapus!",
              cancelButtonText: "Tidak, Batalkan",
            }).then((result) => {
              if (result.value) {
                $.ajax({
                  url: url,
                  type: "DELETE",
                  data: {
                    _token  : '{{ csrf_token() }}',
                    _method : 'DELETE'
                  },
                  error   : function(response){
                    toastr.error(response, 'Failed !');
                  },
                  success : function(response) {
                    if (response.status === "success") {
                      toastr.success(response.message, 'Success !');
                      api.draw();
                    } else {
                      toastr.error((response.message ? response.message : "Please complete your form"), 'Failed !');
                    }
                  }
                });
              }
            });
          });
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
              let modal = bootstrap.Modal.getInstance($('#modalImport').get(0));
              modal.hide();
              DataTable.draw();
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
