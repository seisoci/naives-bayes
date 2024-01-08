<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normalization extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
      'type',
      'iq',
      'value',
      'value_converter',
      'min',
      'max',
      'description',
      'description_2',
      'description_3',
    ];

}
