<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerChoice extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'answer_id',
    'choice_id',
    'answer'
  ];


  public function choice(){
    return $this->belongsTo(Choice::class);
  }
}
