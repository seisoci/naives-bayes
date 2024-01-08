<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionDiscussion extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
      'script_id',
      'question_id',
      'content',
    ];
}
