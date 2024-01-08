<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model {
    use HasFactory;

    protected $fillable = [
      'id',
      'key',
      'content',
      'score',
      'question_id'
    ];

  public function scores(){
    return $this->hasMany(Score::class);
  }

}
