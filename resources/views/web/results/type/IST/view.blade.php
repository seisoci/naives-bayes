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
          <h4>{{ $result->total_sw }}</h4>
        </div>
        <canvas id="myChart" height="100px"></canvas>
      </div>
      <div class="col-md-12">
        <table id="tableNorma" class="w-100">
          <thead>
          <tr>
            <th></th>
            @foreach($result->table as $key => $item)
              <th class="text-center">{{ $key }}</th>
            @endforeach
            <th>-</th>
          </tr>
          <tr>
            <th class="text-start">RW</th>
            @foreach($result->table as $key => $item)
              <th class="text-center">{{ $item->score_rw }}</th>
            @endforeach
            <th class="text-center">{{ $result->total_rw }}</th>
          </tr>
          <tr>
            <th class="text-start">SW</th>
            @foreach($result->table as $key => $item)
              <th class="text-center">{{ $item->score_sw }}</th>
            @endforeach
            <th class="text-center">{{ $result->total_sw }}</th>
          </tr>
          <tr>
            <th class="text-start">Kriteria</th>
            @foreach($result->table as $key => $item)
              <th class="text-center">{{ $item->kriteria }}</th>
            @endforeach
            <th class="text-center">-</th>
          </tr>
          </thead>
        </table>
        <div class="mt-4">
          <h5 class="text-center">Nilai IQ: {{ $result->total_iq }} <label class="text-danger">Rata - Rata (Nilai dan Deskripsi)</label></h5>
          <h5 class="text-center">{{ $result->psikogram->iq_psikometri }} <label class="text-danger">(Deskripsi Norma IQ)</label></h5>
        </div>
      </div>
    </div>
    <div class="row">
      @foreach($result->table as $key => $item)
        <h2 class="mt-2">Lembar Jawaban {{ $key }}</h2>
        <hr>
        @php $chunkedData = array_chunk($item->answers, 5) @endphp
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
      @endforeach
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
