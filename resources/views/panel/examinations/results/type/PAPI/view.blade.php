<style>
  @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,500;1,400;1,500&display=swap');
</style>
<div class="card">
  <div class="card-header justify-content-between">
    <div class="header-title">
      <h3 class="text-center">Laporan Hasil Pemeriksaan Psikologi</h3>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12">
        <table class="w-100">
          <tbody>
          <tr>
            <td style="width: 130px; font-weight: bold">Nama Lengkap</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td style="padding-left:10px; font-weight: bold">{{ $result->user->fullname }}</td>
          </tr>
          <tr>
            <td style="width: 130px; font-weight: bold">Tanggal Lahir</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->user->birthday ?? ''  }}</td>
          </tr>
          <tr>
            <td style="width: 130px; font-weight: bold">Jenis Kelamin</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->user->sex == 'Male' ? 'Laki-laki' : 'Perempuan' }}</td>
          </tr>
          <tr>
            <td style="width: 130px; font-weight: bold">Usia</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td style="padding-left:10px; font-weight: bold">{{ $result->user->age }}</td>
          </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-between mt-4">
          <h4>IQ {{ $result->user->fullname }}</h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="d-flex justify-content-center">
          <div id="chart"></div>
        </div>
      </div>
      <div class="col-md-12">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Aspek</th>
            <th class="text-center">Nilai</th>
            <th>Keterangan</th>
          </tr>
          </thead>
          <tbody>
          @foreach($result->results ?? [] as $item)
            <tr>
              <td colspan="2"
                  style="background: #0a58ca; color: white; padding: 0 10px; font-size: 20px; font-family: 'Roboto', 'sans-serif'; font-style: italic ">{{ $item->group }}</td>
              <td
                style="background: #0a58ca; color: white; padding: 0 10px; font-size: 20px; font-family: 'Roboto', 'sans-serif'; font-style: italic;">
                <div class="d-flex justify-content-end mt-1">
                  {!! $item->result !!}
                </div>
              </td>
            </tr>
            @foreach($item->items ?? [] as $itemColumn)
              <tr>
                <td>{{ $itemColumn->name }}
                  - {{ $itemColumn->original_title  }}</td>
                <td class="text-center">{{ $itemColumn->value }}</td>
                <td>{{ ucwords($itemColumn->norma) }}</td>
              </tr>
            @endforeach
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="col-md-12">
        <h2 class="mt-2">Lembar Jawaban</h2>
        <hr>
        @php $chunkedData = array_chunk($result->answers, 5) @endphp
        <div class="d-flex flex-wrap">
          @foreach($chunkedData as $itemChunk)
            <table class="borderAnswer">
              @foreach ($itemChunk as $itemAnswer)
                <tr>
                  <td>{{ $itemAnswer->number }}</td>
                  <td>{{ $itemAnswer->answers }}</td>
                </tr>
              @endforeach
            </table>
          @endforeach
        </div>
      </div>
      <div class="col-md-12 my-3">
        <h3 class="text-center mt-4">SKOR EKSTRIM</h3>
        <table class="table table-striped">
          <tbody>
          @foreach($result->skor_ekstrim as $group => $item)
            <tr>
              <td colspan="2"
                  style="background: #c51047; color: white; padding: 0 10px; font-size: 20px; font-family: 'Roboto', 'sans-serif'; font-style: italic ">{{ $group }}</td>
            </tr>
            @foreach($item as $itemColumn)
              <tr>
                <td rowspan="2" style="width: 400px">{{ $itemColumn->name }} - {{ $itemColumn->original_title }}</td>
                <td>{{ ucwords($itemColumn->plus) }}</td>
              </tr>
              <tr>
                <td>{{ ucwords($itemColumn->minus) }}</td>
              </tr>
            @endforeach
          @endforeach
          </tbody>
        </table>
      </div>
      <div class="col-md-12 my-3">
        <h3 class="text-center">AREA PENGEMBANGAN</h3>
        <h5 class="text-center">{{ $result->area_pengembangan ?? '' }}</h5>
      </div>
      <div class="col-md-12 my-3">
        <h3 class="text-center">CIRI BERDEKATAN</h3>
        <h5 class="text-center">{!! $result->ciri_berdekatan ?? '' !!}</h5>
      </div>
    </div>
  </div>
</div>
<style>
  .borderAnswer, #tableNorma {
    border-collapse: collapse;
  }

  .borderAnswer table, .borderAnswer th, .borderAnswer td,
  #tableNorma table, #tableNorma th, #tableNorma td {
    border: 1px solid black;
    padding: 8px;
  }

  .borderAnswer:not(:first-child) td {
    border-left: none !important;
  }
</style>
