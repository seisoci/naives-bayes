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
        <div class="d-flex mb-2 align-items-center">
          <h6 class="m-0 text-uppercase fw-bold">Peserta</h6>
        </div>
        <table id="dt" class="table table-hover w-100">
          <thead>
          <tr>
            <th>Profil</th>
            <th>Durasi Pengerjaan</th>
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
    const profile = (data) => {
      let picture = data.user.picture ? `storage/images/thumbnail/${data.user.picture}` : '/assets/images/no-content.svg';
      let sex = data.user.sex == 'Male' ? 'Laki-laki' : 'Perempuan';
      return `
        <div class="d-flex">
            <div style="width:50px;"><img class="rounded-circle img-fluid" src="${picture}"></div>
            <div class="ps-3">
                <strong>${data.user.fullname}</strong><br/>
                <small>${data.user.email}</small><br/>
                <small>${sex} || ${data.age} || ${data.user.email}</small>
            </div>
        </div>
      `;
    };
    const dt = $('#dt').DataTable({
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, 'All']
      ],
      processing: true,
      serverSide: true,
      search: {
        return: true
      },
      ajax: {
        url: `{{ route('panel.examinations.results.index',['examination' => $examination]) }}`,
        data: (params) => {
          return $.extend({}, params, {});
        }
      },
      order: [[0, 'desc']],
      columns: [
        {
          data: 'user',
          name: 'user.fullname',
          render: (data, type, row, meta) => {
            return profile(row);
          }
        },
        {
          data: 'id',
          name: 'id',
          orderable: false,
          render: (data, type, row, meta) => {
            let timeUsed = parseInt(row.participant_sections_sum_duration_used / 60);
            let timeDuration = row.section_sum_duration / 60;
            return `<p>
               <h6>${timeUsed} Menit</h6>
          </p>`;
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
          orderable: false,
          render: (data, type, row, meta) => {
            let laporan = '';

            if(row.examination.category.name == "IST"){
              laporan = `
                <li><a class="dropdown-item" href="{{ route('panel.examinations.results.index',['examination'=> $examination]) }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>
                <li><a class="dropdown-item" href="{{ route('panel.examinations.results.index',['examination'=> $examination]) }}/${data}?type=psikogram">Psikogram Hasil Pemeriksaan Psikologi</a></li>
              `;
            }

            if(row.examination.category.name == "PAPI KOSTICK"){
              laporan = `<li><a class="dropdown-item" href="{{ route('panel.examinations.results.index',['examination'=> $examination]) }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>`;
            }

            if(row.examination.category.name == "RMIB"){
              laporan = `<li><a class="dropdown-item" href="{{ route('panel.examinations.results.index',['examination'=> $examination]) }}/${data}">Laporan Hasil Pemeriksaan Psikologi</a></li>`;
            }

            return row.date_finish ? `
            <div class="dropdown dropstart">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">&#x22EE;</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('panel.examinations.results.index',['examination'=> $examination]) }}/${data}/sections">Lihat Jawaban</a></li>
                    ${laporan}
                </ul>
            </div>
            ` : '';
          }
        }],
      rowCallback: function (row, data) {
        let api = this.api();
        $(row).find('.btn-delete').click(function () {
          let pk = $(this).data('id'),
            url = `{{ url()->current() }}/` + pk;
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
  </script>
@endpush
