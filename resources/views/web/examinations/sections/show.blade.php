@extends('layouts.answer-sheet')
@section('content')
  <div id="answer-sheet" class="answer-sheet" as--has-finished="0"></div>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <div class="d-none">
    <button id="as__btnModalCountdown" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom"
            data-bs-toggle="modal" data-bs-target="#as__modalMainCountdown">Selesai
    </button>
  </div>
@endsection

@push('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.css"/>
  <style>
    table{
      width: 100% !important;
    }
  </style>
@endpush

@push('script')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/KaTeX/0.9.0/katex.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-countdown@2.2.0/dist/jquery.countdown.min.js"></script>
  @if($participant->examination->category_id != 6)
    <script src="{{ asset('vendor/answer-sheet/AnswerSheet.js') }}"></script>
  @else
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js" integrity="sha512-Eezs+g9Lq4TCCq0wae01s9PuNWzHYoCMkE97e2qdkYthpI0pzC3UGB03lgEHn2XM85hDOUF6qgqqszs+iXU4UA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('vendor/answer-sheet/AnswerSheetRMIB.js') }}"></script>
  @endif
    <script>
    $(function () {
      'use strict'
      const AS = new AnswerSheet('#answer-sheet');
    });
  </script>
@endpush
