@extends('layouts.master')

@section('content')
  <div class="d-flex mb-2 align-items-center">
    <h4 class="mb-0 text-uppercase fw-bold">Kunci Jawaban</h4>
    <div class="btn-toolbar ms-auto">
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.scripts-example.show', $script['id']) }}" class="btn btn-soft-secondary">Kembali</a>
      </div>
      <div class="btn-group btn-group-sm me-2">
        <a
          href="{{ route('panel.scripts-example.questions.edit', ['script' => $script['id'], 'question' => $question['id']]) }}"
          class="btn btn-soft-secondary">Edit Naskah</a>
      </div>
    </div>
  </div>
  @include('panel.scripts.header.question')
  <form id="formStore" method="POST"
        action="{{ route('panel.scripts-example.questions.answers.store',['script'=>$script, 'question'=>$question]) }}"
        enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="d-flex mb-2 align-items-center mt-4">
      <h6 class="m-0 text-uppercase fw-bold">Butir Soal</h6>
      <div class="btn-toolbar ms-auto">
        <div class="btn-group btn-group-sm me-2">
          <a class="btn btn-outline-secondary" href="{{ route('panel.scripts.questions.index', ['script'=>$script]) }}">Batal</a>
        </div>
        <div class="btn-group btn-group-sm">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body">
        <div id="errorCreate" class="mb-3" style="display:none;">
          <div class="alert alert-danger" role="alert">
            <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
            <div class="alert-text">
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table id="DataTable" class="table table-borderless w-100">
            <thead>
            <tr>
              <th colspan="2">Pilihan Jawaban</th>
            </tr>
            <tr>
              <th>Score</th>
              <th>Kunci Jawaban</th>
            </tr>
            </thead>
            <tbody></tbody>
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
      let idx = 0;

      let choices = {!! json_encode($choice) !!}

      const removeHTMLTags = (htmlString) => {
        const tempElement = document.createElement('div');
        tempElement.innerHTML = htmlString;
        return tempElement.textContent || tempElement.innerText || '';
      };

      const inArray = (element, array) => array.includes(element);

      const dt = $('#DataTable').DataTable({
        data: {!! json_encode($answer) ?? json_encode($choice) !!},
        paging: false,
        info: false,
        searching: false,
        columns: [{
          data: 'value',
          width: '60px',
          render: function (data, type, row, meta) {
            return `<input id="choices-${meta.row}-key" class="form-control text-center choice-value" value="${data}" ><div class="invalid-feedback"></div>`;
          }
        },
        {
          data: 'choices',
          render: function (data, type, row, meta) {
            let choices = '';
            data.forEach((item, index) => {
              choices += `
                <div class="mb-2 d-flex choice">
                  <div class="key pe-2">
                    <input
                      id="as__choice_${idx}"
                      class="btn-check as__choice_item choice-choice_id"
                      type="checkbox"
                      name="choice_id"
                      value="${item.id}"
                      ${inArray(item.id, row.answer ?? []) ? "checked" : ""}
                    >
                    <label for="as__choice_${idx++}" class="btn btn-sm btn-outline-primary text-uppercase">
                        ${item.key}. ${removeHTMLTags(item.content)}
                    </label>
              `
            });

            return choices;
          }
        }],
        rowCallback: function (row, data, displayNum, displayIndex, dataIndex) {
          $(row).find('.choice-value').attr('name', `choices[${dataIndex}][value]`);
          $(row).find('.choice-choice_id').attr('name', `choices[${dataIndex}][choice_id][]`);
        }
      });

      $('#btn-row-add').click(function (e) {
        e.preventDefault();
        dt.row.add(choices[0]).draw();
      });

      $('#btn-row-delete').click(function (e) {
        dt.row(':last').remove().draw();
      });


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
    });
  </script>
@endpush
