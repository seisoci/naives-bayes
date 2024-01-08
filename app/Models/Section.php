<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

  public function script()
  {
    return $this->belongsTo(Script::class)->withCount('questions');
  }

  public function example()
  {
    return $this->hasManyThrough(QuestionDiscussion::class, Script::class, 'id', 'script_id', 'tes_3', '');
  }

  public function category(){
    return $this->belongsTo(Category::class);
  }

  public function subcategory(){
    return $this->belongsTo(SubCategory::class);
  }

}
