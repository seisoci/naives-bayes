<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

  protected $fillable = [
    'owner_id',
    'examination_id',
  ];

  public function examination()
  {
    return $this->belongsTo(Examination::class);
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'owner_id');
  }

  public function participant()
  {
    return $this->hasOne(Participant::class);
  }

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMM YYYY');
  }

}
