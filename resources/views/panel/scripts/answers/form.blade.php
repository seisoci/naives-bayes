@extends('layouts.master')

@section('content')
  <div class="row">
    <div class="col-md-8">
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
      <form id="formStore" method="POST" action="{{ route('panel.scripts.questions.store',['script'=>$script]) }}" enctype="multipart/form-data">
        @method('POST')
        @csrf
        <div class="d-flex mb-2 align-items-center mt-4">
          <h6 class="m-0 text-uppercase fw-bold">Butir Soal</h6>
          <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm me-2">
              <a class="btn btn-outline-secondary" href="{{ route('panel.scripts.questions.index', ['script'=>$script]) }}">Batal</a>
            </div>
            <div class="btn-group btn-group-sm">
              <button type="submit" class="btn btn-primary">Tambah</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group mb-3">
                  <label for="number">No Urut</label>
                  <input id="number" type="text" class="form-control" name="number" value="{{ $question->number ?? '' }}">
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
            <div class="form-group mb-3">
              <label for="sentence">Pertanyaan</label>
              <textarea id="sentence" class="form-control" name="sentence"></textarea>
              <div class="invalid-feedback"></div>
            </div>
            <div class="table-responsive">
              <table id="dt" class="table table-sm w-100" width="100%">
                <thead>
                <tr>
                  <th colspan="2">Pilihan Jawaban</th>
                </tr>
                <tr>
                  <th>Key</th>
                  <th>Konten</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                  <th></th>
                  <th class="text-end">
                    <div class="btn-group">
                      <button id="btn-row-delete" type="button" class="btn btn-outline-secondary">-</button>
                      <button id="btn-row-add" type="button" class="btn btn-outline-secondary">+</button>
                    </div>
                  </th>
                </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" />
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script
    src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-colvis-2.4.1/b-html5-2.4.1/fc-4.3.0/fh-3.4.0/r-2.5.0/rg-1.4.0/datatables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
  <script src="{{ asset('assets/libs/math/math.js') }}"></script>
  <script>
    $(document).ready(function () {
      $("#formStore").submit((e) => {
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
              setTimeout(function () {
                if (response.redirect === "" || response.redirect === "reload") {
                  location.reload();
                } else {
                  location.href = response.redirect;
                }
              }, 1000);
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
      @if (isset($question->choices) && count($question->choices) > 0)
      let choices = {!!  json_encode($question->choices) !!};
      @else
      let choices = [
        { key : 'A', content : '', score : 0 },
        { key : 'B', content : '', score : 0 },
        { key : 'C', content : '', score : 0 },
        { key : 'D', content : '', score : 0 },
      ];
      @endif
      const dt = $('#dt').DataTable({
        data        : choices,
        paging      : false,
        info        : false,
        searching   : false,
        columns     : [{
          data : 'key',
          width : '60px',
          render : function(data, type, row, meta){
            return `<input id="choices-${meta.row}-key" class="form-control text-center choice-key" value="${data}" ><div class="invalid-feedback"></div>`;
          }
        }, {
          data : 'content',
          render : function(data, type, row, meta){
            return `<textarea id="choices-${meta.row}-content" class="form-control choice-content">${data}</textarea><div class="invalid-feedback"></div>`;
          }
        }],
        createdRow : function(row, data, dataIndex, cells){
          $(row).find(`#choices-${dataIndex}-content`).summernote({
            toolbar: [
              ['style', ['style']],
              ['font', ['bold', 'underline', 'clear']],
              ['fontname', ['fontname']],
              ['fontsize',['fontsize']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['table', ['table']],
              ['insert', ['link', 'picture', 'video', 'math']],
              ['view', ['fullscreen', 'codeview']],
            ]
          });
        },
        rowCallback : function(row, data, displayNum, displayIndex, dataIndex){
          $(row).find('.choice-key').attr('name',`choices[${dataIndex}][key]`);
          $(row).find('.choice-content').attr('name',`choices[${dataIndex}][content]`);
          $(row).find('.choice-score').attr('name',`choices[${dataIndex}][score]`);
        }
      });

      let sentence =  $('#sentence').summernote({
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['fontsize',['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'video', 'math']],
          ['view', ['fullscreen', 'codeview', 'help']],
        ]
      });

      @if (isset($question->choices) && count($question->choices) > 0)
        sentence.summernote('code',`{!! $question->sentence !!}`)
      @endif

      $('#btn-row-add').click(function(e){
        e.preventDefault();
        dt.row.add({
          key : '',  content : '', score : 0
        }).draw();
      });

      $('#btn-row-delete').click(function(e){
        dt.row( ':last' ).remove().draw();
      });

    });
  </script>
@endpush
