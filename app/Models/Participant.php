<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
  use HasFactory;

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function ticket()
  {
    return $this->belongsTo(Ticket::class);
  }

  public function examination()
  {
    return $this->belongsTo(Examination::class);
  }

  public function participant_sections()
  {
    return $this->hasMany(ParticipantSection::class)->with('section');
  }

  public function section()
  {
    return $this->belongsToMany(Section::class, 'participant_sections');
  }

  public function answers() {
    return $this->hasManyThrough(Answer::class, ParticipantSection::class);
  }

  public function result(){
    return $this->hasOne(ParticipantResult::class, 'participant_id');
  }

  public function scopeOwner($query)
  {
    $query->where('user_id', auth()->id());
  }
}
