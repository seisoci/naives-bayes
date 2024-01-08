@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="d-flex mb-2 align-items-center">
        <h4 class="m-0 text-uppercase fw-bold">Ujian</h4>
        <div class="btn-toolbar ms-auto">
            <div class="btn-group btn-group-sm me-2">
                <a href="{{ route('panel.examinations.index') }}" class="btn btn-outline-secondary">Daftar Ujian</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            @include('panel.examinations.parts.info')
        </div>
        <div class="card-header">
            @include('panel.examinations.parts.navs')
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-file-lines fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->sections()->count() ?? '-' }}</h3>
                                    <p class="mb-0">Jumlah Sesi Naskah</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-clipboard-question fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ !empty($examination->question_count) ? $examination->question_count : 0 }}</h3>
                                    <p class="mb-0">Jumlah Soal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fa-solid fa-stopwatch fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->sections_sum_duration != 0  ?  round($examination->sections_sum_duration/60)  : 0 }} <small class="fs-6">Menit</small></h3>
                                    <p class="mb-0">Total Durasi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-pencil-alt fa-3x"></i>
                                </div>
                                <div class="text-end">
{{--                                    <h3>{{ $questions_summary != null ? number_format($questions_summary->highest_score,2) : 0 }}</h3>--}}
                                    <p class="mb-0">Skor Tertinggi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-tags fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->tickets()->count() }}</h3>
                                    <p class="mb-0">Jumlah Tiket</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-pencil-alt fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->tickets()->where('owner_id','!=',null)->count() ?? 0 }}</h3>
                                    <p class="mb-0">Tiket Dimiliki</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fa-solid fa-users fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->participants()->count() ?? 0 }}</h3>
                                    <p class="mb-0">Total Peserta</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between px-md-1">
                                <div class="align-self-center">
                                    <i style="color: #151529;" class="fas fa-tag fa-3x"></i>
                                </div>
                                <div class="text-end">
                                    <h3>{{ $examination->tickets()->where('owner_id',null)->count() ?? 0 }}</h3>
                                    <p class="mb-0">Sisa Tiket</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
