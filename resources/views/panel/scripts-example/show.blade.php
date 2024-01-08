@extends('layouts.master')

@section('content')
  <div class="d-flex mb-2 align-items-center">
    <h4 class="mb-0 text-uppercase fw-bold">Naskah Soal</h4>
    <div class="btn-toolbar ms-auto">
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.scripts-example.index') }}" class="btn btn-soft-secondary">Kembali</a>
      </div>
      <div class="btn-group btn-group-sm me-2">
        <a href="{{ route('panel.scripts-example.edit', $script['id']) }}" class="btn btn-soft-secondary">Edit Naskah</a>
      </div>
      <div class="btn-group btn-group-sm">
        <a href="{{ route('panel.scripts-example.questions.index', $script['id']) }}" class="btn btn-soft-secondary">Kelola
          Butir Soal</a>
      </div>
    </div>
  </div>
  @include('panel.scripts.header.script')
  <div class="card my-3">
    <div class="card-body">
      <div class="text-center">
        <h4>Butir Soal</h4>
      </div>
      <hr>
      <ul class="list-unstyled row">
        @foreach ($questions->items() ?? [] as $question)
          <li class="mb-4 col-12">
            <div class="d-flex">
              <div class="me-2">{{ $question->number  }}.</div>
              <div>{!! $question->sentence !!}</div>
            </div>
            <ul class="list-unstyled ps-4">
              @foreach ($question->choices as $choice)
                <li>
                  <div class="d-flex">
                    <div class="me-2">{{ $choice->key  }}.</div>
                    <div>{!! $choice->content !!}</div>
                  </div>
                </li>
              @endforeach
            </ul>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="d-flex justify-content-center">
    {{ $questions->links() }}
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
