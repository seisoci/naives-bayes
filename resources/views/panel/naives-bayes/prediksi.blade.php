@extends('layouts.master')

@section('content')
  <div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
      <div class="row">
        <div class="col-sm-12 col-lg-12">
          <div class="card">
            <div class="card-header justify-content-between">
              <div class="header-title">
                <div class="row">
                  <div class="col-sm-6 col-lg-6">
                    <h4 class="card-title">{{ $config['title'] }}</h4>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <div id="errorCreate" class="mb-3" style="display:none;">
                  <div class="alert alert-danger" role="alert">
                    <div class="alert-icon"><i class="flaticon-danger text-danger"></i></div>
                    <div class="alert-text">
                    </div>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="select2Role">Hero :</label>
                  <div class="col-sm-9">
                    <select id="select2Hero" name="hero_id">
                      @if(isset($data->hero_id))
                        <option value="{{ $data->hero_id }}">{{ $data->hero->nama }}</option>
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="select2Role">Hero Musuh :</label>
                  <div class="col-sm-9">
                    <select id="select2HeroMusuh" name="hero_musuh_id">
                      @if(isset($data->hero_musuh_id))
                        <option value="{{ $data->hero_musuh_id }}">{{ $data->hero_musuh->nama }}</option>
                      @endif
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="select2Role">Tipe Build :</label>
                  <div class="col-sm-9">
                    <select name="tipe_build" class="form-select">
                      <option value="Physical" @selected(isset($data->tipe_build) && $data->tipe_build == "Damage")>
                        Damage
                      </option>
                      <option value="Magic" @selected(isset($data->tipe_build) && $data->tipe_build == "Magic")>Magic
                      </option>
                      <option value="Tank" @selected(isset($data->tipe_build) && $data->tipe_build == "Tank")>Tank
                      </option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-3">
                  <label class="control-label col-sm-3 align-self-center mb-0" for="select2Role">Emblem :</label>
                  <div class="col-sm-9">
                    <select name="emblem" class="form-select">
                      <option value="Tank">Tank</option>
                      <option value="Assassin">Assassin</option>
                      <option value="Mage">Mage</option>
                      <option value="Marksman">Marksman</option>
                    </select>
                  </div>
                </div>
                <div class="btn-group float-end" role="group" aria-label="Basic outlined example">
                  <button id="btnPrediksi" type="button" class="btn btn-sm btn-primary">Prediksi <i
                      class="fa-solid fa-floppy-disk"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="results" class="col-sm-12 col-lg-12">

        </div>
      </div>
  </div>
@endsection

@push('style')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
@endpush

@push('script')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function () {
      $('#select2Hero').select2({
        dropdownParent: $('#select2Hero').parent(),
        placeholder: "Cari Hero",
        width: '100%',
        allowClear: true,
        ajax: {
          url: "{{ route('panel.heroes.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      $('#select2HeroMusuh').select2({
        dropdownParent: $('#select2HeroMusuh').parent(),
        placeholder: "Cari Hero",
        width: '100%',
        allowClear: true,
        ajax: {
          url: "{{ route('panel.heroes.select2') }}",
          dataType: "json",
          cache: true,
          data: function (e) {
            return {
              q: e.term || '',
              page: e.page || 1
            }
          },
        },
      });

      $('#btnPrediksi').on('click', (e) => {
        e.preventDefault();
        let hero = $('#select2Hero').find(':selected').text() ?? null;
        let hero_musuh = $('#select2HeroMusuh').find(':selected').text() ?? null;
        let tipe_build = $('select[name="tipe_build"]').val() ?? null;
        let emblem = $('select[name="emblem"]').val() ?? null;

        if (!hero || !hero_musuh || !tipe_build) {
          toastr.error('Pastikan Semua Atribut Sudah Diisi', 'Failed !');
        }
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: '{{ route('panel.naive-bayes.prediksi') }}',
          data: {
            hero: hero,
            hero_musuh: hero_musuh,
            tipe_build: tipe_build,
            emblem: emblem,
          },
          method: 'POST',
          dataType: 'json',
          success: (response) => {
            $('#results').empty().append(response);
          },
          error: (xhr, status, error) => {
            toastr.error('Gagal Menyimpan Data', 'Failed !');
          }
        });
      });

    });
  </script>
@endpush
