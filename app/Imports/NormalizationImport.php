<?php

namespace App\Imports;

use App\Models\Normalization;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class NormalizationImport implements ToModel, WithStartRow
{

  public function model(array $row)
  {
    $exists = Normalization::where([
      ['type', $row[0]],
      ['min', $row[1]],
      ['max', $row[2]],
      ['value', $row[4]],
    ]);
    if(in_array($row[0], ['PAPI-PENGEMBANGAN'])){
      $exists->where('value_converter', $row[5]);
    }

    if ($exists->first()) {
      $exists->update([
        'type' => $row[0],
        'min' => $row[1],
        'max' => $row[2],
        'iq' => $row[3],
        'value' => trim($row[4]),
        'value_converter' => trim($row[5]),
        'description' => $row[6],
        'description_2' => $row[7],
        'description_3' => $row[8],
      ]);
      return null;
    }else{
      return new Normalization([
        'type' => $row[0],
        'min' => $row[1],
        'max' => $row[2],
        'iq' => $row[3],
        'value' => trim($row[4]),
        'value_converter' => trim($row[5]),
        'description' => $row[6],
        'description_2' => $row[7],
        'description_3' => $row[8],
      ]);
    }
  }

  public function startRow(): int
  {
    return 2;
  }
}
