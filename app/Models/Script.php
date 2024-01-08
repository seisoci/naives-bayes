<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
  use HasFactory;

  protected $fillable = [
    'category_id',
    'sub_category_id',
    'example_id',
    'title',
    'description',
    'duration',
    'type',
    'example_question',
  ];

  public function example()
  {
    return $this->belongsTo(Script::class, 'example_id');
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function subcategory()
  {
    return $this->belongsTo(SubCategory::class, 'sub_category_id');
  }

  public function questions()
  {
    return $this->hasMany(Question::class);
  }

  public function choices()
  {
    return $this->hasManyThrough(Choice::class, Question::class);
  }

  public function introduction(){
    return $this->hasOne(ScriptIntroduction::class);
  }

  public function highest_choices()
  {
    return $this->hasOneThrough(Choice::class, Question::class)->ofMany('score', 'max');
  }

  public function scopeExample($query, $value)
  {
    return $query->where('example_question', $value);
  }

  protected function serializeDate(\DateTimeInterface $date)
  {
    return $date->isoFormat('DD MMM YYYY');
  }
}
