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
          <h6 class="m-0 text-uppercase fw-bold">Sesi Naskah Soal</h6>
          <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm">
              <a href="{{ route('panel.examinations.sections.create',['examination'=>$examination]) }}"
                 class="btn btn-primary">Tambah</a>
            </div>
          </div>
        </div>
        <table id="dt" class="table table-hover" width="100%">
          <thead>
          <tr>
            <th>No</th>
            <th>Naskah Soal</th>
            <th>Penomoran Soal</th>
            <th>Kontrol Soal</th>
            <th>Auto Next</th>
            <th>Durasi</th>
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
        [10, 25, 500, 100, 'All']
      ],
      processing: true,
      serverSide: true,
      search: {
        return: true
      },
      ajax: {
        url: `{{ route('panel.examinations.sections.index',['examination'=>$examination]) }}`,
        data: (params) => {
          return $.extend({}, params, {});
        }
      },
      order: [[0, 'asc']],
      columns: [
        {
          data: 'number',
          width: '80px',
          className: 'text-end'
        },
        {
          data: 'script',
          name: 'script.title',
          render: (script, type, row, meta) => {
            console.log(row);
            return `
            <dl class="row">
                <dt class="col-3 m-0">Judul</dt>
                <dd class="col-9 m-0">${script.title}</dd>
                <dt class="col-3 m-0">Kategori</dt>
                <dd class="col-9 m-0"><b>${script?.category?.name ?? ''}</b> | <b>${script?.subcategory?.name ?? ''}</b></dd>
                <dt class="col-3 m-0">Jumlah Soal</dt>
                <dd class="col-9 m-0">${script.questions_count}</dd>
            </dl>
            `;
          }
        },
        {
          data: 'control_mode',
          render: (text) => {
            return (text > 0) ? `Ya` : `Tidak`;
          }
        },
        {
          data: 'sorting_mode',
          render: (text) => {
            return String(text).replace("Random", "Acak");
          }
        },
        {
          data: 'auto_next',
          render: (text) => {
            return (text > 0) ? `Ya` : `Tidak`;
          }
        },
        {
          data: 'duration',
          render: (text) => {
            return (text / 60);
          }
        },
        {
          data: 'id',
          className: 'text-center',
          width: '10px',
          render: (data, type, row, meta) => {
            return `
            <div class="dropdown dropstart">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">&#x22EE;</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('panel.examinations.sections.index',['examination'=>$examination]) }}/${data}/edit">Edit</a></li>
                    <li><a class="dropdown-item btn-delete" data-id ="${row.id}"  href="#">Hapus</a></li>
                </ul>
            </div>
            `;
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
