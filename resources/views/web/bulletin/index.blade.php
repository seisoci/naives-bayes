@extends('layouts.web.master')

@section('content')
  <div class="container py-3">
    <div class="row justify-content-center">
      <div class="col-md-10 col-12-8">
        <h1 class="fw-bolder fs-2 text-uppercase text-center my-3">Pemberitahuan</h1>
        <div class="card">
          <div class="card-body">
            <p class="card-text">
              {!! $data['description'] ?? '' !!}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('style')
@endpush

@push('script')
@endpush
