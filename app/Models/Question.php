<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Question\ChoiceQuestion;


class Question extends Model
{
  use HasFactory;


  public function discussion()
  {
    return $this->hasOne(QuestionDiscussion::class);
  }

  public function choices()
  {
    return $this->hasMany(Choice::class);
  }

  public function scores(){
    return $this->hasMany(Score::class);
  }

  public function correct(){

      return $this->hasManyThrough(ChoiceScore::class, Score::class);

  }
}
