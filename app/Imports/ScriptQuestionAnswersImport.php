<?php

namespace App\Imports;

use App\Models\Choice;
use App\Models\ChoiceScore;
use App\Models\Question;
use App\Models\Score;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;

class ScriptQuestionAnswersImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
  protected $script_id;

  public function __construct(int $script_id)
  {
    $this->script_id = $script_id;
  }

  public function sheets(): array
  {
    return [
      1 => new ScriptQuestionAnswersImport($this->script_id),
    ];
  }

  public function onRow(Row $row)
  {
    $scoreColumns = ['question_id', 'value', 'alias'];
    $scoreData = array_slice(array_values($row->toArray()), 0, 3);

    $choicesData = array_slice(array_values($row->toArray()), 3);

    $score = new Score;
    $question = NULL;
    foreach ($scoreData ?? [] as $index => $item):
      if ($index == 0) {
        $question = Question::where([['script_id', $this->script_id], ['number', $item]])->first();
        $score->{$scoreColumns[$index]} = $question->id;
      }

      if ($index != 0) {
        $score->{$scoreColumns[$index]} = $item;
      }
    endforeach;
    $score->save();

    $choiceScore = [];
    $choiceKey = explode(",", strtoupper($choicesData[0]));
    foreach ($choiceKey ?? [] as $item):
      $choice['choice_id'] = Choice::where([['question_id', $question->id], ['key', trim($item)]])->first()->id;
      $choiceScore[] = new ChoiceScore($choice);
    endforeach;

    $score->choicesscore()->saveMany($choiceScore);
  }

}
