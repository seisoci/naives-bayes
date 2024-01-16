<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaiveBayes extends Model
{
  use HasFactory;

  protected $fillable = [
    'hero_id',
    'hero_musuh_id',
    'tipe_build',
    'emblem',
    'hasil'
  ];

  public function hero(){
    return $this->belongsTo(Hero::class, 'hero_id');
  }

  public function hero_musuh(){
    return $this->belongsTo(Hero::class, 'hero_musuh_id');
  }
}
