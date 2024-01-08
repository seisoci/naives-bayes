@extends('layouts.master')

@section('content')
    <div class="card">
      <div class="card-header justify-content-between">
        <div class="header-title">
          <div class="row">
            <div class="col-sm-6 col-lg-6">
              <h4 class="card-title">Data Roles </h4>
            </div>
            <div class="col-sm-6 col-lg-6">
              <a href="{{ route('panel.roles.create') }}" class="btn btn-primary float-end">
                <i class="fa-solid fa-plus"></i> Tambah
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="dt" class="table table-bordered w-100">
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
      $('#dt').DataTable({
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
    });
  </script>
@endpush
