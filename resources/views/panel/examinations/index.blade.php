@extends('layouts.master')

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex mb-2 align-items-center">
          <h4 class="m-0 text-uppercase fw-bold">Ujian</h4>
          <div class="btn-toolbar ms-auto">
            <div id="export-button" class="btn-group btn-group-sm me-2"></div>
            <div class="btn-group btn-group-sm">
              <a href="{{ route('panel.examinations.create') }}" class="btn btn-primary">Tambah</a>
            </div>
          </div>
        </div>
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <table id="DataTable" class="table table-hover w-100">
              <thead>
              <tr>
                <th>Poster</th>
                <th>Nama Ujian</th>
                <th>Status</th>
                <th>Price</th>
                <th>Tgl Dibuat</th>
              </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('style')
  <link
    href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.css"
    rel="stylesheet">
@endpush
@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/b-print-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/rr-1.4.1/datatables.min.js"></script>
  <script>
    const dt = $('#DataTable').DataTable({
      buttons: [{
        extend: 'print',
        className: 'btn btn-sm btn-outline-secondary',
        exportOptions: {
          columns: [0]
        }
      }, {
        extend: 'excelHtml5',
        className: 'btn btn-sm btn-outline-secondary',
        exportOptions: {
          columns: [0]
        }
      }, {
        extend: 'pdfHtml5',
        className: 'btn btn-sm btn-outline-secondary',
        exportOptions: {
          columns: [0]
        }
      }],
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 500, 100, 'All']
      ],
      processing: true,
      serverSide: true,
      search: {
        return: true
      },
      ajax: {
        url: `{{ url()->current() }}`,
        data: (params) => {
          return $.extend({}, params, {});
        }
      },
      order: [[4, 'desc']],
      columns: [{
        data: 'poster',
        width: '50px',
        render: (data, type, row, meta) => {
          return `<img src="{{ asset('storage') }}/${data}" width="60"/>`;
        }
      }, {
        data: 'name',
        width: '200px',
        render: (data, type, row, meta) => {
          return `<a href="{{ route('panel.examinations.index') }}/${row.id}"><strong>${data}</strong></a>`
        }
      }, {
        data: 'status',
        render: (data, type, row, meta) => {
          if (data == 'Plan') {
            return 'Rencana';
          } else if (data == 'Done') {
            return 'Ditutup';
          } else {
            return 'Dibuka';
          }
        }
      }, {
        data: 'price',
        width: '100px',
        render: $.fn.dataTable.render.number(',', '.', 2, 'Rp. ')
      }, {
        data: 'created_at',
        width: '100px',
      }],
      initComplete: function () {
        this.api().buttons().container().css({
          marginBottom: 0
        }).addClass('btn-group');
        this.api().buttons().container().appendTo('#export-button');
      }
    });
  </script>
@endpush
