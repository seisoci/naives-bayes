<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
  use HasFactory;

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function sections()
  {
    return $this->hasMany(Section::class);
  }

  public function tickets()
  {
    return $this->hasMany(Ticket::class);
  }

  public function participants()
  {
    return $this->hasMany(Participant::class);
  }

  public function scopeOwner($query)
  {

    $query->whereRelation('participants', 'user_id', auth()->id());
  }

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMM YYYY');
  }
}
