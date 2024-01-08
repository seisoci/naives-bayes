@extends('layouts.master')

@section('content')
  <div class="row">
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-user-graduate fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['userCount'] }}</h3>
              <p class="mb-0">Peserta</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-file-text fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['scriptExampleCount'] }}</h3>
              <p class="mb-0">Demo Naskah Soal</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-file-text fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['scriptCount'] }}</h3>
              <p class="mb-0">Naskah Soal</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-file-circle-question fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['questionCount'] }}</h3>
              <p class="mb-0">Butir Soal</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-calendar-days fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['examinationCount'] }}</h3>
              <p class="mb-0">Total Ujian</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fa-regular fa-calendar fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['examinationPlanCount'] }}</h3>
              <p class="mb-0">Rencana Ujian</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-calendar-plus fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['examinationOnGoingCount'] }}</h3>
              <p class="mb-0">Ujian Dibuka</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-calendar-check fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['examinationDoneCount'] }}</h3>
              <p class="mb-0">Ujian Ditutup</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-tags fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['ticketCount'] }}</h3>
              <p class="mb-0">Total Tiket</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-tags fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['ticketAvailableCount'] }}</h3>
              <p class="mb-0">Tiket Tersedia</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-tags fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['ticketHavingCount'] }}</h3>
              <p class="mb-0">Tiket Dimiliki</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between px-md-1">
            <div class="align-self-center">
              <i class="fas fa-tags fa-3x"></i>
            </div>
            <div class="text-end">
              <h3>{{ $data['ticketUsedCount'] }}</h3>
              <p class="mb-0">Tiket Digunakan</p>
            </div>
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
