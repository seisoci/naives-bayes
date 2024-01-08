@extends('layouts.master')

@section('content')
  <div class="card">
    <div class="card-header justify-content-between">
      <div class="header-title">
        <div class="row">
          <div class="col-sm-6 col-lg-6">
            <h4 class="card-title">Data User </h4>
          </div>
          <div class="col-sm-6 col-lg-6">
            <a href="{{ route('panel.admins.create') }}" class="btn btn-primary float-end">
              <i class="fa-solid fa-plus"></i> Tambah
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <select id="select2Role" name="role_id">
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="dataTable" class="table table-bordered w-100">
          <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aktif</th>
            <th data-priority="1">Aksi</th>
          </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  {{--Modal--}}
  <div class="modal fade" id="modalReset" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalResetLabel">Reset Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="formReset" method="POST" action="{{ route('panel.admins.reset-password-users') }}">
          <div class="modal-body">
            @csrf
            <input type="hidden" name="id"></a>
            Anda yakin ingin reset password data ini? <br> (password sama dengan email)
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('style')
  <link
    href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.css"
    rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      let modalReset = document.getElementById('modalReset');
      const bsReset = new bootstrap.Modal(modalReset);

      modalReset.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id');
        this.querySelector('input[name=id]').value = id;
      });

      modalReset.addEventListener('hidden.bs.modal', function (event) {
        this.querySelector('input[name=id]').value = '';
      });

      let DataTable = $('#dataTable').DataTable({
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: {
          url: `{{ url()->current() }}`,
          data: function (d) {
            d.role_id = $('#select2Role').find(':selected').val();
          }
        },
        order: [[1, 'asc']],
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        pageLength: 10,
        columns: [
          {
            data: 'image',
            name: 'image',
            render: function (data, type, full, meta) {
              if (data !== null) {
                return `<img src="/storage/images/thumbnail/${data}" style="max-width:75px; max-height: 75px;">`
              }
              return `<img src="/assets/images/no-content.svg" style="max-width:75px; max-height: 75px;">`
            },
          },
          {data: 'name', name: 'name'},
          {data: 'username', name: 'username'},
          {data: 'email', name: 'email'},
          {data: 'roles.name', name: 'roles.name'},
          {
            data: 'active',
            name: 'active',
            className: 'text-center',
            render: function (data, type, row, meta) {
              if (data === 0) {
                return `<span class="badge bg-danger"> Tidak Aktif </span>`;
              } else {
                return `<span class="badge bg-success"> Aktif </span>`;
              }
            },
          },
          {
            data: 'action',
            name: 'action',
            className: 'text-center',
            orderable: false,
            searchable: false
          },
        ],
        rowCallback: function (row, data) {
          let api = this.api();
          $(row).find('.btn-delete').click(function () {
            let pk = $(this).data('id'),
              url = `{{ url()->current()  }}/` + pk;
            Swal.fire({
              title: "Anda Yakin ?",
              text: "Data tidak dapat dikembalikan setelah di hapus!",
              icon: "warning",
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
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                  },
                  error: function (response) {
                    toastr.error(response, 'Failed !');
                  },
                  success: function (response) {
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

      $("#formReset").submit(function (e) {
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
              bsReset.hide();
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

      $('#select2Role').select2({
        dropdownParent: $('#select2Role').parent(),
        placeholder: "Cari Role",
        allowClear: true,
        width: '100%',
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
      }).on('change', function (e) {
        DataTable.draw();
      })
    });
  </script>
@endpush
