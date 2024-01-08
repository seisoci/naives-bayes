<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ScriptQuestionExport implements FromArray {
  protected $questions;

  public function __construct(array $questions) {
    $this->questions = $questions;
  }

  public function array(): array {
    return $this->questions;
  }
}
