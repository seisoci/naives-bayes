@extends('layouts.master')

@section('content')
    <div class="card">
      <div class="card-header justify-content-between">
        <div class="header-title">
          <div class="row">
            <div class="col-sm-6 col-lg-6">
              <h4 class="card-title">{{ $config['title'] ?? '' }}</h4>
            </div>
            <div class="col-sm-6 col-lg-6">
              <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#modalCreate">
                <i class="fa-solid fa-plus"></i> Tambah
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTable" class="table table-bordered w-100">
            <thead>
            <tr>
              <th>Name</th>
              <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Tambah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="formStore" method="POST" action="{{ route('panel.categories.store') }}">
            @csrf
            <div class="modal-body">
              <div id="errorCreate" style="display:none;">
                <div class="alert alert-danger" role="alert">
                  <div class="alert-text">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalmodalEdit" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ubah</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="formUpdate" action="#">
            @method('PUT')
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <div class="modal-body">
              <div id="errorEdit" style="display:none;">
                <div class="alert alert-danger" role="alert">
                  <div class="alert-text">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control"/>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
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
      let modalCreate = document.getElementById('modalCreate');
      const bsCreate = new bootstrap.Modal(modalCreate);
      let modalEdit = document.getElementById('modalEdit');
      const bsEdit = new bootstrap.Modal(modalEdit);

      let dataTable = $('#dataTable').DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
          url: `{{ url()->current() }}`
        },
        columns: [
          {
            data: 'name',
            name: 'name'
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

      modalCreate.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
      });

      modalEdit.addEventListener('show.bs.modal', function (event) {
        let name = event.relatedTarget.getAttribute('data-bs-name');
        this.querySelector('input[name=name]').value = name;
        this.querySelector('#formUpdate').setAttribute('action', '{{ url()->current() }}/' + event.relatedTarget.getAttribute('data-bs-id'));
      });

      modalEdit.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=name]').value = '';
        this.querySelector('#formUpdate').setAttribute('href', '');
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
              bsCreate.hide();
              dataTable.draw();
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

      $("#formUpdate").submit((e) => {
        e.preventDefault();
        const form = $(e.target);
        const btnSubmit = form.find("[type='submit']");
        const btnSubmitHtml = btnSubmit.html();
        const url = form.attr("action");
        const data = new FormData(e.target);

        $.ajax({
          beforeSend: () => {
            btnSubmit.addClass("disabled").html("<i class='spinner-border spinner-border-sm font-size-16 align-middle me-2'></i> Loading ...").prop("disabled", "disabled");
          },
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          cache: false,
          processData: false,
          contentType: false,
          type: "POST",
          url: url,
          data: data,
          success: (response) => {
            const errorEdit = $('#errorEdit');
            errorEdit.css('display', 'none');
            errorEdit.find('.alert-text').html('');
            btnSubmit.removeClass("disabled").html(btnSubmitHtml).removeAttr("disabled");

            if (response.status === "success") {
              toastr.success(response.message, 'Success !');
              bsEdit.hide();
              dataTable.draw();
            } else {
              toastr.error((response.message ? response.message : "Gagal mengubah data"), 'Failed !');
              if (response.error !== undefined) {
                errorEdit.removeAttr('style');
                $.each(response.error, (key, value) => {
                  errorEdit.find('.alert-text').append(`<span style="display: block">${value}</span>`);
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
