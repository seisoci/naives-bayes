@extends('layouts.master')

@section('content')
  <div class="d-flex mb-2 align-items-center d-print-none">
    <div class="btn-toolbar ms-auto">
      <div class="btn-group btn-group-sm me-2">
        <a onclick="history.back()" class="btn btn-soft-secondary">Kembali</a>
      </div>
    </div>
  </div>
  <div id="printForm">
    {!! $result['view'] ?? '' !!}
  </div>
@endsection

@push('style')
  <style>
    @media print {
      #chart {
        max-width: 100%;
        position: relative;
      }
    }
  </style>
@endpush
@push('script')
    {!! $result['js'] ?? '' !!}
@endpush
