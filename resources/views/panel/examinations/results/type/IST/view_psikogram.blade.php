<div class="card">
  <div class="card-header justify-content-between">
    <div class="header-title">
      <h3 class="text-center">Psikogram Hasil Pemeriksaan Psikologi</h3>
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
              style="padding-left:10px; font-weight: bold">{{ $result->user->birthday?? ''  }}</td>
          </tr>
          <tr>
            <td style="width: 130px; font-weight: bold">Jenis Kelamin</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->user->sex== 'Male' ? 'Laki-laki' : 'Perempuan' }}</td>
          </tr>
          <tr>
            <td style="width: 130px; font-weight: bold">Usia</td>
            <td style="width: 1px; font-weight: bold">:</td>
            <td style="padding-left:10px; font-weight: bold">{{ $result->user->age}}</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-12 my-5">
        <h5 class="text-center">INTELEKTUAL (KECERDASAN)</h5>
        <table class="w-100">
          <tbody>
          <tr>
            <td style="width: 50%; font-weight: bold">Intelligent Quotein (IQ)</td>
            <td style="padding-left:10px; font-weight: bold">{{ $result->total_iq }} - {{ $result->psikogram->iq_psikometri }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Kemampuan Aritmatika</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->aritmatika ?? '' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Kemampuan Verbal</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->verbal ??'' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Kemampuan Analisa</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->analisa ?? '' }}</td>
          </tr>

          <tr>
            <td style="padding-top: 50px; width: 50%; font-weight: bold">Kemampuan Pengambilan Keputusan</td>
            <td
              style="padding-top: 50px; padding-left:10px; font-weight: bold">{{ $result->psikogram->pengambilan_keputusan ??'' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Kemampuan Berbahasa</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->berbahasa ?? '' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Kreatifitas</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->kreatifitas ?? '' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Berfikir Secara Komprehensif</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->komprehensif ?? '' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Keunggulan Berfikir Secara (Eksak/Fleksibel)</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->keunggulan ?? '' }}</td>
          </tr>
          <tr>
            <td style="width: 50%; font-weight: bold">Pola Berfikir</td>
            <td
              style="padding-left:10px; font-weight: bold">{{ $result->psikogram->pola_berifikir ?? '' }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
