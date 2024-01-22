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
         <div class="d-flex justify-content-end">
           <a href="{{ route('panel.naive-bayes.show', 'prediksi') }}" class="btn btn-success me-2">
             <i class="fa-solid fa-calculator"></i> Prediksi Kemenangan
           </a>
           <a href="{{ route('panel.naive-bayes.create') }}" class="btn btn-primary">
             <i class="fa-solid fa-plus"></i> Tambah
           </a>
         </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="Datatable" class="table table-striped w-100">
          <thead>
          <tr>
            <th>Hero</th>
            <th>Hero Musuh</th>
            <th>Tipe Build</th>
            <th>Emblem</th>
            <th>Hasil</th>
            <th data-priority="1">Aksi</th>
          </tr>
          </thead>
          <tbody></tbody>
        </table>
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
      let dataTable = $('#Datatable').DataTable({
        lengthChange: false,
        buttons: ['pageLength', {
          extend: 'copy',
          footer: true
        }, {
          extend: 'csv',
          footer: true
        }, {
          extend: 'excel',
          footer: true
        },],
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
        lengthMenu: [[100, 250, 1000, -1], [100, 250, 1000, "All"]],
        pageLength: 100,
        columns: [
          {data: 'hero.nama', name: 'hero.nama'},
          {data: 'hero_musuh.nama', name: 'hero_musuh.nama'},
          {data: 'tipe_build', name: 'tipe_build'},
          {data: 'emblem', name: 'emblem'},
          {data: 'hasil', name: 'hasil'},
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
        },
        initComplete: function (settings, json) {
          dataTable.buttons().container().appendTo('#Datatable_wrapper .col-md-6:eq(0)')
        },
      });

    });
  </script>
@endpush
