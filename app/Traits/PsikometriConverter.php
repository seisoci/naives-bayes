<?php

namespace App\Traits;

use App\Models\Normalization;

trait PsikometriConverter
{

  public function psikometriIST($value, $output = null)
  {
    $psikometriConverter = Normalization::where('type', 'IST-PSIKOMETRI')->get();
    $result = 'error';
    foreach ($psikometriConverter as $item):
      if ($item['min'] <= $value && $item['max'] >= $value):
        $result = $output ? $item[$output] : $item['value_converter'];
      endif;
    endforeach;
    return $result;
  }


}
