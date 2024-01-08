@extends('layouts.master')

@section('content')
  <div class="d-flex mb-2 align-items-center">
    <h4 class="mb-0 text-uppercase fw-bold">Naskah Soal</h4>
    <div class="btn-toolbar ms-auto">
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.examinations.results.index', ['examination' => $examination]) }}"
           class="btn btn-soft-secondary">Kembali</a>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header justify-content-between">
      <div class="header-title">
        <h3 class="text-center">{{ $examination['name'] ?? '' }}</h3>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        @foreach($participant->participant_sections ?? [] as $item)
          <div class="col-md-3">
            <a
              href="{{ route('panel.examinations.results.sections.index', ['examination' => $examination, 'participant' => $participant, 'participant_section_id' => $item['id'] ]) }}"
              class="btn btn-outline-dark my-2 {{ $item['id'] == $participant_section_id ? 'active' : '' }}">{{ $item['section']['script']['title'] ?? '' }}</a>
          </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="card my-3">
    <div class="card-body">
      <div class="text-center">
        <h4>Butir Soal</h4>
      </div>
      <hr>
      <ul class="list-unstyled row">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>No</th>
            <th>Soal</th>
            <th>Kunci Jawaban</th>
            <th>Jawaban</th>
            <th>Score</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($questions ?? [] as $item)
            <tr>
              <td class="align-top">{{ $item->question->number }}</td>
              <td>{!! $item->question->sentence !!}
                <ul class="list-unstyled ps-4">
                  @foreach ($item->question->choices ?? [] as $choice)
                    <li>
                      <div class="d-flex">
                        <div class="me-2">{{ $choice->key  }}.</div>
                        <div>{!! $choice->content !!}</div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </td>
              <td>
                @foreach($item->question->scores ?? [] as $itemScore)
                  <b>{{ $itemScore['value'] ?? '' }}</b>: {{ $itemScore->choices->pluck('key')->implode(', ') }}<br>
                @endforeach
              </td>
              <td>{{ $item['answers'] }}</td>
              <td>{{ $item['score'] }}</td>
            </tr>
          @endforeach
          </tbody>
          <tfoot>
          <tr>
            <th colspan="4" class="text-end">Total Score</th>
            <th>{{ isset($questions) ? $questions->sum('score') : '-' }}</th>
          </tr>
          </tfoot>
        </table>
      </ul>
    </div>
  </div>
@endsection

@push('style')
@endpush

@push('script')
  <script>
    $(document).ready(function () {

    });
  </script>
@endpush
