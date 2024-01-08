<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NormalizationExport implements WithHeadings
{
  public function headings(): array
  {
    return [
      'Jenis Tabel Normalisasi',
      'Range Min',
      'Range Max',
      'IQ',
      'Nilai',
      'Nilai Conversi',
      'Deskripsi'
    ];
  }
}
