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
          <h6 class="m-0 text-uppercase fw-bold">Tiket</h6>
          <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm me-2">
            </div>
            <div class="btn-group btn-group-sm">
              <a href="{{ route('panel.examinations.tickets.create',['examination'=> $examination]) }}"
                 class="btn btn-primary">Generate Tiket</a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table id="dt" class="table table-hover w-100">
            <thead>
            <tr>
              <th>Kode</th>
              <th>Pemilik</th>
              <th>Sudah Digunakan</th>
              <th></th>
            </tr>
            </thead>
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
      responsive: true,
      serverSide: true,
      processing: true,
      ajax: {
        url: `{{ route('panel.examinations.tickets.index',[ 'examination'=> $examination]) }}`,
        data: (params) => {
          return $.extend({}, params, {});
        }
      },
      order: [[0, 'desc']],
      columns: [{
        data: 'code',
        width: '150px'
      },
        {
          data: 'owner',
          name: 'owner.fullname',
          render: (data, type, row, meta) => {
            let html = ``;
            if (data !== null) {
              console.log(row);
              let picture = data.picture ? `storage/images/thumbnail/${data.picture}` : '/assets/images/no-content.svg';
              let sex = data.sex == 'Male' ? 'Laki-laki' : 'Perempuan';
              html += `
                <div class="d-flex align-items-center">
                    <img class="rounded-circle me-2" src="${picture}" height="50">
                    <div>
                        <strong>${data.fullname}</strong><br/>
                        <small>${sex} || ${row.participant.age} || ${data.email}</small>
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
          className: 'text-center',
          render: (participant, type, row, meta) => {
            let html = ``;
            if (participant !== null) {
              html += `<i class="fa-solid fa-check"></i>`;
            } else {
              if (row.owner_id !== null) {
                html += `<button class="btn btn-sm btn-outline-primary btn-enroll" data-ticket-id="${row.id}">Enroll</button>`;
              }
            }
            return html;
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
                    <li><a class="dropdown-item" href="{{ route('panel.examinations.tickets.index',['examination'=>$examination]) }}/${data}/edit">Edit</a></li>
                    <li><a class="dropdown-item btn-delete" data-id ="${row.id}"  href="#">Hapus</a></li>
                </ul>
            </div>
            `;
          }
        }],
      createdRow: (row, data) => {
        $(row).find('.btn-enroll').click((e) => {
          e.preventDefault();
          let btn = $(e.target),
            btnHTML = btn.html();

          btn.prop('disabled', true);
          btn.addClass('disabled').html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

          $.post(
            `{{ route('panel.examinations.tickets.index',['examination'=>$examination]) }}/${data.id}/enroll`, {
              _method: 'GET'
            }, (xhr) => {
              dt.ajax.reload(null, false);
            }, 'json'
          ).fail((xhr) => {

          }).always(() => {
            btn.prop('disabled', false).removeClass('disabled').html(btnHTML);
          });
        });
      },
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
  </script>
@endpush
