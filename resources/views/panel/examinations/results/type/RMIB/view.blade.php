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
      </div>
      <div class="col-md-12">
        <table class="w-100 d-flex justify-content-end">
          <tbody>
          <tr>
            <td class="text-center" style="width: 400px; font-weight: bold">Intelligenz Struktur Test {{ $result->user->exam_name ?? '-' }}</td>
          </tr>
          <tr>
            <td class="text-center" style="width: 400px; font-weight: bold">{{ isset($result->user->date_finish) ? \Carbon\Carbon::parse($result->user->date_finish)->isoFormat('DD MMMM YYYY') : '-' }}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 my-4">
        <h4>Laporan Skoring Tes Minat Bakat</h4>
      </div>
      <div class="col-md-12 px-4">
        <table class="table table-border">
          <thead>
          <tr>
            <th class="text-center">KATEGORI</th>
            <th class="text-center">A</th>
            <th class="text-center">B</th>
            <th class="text-center">C</th>
            <th class="text-center">D</th>
            <th class="text-center">E</th>
            <th class="text-center">F</th>
            <th class="text-center">G</th>
            <th class="text-center">H</th>
            <th class="text-center">Total</th>
            <th class="text-center">Rank</th>
          </tr>
          </thead>
          <tbody>
          @foreach($result->results->answers ?? [] as $item)
            <tr>
              @foreach($item as $itemChild)
                <td class="text-center"
                    style="{{ in_array($loop->index, [0, 9,10]) ? 'background:#338a71;color:white;' : '' }}">{{ $itemChild }}</td>
              @endforeach
            </tr>
          @endforeach
          </tbody>
          <tfoot>
          <tr>
            <th class="text-center" colspan="9">Total</th>
            <th class="text-center">{{ $result->results->total_points }}</th>
            <th class="text-center"></th>
          </tr>
          </tfoot>
        </table>
      </div>
      <div class="col-md-12 my-4">
        <h4 class="mt-2">Deskripsi Hasil Berdasar Ranking</h4>
        <table class="table table-bordered">
          <thead>
          <tr>
            <th class="text-center">Ranking</th>
            <th class="text-center">Deskripsi</th>
          </tr>
          </thead>
          <tbody>
        @foreach($result->interpretasi ?? [] as $item)
          <tr>
            <td class="text-center">
              <h2 style="color: #338a71" class="fw-bold">{{ $loop->iteration }}</h2>
              <h6 style="color: #338a71" class="fw-bold">{{ $item->type ?? '' }}</h6>
            </td>
            <td>
              <div style="white-space: pre-wrap">{{ $item->deskripsi }}<br><br><b>Jurusan : {{ $item->jurusan }}</b></div>
            </td>
          </tr>
        @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<style>
  .table tr th {
    background: #338a71;
    color: white;
  }

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
