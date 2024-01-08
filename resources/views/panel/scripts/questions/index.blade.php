@extends('layouts.master')

@section('content')
  <div class="d-flex mb-2 align-items-center">
    <h4 class="mb-0 text-uppercase fw-bold">Naskah Soal</h4>
    <div class="btn-toolbar ms-auto">
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.scripts.show', $script['id']) }}" class="btn btn-soft-secondary">Kembali</a>
      </div>
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.scripts.edit', $script['id']) }}" class="btn btn-soft-secondary">Edit Naskah</a>
      </div>
    </div>
  </div>
  @include('panel.scripts.header.script')
  <div class="d-flex mb-2 align-items-center mt-4">
    <h6 class="m-0 text-uppercase fw-bold">Butir Soal</h6>
    <div class="btn-toolbar ms-auto">
      <div id="export-button" class="btn-group btn-group-sm me-2"></div>
      <div class="btn-group btn-group-sm me-2">
        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalImport">Import Soal</button>
        <a class="btn btn-outline-secondary" href="{{ route('panel.scripts.questions.export',['script'=> $script['id']]) }}">Export Soal</a>
      </div>
      <div class="btn-group btn-group-sm me-2">
        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalImportAnswers">Import Kunci Jawaban</button>
        <a class="btn btn-outline-success" href="{{ route('panel.scripts.questions.export-answers',['script'=> $script['id']]) }}">Export Kunci Jawaban</a>
      </div>
      <div class="btn-group btn-group-sm">
        <a href="{{ route('panel.scripts.questions.create',['script'=> $script['id']]) }}" class="btn btn-primary">Tambah</a>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table id="dataTable" class="table table-borderless w-100">
          <thead>
          <tr>
            <th>No</th>
            <th>Pertanyaan</th>
            <th>Jawaban</th>
            <th class="text-center">Aksi</th>
          </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="modalImport" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <form id="formImport" class="modal-content" method="POST" action="{{ route('panel.scripts.questions.import', ['script' => $script['id']]) }}">
        @method('POST')
        @csrf
        <div class="modal-header p-2">
          <h5 class="modal-title"><strong>Import</strong></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-2">
          <div class="alert alert-primary mb-3"><strong>Peringatan !</strong><br>Proses import akan menimpah data soal yang sudah ada.</div>
          <div class="form-group">
            <input id="file" type="file" class="form-control" name="file">
            <div class="invalid-feedback"></div>
          </div>

        </div>
        <div class="modal-footer p-2">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
          <button type="submit" class="btn btn-sm btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
  <div id="modalImportAnswers" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <form id="formImportAnswers" class="modal-content" method="POST" action="{{ route('panel.scripts.questions.import-answers', ['script' => $script['id']]) }}">
        @method('POST')
        @csrf
        <div class="modal-header p-2">
          <h5 class="modal-title"><strong>Import</strong></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-2">
          <div class="alert alert-success mb-3"><strong>Peringatan !</strong><br>Proses import akan menimpah data jawaban yang sudah ada.</div>
          <div class="form-group">
            <input id="file" type="file" class="form-control" name="file">
            <div class="invalid-feedback"></div>
          </div>

        </div>
        <div class="modal-footer p-2">
          <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batalkan</button>
          <button type="submit" class="btn btn-sm btn-primary">Import</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('style')
  <link
    href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.css"
    rel="stylesheet">
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.js"></script>
  <script>
    $(document).ready(function () {
      const decodeHtml = (html) => {
        const txt = document.createElement("textarea");
        txt.innerHTML = html;
        return txt.value;
      };

      const DataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        search: {
          return: true
        },
        ajax: {
          url: `{{ url()->current() }}`
        },
        columns: [
          {
            data: 'number',
            width: '20px',
            className: 'text-end'
          },
          {
            data: 'sentence',
            render: (data, type, row, meta) => {
              let html = ``;
              $.each(row.choices, (index, choice) => {
                html += `<tr>
                    <td class="text-end" width="50">${choice.key}.</td>
                    <td>${decodeHtml(choice.content)}</td>
                </tr>`;
              });
              return decodeHtml(data) + `<table class="table table-sm table-borderless table-striped mt-1">${html}</table>`;
            }
          },
          {
            data: 'number',
            width: '200px',
            render: (data, type, row, meta) => {
              let html = ``;
              $.each(row.scores, (index, scores) => {
                let pluckedNames = scores.choices.map(item => item.key);
                  html += `<tr>
                    <td width="50"><b>${scores.value}</b>: ${pluckedNames}</td>
                </tr>`;
              });
              return `<table class="table table-sm table-borderless table-striped mt-1">${html}</table>`;
            }
          },
          {
            data: 'id',
            className: 'text-center',
            width: '50px',
            render: function (data, type, row, meta) {
              return `
            <div class="dropdown dropstart">
                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">&#x22EE;</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ url()->current() }}/${data}/answers">Tambah Kunci Jawaban</a></li>
                    <li><a class="dropdown-item" href="{{ url()->current() }}/${data}/edit">Edit Soal</a></li>
                    <li><a class="dropdown-item btn-delete" href="#" data-id="${data}">Hapus</a></li>
                </ul>
            </div>
            `;
            }
          }],
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

      $("#formImport, #formImportAnswers").submit((e) => {
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
              const modalIds = ['#modalImport', '#modalImportAnswers'];
              modalIds.forEach(modalId => {
                const modal = bootstrap.Modal.getInstance($(modalId).get(0));
                if (modal) {
                  modal.hide();
                }
              });
              DataTable.draw();
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
    });
  </script>
@endpush
