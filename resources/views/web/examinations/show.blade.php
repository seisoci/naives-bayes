@extends('layouts.web.master')

@section('content')
  <div class="container py-3">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <h1 class="fw-bolder fs-2 text-uppercase text-center my-3">{{ $participant->examination->name }}</h1>
        <div class="card shadow-sm">
          <div class="text-center">
            <img src="{{ asset('storage/' . $participant->examination->poster) }}" class="card-img-top" alt="..."
                 style="width: 40%; height: auto">
          </div>
          <div class="card-body">
            {!! $participant->examination->description !!}
          </div>
          <div class="list-group list-group-flush">
            @php
              $status = 0;
            @endphp
            @foreach ($participant->participant_sections as $participant_section)
              <div
                @class([
                    'list-group-item',
                ])>
                <div class="row">
                  <div class="col-lg-9">
                    <div class="d-flex">
                      <span class="fw-bold text-end me-3" style="width:20px;">{{ $participant_section->section->number }}.</span>
                      <div class="w-100">
                        <span class="fw-bold">{{ $participant_section->section->script->title }}</span>
                        <dl class="row" style="font-size:0.85rem;">
                          <dt class="fw-normal col-4">Jumlah Soal</dt>
                          <dd class="col-8 mb-0">: {{ $participant_section->section->script->questions_count }}</dd>
                        </dl>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 text-end">
                    <a
                      href="{{ ($participant_section->status != 'Finish') ? route('examinations.sections.show', ['participant'=>$participant, 'section' => $participant_section]) : '#' }}"
                      @class([
                          "btn",
                          "btn-sm",
                          "w-100",
                          "rounded-pill",
                          "btn-primary" => (($participant_section->status == 'Finish')),
                          "btn-outline-secondary" => (!($participant_section->status == 'Finish')),
                          "disabled" => (!($participant_section->status != 'Finish') || $status)
                      ])>
                      {{ ($participant_section->status == 'Finish') ? 'Telah Dikerjakan' : ( $participant_section->status == 'Running' ? 'Lanjutkan' : 'Mulai Ujian') }}
                    </a>
                  </div>
                </div>
              </div>
              @php
                $status = $participant_section->status != 'Finish' ? 1 : 0;
              @endphp
            @endforeach
          </div>
        </div>

        <div class="mt-4">
          @if(($status == 0))
            <button type="button" class="btn btn-primary rounded-pill w-100 mb-3" data-bs-toggle="modal"
                    data-bs-target="#modalStore">Kirim Lembar Jawaban
            </button>
          @endif
          <a href="{{ route('examinations.index') }}" class="btn btn-outline-primary rounded-pill w-100">Kembali</a>
        </div>
      </div>
    </div>
    <div class="modal fade" id="modalStore" tabindex="-1" aria-labelledby="modalStoreLabel" aria-hidden="true"
         data-bs-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="formStore" method="POST" action="{{ route('examinations.update',['participant' => $participant_section->participant]) }}">
            @csrf
            @method('PUT')
          <div class="modal-body">
            <h5 class="fw-bolder">Peringatan !</h5>
            <span id="count-answered" class="text-center"></span>
            <br/>Lembar jawaban yang dikirim tidak dapat diubah kembali, pastikan anda sudah yakin dengan jawaban anda.
            <br/>Apakah anda yakin ingin melanjutkan ?
          </div>
          <div class="modal-footer">
            <button id="btn-cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Kirim !</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('style')

@endpush

@push('script')
  <script>
    $(document).ready(function () {
      let csrfToken = $('meta[name="csrf-token"]').attr('content');
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': csrfToken
        }
      });

      $('#btn-store').click(function () {
        $.post(`{{ route('examinations.update',['participant' => $participant_section->participant]) }}`, {
          _method: 'PUT',
        }).done(function (e) {
          location.href = "{{ route('results.show', $participant_section->participant) }}";
        });
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
            location.href = "{{ route('results.show', $participant_section->participant) }}";
          },
        });
      });


    })
  </script>
@endpush
