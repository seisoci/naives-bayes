<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;
    public $timestamps =  false;

    protected $fillable = [
      'question_id',
      'value'
    ];

    public function choices(){
      return $this->belongsToMany(Choice::class);
    }

    public function choicesscore(){
      return $this->hasMany(ChoiceScore::class);
    }
}
