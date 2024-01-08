<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
  use HasFactory;

  protected $fillable = [
    'participant_section_id',
    'question_id',
    'number',
    'choice_id',
    'duration_used'
  ];

  public function question()
  {
    return $this->hasOne(Question::class, 'id', 'question_id');
  }

  public function choiced()
  {
    return $this->hasOne(Choice::class, 'id', 'choice_id');
  }

  public function answered_essay()
  {
    return $this->hasOne(AnswerChoice::class);
  }

  public function answered_choice()
  {
    return $this->hasMany(AnswerChoice::class);
  }

  public function points()
  {
    return $this->hasMany(VPoints::class);
  }
}
