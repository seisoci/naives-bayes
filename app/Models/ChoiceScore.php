<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChoiceScore extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'choice_score';

    protected $fillable = [
      'score_id',
      'choice_id'
    ];

    public function choice(){
      return $this->belongsTo(Choice::class);
    }
}
