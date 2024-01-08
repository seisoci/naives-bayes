@extends('layouts.master')

@section('content')
  <div class="card">
    <div class="card-header justify-content-between">
      <div class="header-title">
        <div class="row">
          <div class="col-sm-6 col-lg-6">
            <h4 class="card-title">{{ $config['title'] ?? '' }}</h4>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="accordion custom-accordionwithicon accordion-flush accordion-fill-secondary" id="accordionFill2">
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionFill2Example1">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accor_fill21"
                    aria-expanded="true" aria-controls="accor_fill21">
              IST
            </button>
          </h2>
          <div id="accor_fill21" class="accordion-collapse collapse" aria-labelledby="accordionFill2Example1"
               data-bs-parent="#accordionFill2">
            <div class="accordion-body">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Jenis Tabel Normalisasi (Col 1)</th>
                  <th>Range Min (Col 2)</th>
                  <th>Range Max (Col 3)</th>
                  <th>IQ (Col 4)</th>
                  <th>Nilai (Col 5)</th>
                  <th>Nilai Konversi (Col 6)</th>
                  <th>Deskripsi (Col 7)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>IST-SE (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>20 (RW)</td>
                  <td>134 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-WA (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>76 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-AN (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>85 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-GE (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>74 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-ME (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>85 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-RA (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>79 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-ZR (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>84 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-FA (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>82 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-WU (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>0 (RW)</td>
                  <td>81 (SW)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-GESMAT (Kode Norma)</td>
                  <td>51 (Usia Min)</td>
                  <td>60 (Usia Max)</td>
                  <td></td>
                  <td>51-60 (Range GESMAT)</td>
                  <td>87 (Hasil GESMAT)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-GE-CONVERSION (Kode Norma)</td>
                  <td>1 (Range Min)</td>
                  <td>2 (Range Max)</td>
                  <td></td>
                  <td>1 (Nilai)</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-IQ (Kode Norma)</td>
                  <td></td>
                  <td></td>
                  <td>160 (IQ)</td>
                  <td>140 (Gesamt SW)</td>
                  <td>100 (Persen)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-PSIKOMETRI (Kode Norma)</td>
                  <td>0 (Range Min)</td>
                  <td>89 (Range Max)</td>
                  <td></td>
                  <td>1</td>
                  <td>Kurang Sekali (Deskripsi)</td>
                  <td></td>
                </tr>
                <tr>
                  <td>IST-IQ-PSIKOMETRI (Kode Norma)</td>
                  <td>60 (Range Min)</td>
                  <td>69 (Range Max)</td>
                  <td></td>
                  <td></td>
                  <td>Sangat Redah (Deskripsi)</td>
                  <td></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionFill2Example2">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#accor_fill22" aria-expanded="false" aria-controls="accor_fill22">
              PAPI KOSTICK
            </button>
          </h2>
          <div id="accor_fill22" class="accordion-collapse collapse" aria-labelledby="accordionFill2Example1"
               data-bs-parent="#accordionFill2">
            <div class="accordion-body">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Jenis Tabel Normalisasi (Col 1)</th>
                  <th>Range Min (Col 2)</th>
                  <th>Range Max (Col 3)</th>
                  <th>IQ (Col 4)</th>
                  <th>Nilai (Col 5)</th>
                  <th>Nilai Konversi (Col 6)</th>
                  <th>Deskripsi (Col 7)</th>
                  <th>Deskripsi 2 (Col 8)</th>
                  <th>Deskripsi 3 (Col 9)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>PAPI (Kode Norma)</td>
                  <td>0 (Range Min)</td>
                  <td>4 (Range Max)</td>
                  <td></td>
                  <td>L (Papi Alfabet)</td>
                  <td>Cenderung kurang aktif dalam mengambil peran pemimpin dalam bekerja (Deskripsi)</td>
                  <td>Kepemimpinan (Jurusan)</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>PAPI-EKSTRIM (Kode Norma)</td>
                  <td>0 (Range Min)</td>
                  <td>2 (Range Max)</td>
                  <td></td>
                  <td>L (Papi Alfabet)</td>
                  <td>(+) Tidak terlalu kompetitif dan puas menjadi anggota. (Deskripsi +)</td>
                  <td>(-) Tidak dominan dan ia tidak melihat dirinya sebagai pemimpin. (Deskripsi -)</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>PAPI-PENGEMBANGAN (Kode Norma)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>KEPEMIMPINAN </td>
                  <td>L (Papi Alfabet)</td>
                  <td>Ragu-ragu dalam mengambil keputusan dan kurang mampu bertanggung jawab pada tugas maupun anggota (Deskripsi)</td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>PAPI-AREABERDEKATAN (Kode Norma)</td>
                  <td>1 (Range Min)</td>
                  <td>4 (Range Max)</td>
                  <td></td>
                  <td>L,P</td>
                  <td></td>
                  <td>Kurang dapat diandalkan jika menjadi pemimpin karena ragu-ragu dalam mengambil keputasan dan kurang dapat bertanggung jawab (Deskripsi)</td>
                  <td></td>
                  <td></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="accordionFill2Example3">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#accor_fill23" aria-expanded="false" aria-controls="accor_fill23">
              RMIB
            </button>
          </h2>
          <div id="accor_fill23" class="accordion-collapse collapse" aria-labelledby="accordionFill2Example1"
               data-bs-parent="#accordionFill2">
            <div class="accordion-body">
              <table class="table table-striped">
                <thead>
                <tr>
                  <th>Jenis Tabel Normalisasi (Col 1)</th>
                  <th>Range Min (Col 2)</th>
                  <th>Range Max (Col 3)</th>
                  <th>IQ (Col 4)</th>
                  <th>Nilai (Col 5)</th>
                  <th>Nilai Konversi (Col 6)</th>
                  <th>Deskripsi (Col 7)</th>
                  <th>Deskripsi 2 (Col 8)</th>
                  <th>Deskripsi 3 (Col 9)</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td>RMIB (Kode Norma)</td>
                  <td>1 (Urutan)</td>
                  <td></td>
                  <td>OUTDOORS (Nama Urutan)</td>
                  <td>Laki-Laki (Jenis Kelamin: Laki-Laki, Perempuan)</td>
                  <td>Berdasarkan hasil tes Psikologi saudara {nama} (Deskripsi)</td>
                  <td>Teknik Pertanian, Pertambangan, Penjaskes, Teknik Geodesi, Meteorologi,  (Jurusan)</td>
                  <td>POLTEKIM (Politeknik Imigrasi), POLTEKIP (Politeknik Pemasyarakatan) (Deskripsi IQ 95 - 115)</td>
                  <td>POLTEKIM (Politeknik Imigrasi), POLTEKIP (Politeknik Pemasyarakatan) (Deskripsi IQ > 115)</td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
