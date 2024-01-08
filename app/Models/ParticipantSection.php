<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantSection extends Model
{
  use HasFactory;


    protected $fillable = [
    'v_demo',
    'v_introduction',
    'duration_used',
    'status',
  ];

  public function section()
  {
    return $this->belongsTo(Section::class);
  }

  public function examination()
  {
    return $this->hasOneThrough(Examination::class, Participant::class, 'id', 'id', 'participant_id',
      'examination_id');
  }

  public function answers()
  {
    return $this->hasMany(Answer::class)->orderBy('number');
  }

  public function current_answer()
  {
    return $this->hasOne(Answer::class, 'participant_section_id', 'id')->whereNull('choice_id')->orderBy('number');
  }

  public function participant()
  {
    return $this->belongsTo(Participant::class);
  }

  public function choices()
  {
    return $this->hasManyThrough(Choice::class, Answer::class, 'participant_section_id', 'id', 'id', 'choice_id');
  }
}
