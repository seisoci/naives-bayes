@extends('layouts.answer-sheet')
@section('content')
<div id="answer-sheet" class="answer-sheet" as--has-finished="0"></div>
<div class="d-none">
    <button id="as__btnModalCountdown" class="btn btn-primary w-100 p-2 border border-0 rounded-0 rounded-bottom" data-bs-toggle="modal" data-bs-target="#as__modalMainCountdown">Selesai</button>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/katex/katex.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('vendor/katex/katex.min.js') }}"></script>
<script src="{{ asset('vendor/countdown/jquery.countdown.min.js') }}"></script>
<script src="{{ asset('vendor/answer-sheet/ViewAnswerSheet.js') }}"></script>
<script>
$(function(){
    'use strict'
    const AS = new AnswerSheet('#answer-sheet');
});
</script>
@endpush
