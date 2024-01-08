<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScriptIntroduction extends Model
{
    use HasFactory;

    protected $fillable = [
      'script_id',
      'description',
      'duration'
    ];
}
