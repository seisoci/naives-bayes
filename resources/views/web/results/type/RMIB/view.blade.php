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
        <h4>Kesimpulan Hasil Tes Minat Bakat</h4>
      </div>
      <div class="col-md-12 px-4">
        <h4 class="text-center fw-bold mb-4">Kesimpulan Hasil Tes Minat Bakat</h4>

        <ul>
          <li><h5>{{ isset($result->interpretasi[0]) ? $result->interpretasi[0]->deskripsi : '-' }}</h5></li>
          <li>
            <div>
              <h5>Berdasarkan minat dan keunggulan tersebut maka jurusan pendidikan yang sesuai adalah</h5>
              <h5>{{ isset($result->interpretasi[0]) ? $result->interpretasi[0]->jurusan : '-' }}</h5>
            </div>
          </li>
          <li>
            <div>
              <h5>Jika berminat ke sekolah kedinasan yang sesuai adalah :</h5>
              <h5>Nilai IQ 95 - 115: <br> {{ $result->interpretasi[0]->iq_1 ?? '-' }}</h5>
              <h5>Nilai IQ > 115: <br> {{ $result->interpretasi[0]->iq_2 ?? '-' }}</h5>
            </div>
          </li>
        </ul>
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
