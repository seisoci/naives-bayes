@extends('layouts.web.master')

@section('content')
  <div class="container">
    <div class="card border">
      <div class="card-header">
        <h6 class="card-title mb-0">{{ $config['title'] ?? '' }}</h6>
      </div>
      <div class="card-body">
        <table id="dt" class="table table-hover w-100">
          <thead>
          <tr>
            <th>Ujian</th>
            <th>No Tiket</th>
            <th>Waktu Penyelesaian Ujian</th>
            <th>Tgl Selesai</th>
            <th></th>
          </tr>
          </thead>
        </table>
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
    const dt = $('#dt').DataTable({
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'All']
      ],
      orderable: [0, 'desc'],
      processing: true,
      serverSide: true,
      search: {
        return: true
      },
      ajax: {
        url: `{{ route('results.index') }}`,
        data: (params) => {
          return $.extend({}, params, {});
        }
      },
      order: [[0, 'desc']],
      columns: [
        {
          data: 'name',
          name: 'e.created_at',
        },
        {
          data: 'ticket.code',
          name: 'ticket.code',
        },
        {
          data: 'date_finish',
          name: 'e.name',
          orderable: false,
          render: (data, type, row, meta) => {
            let timeUsed = parseInt(row.participant_sections_sum_duration_used / 60);
            if(timeUsed == 0){
              return ``;
            }
            return ` <h6>${timeUsed} Menit</h6>`;
          }
        },
        {
          data: 'date_finish',
          name: 'date_finish',
        },
        {
          data: 'participant_id',
          className: 'text-center',
          width: '10px',
          searchable: false,
          orderable: false,
          render: (data, type, row, meta) => {
            let laporan = '';

            if(row.examination.category.name == "IST"){
              laporan = `
                <li><a class="dropdown-item" href="{{ url()->current() }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>
                <li><a class="dropdown-item" href="{{ url()->current() }}/${data}?type=psikogram">Psikogram Hasil Pemeriksaan Psikologi</a></li>
              `;
            }

            if(row.examination.category.name == "PAPI KOSTICK"){
              laporan = `<li><a class="dropdown-item" href="{{ url()->current() }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>`;
            }

            if(row.examination.category.name == "RMIB"){
              laporan = `<li><a class="dropdown-item" href="{{ url()->current() }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>`;
            }

            return row.date_finish ? `
            <div class="dropdown dropstart">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">&#x22EE;</button>
                <ul class="dropdown-menu">
                    ${laporan}
                </ul>
            </div>
            ` : '';
          }
        }],
    });
  </script>
@endpush
