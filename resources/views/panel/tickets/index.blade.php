@extends('layouts.master')

@section('content')
  <div class="container-fluid">
    <div class="d-flex mb-2 align-items-center">
      <h4 class="m-0 text-uppercase fw-bold">Peserta</h4>
      <div class="btn-toolbar ms-auto">
        <div id="export-button" class="btn-group btn-group-sm"></div>
      </div>
    </div>
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <div class="table-responsive">
          <table id="DataTable" class="table w-100">
            <thead>
            <tr>
              <th>Kode Tiket</th>
              <th>Ujian</th>
              <th>Pemilik</th>
              <th>Sudah Digunakan</th>
              <th>Tgl Buat</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
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
    const calculateAge = (birthday) => {
      const birthDate = new Date(birthday);
      const today = new Date();

      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDifference = today.getMonth() - birthDate.getMonth();

      if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }

      return age;
    };

    let dataTable = $('#DataTable').DataTable({
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
      responsive: true,
      serverSide: true,
      processing: true,
      ajax: {
        url: `{{ url()->current() }}`
      },
      columns: [
        {
          data: 'code'
        },
        {
          data: 'examination',
          name: 'examination.name',
          render: (examination) => {
            console.log(examination);
            let html = ``;
            if (examination !== null) {
              html += `
                <div class="d-flex align-items-center">
                    <img class="me-2" src="{{ asset('storage') }}/${examination.poster}" height="40">
                    <div>
                        <strong>${examination.name}</strong><br/>
                        <small></small>
                    </div>
                </div>
                `;
            }
            return html;
          }
        },
        {
          data: 'owner',
          name: 'owner.fullname',
          width: '300px',
          render: (owner) => {
            let html = ``;
            if (owner !== null) {
              let picture =  owner.picture ? `storage/images/thumbnail/${owner.picture}` : '/assets/images/no-content.svg';
              html += `
                <div class="d-flex align-items-center">
                    <img class="rounded-circle me-2" src="${picture}" height="40">
                    <div>
                        <strong>${owner.fullname}</strong><br/>
                        <small>${owner.sex} || ${calculateAge(owner.birthday)} || ${owner.email}</small>
                    </div>
                </div>
                `;
            }
            return html;
          }
        },
        {
          data: 'participant',
          name: 'participant.id',
          render: (participant) => {
            let html = ``;
            if (participant !== null) {
              html += `<i class="fa-solid fa-check"></i>`;
            }
            return html;
          }
        },
        {
          data: 'created_at',
          name: 'created_at'
        },
      ],
      initComplete: function () {
        this.api().buttons().container().css({
          marginBottom: 0
        }).addClass('btn-group');
        this.api().buttons().container().appendTo('#export-button');
      }
    });

  </script>
@endpush
