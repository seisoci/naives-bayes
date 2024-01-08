<?php

namespace App\Imports;

use App\Models\Choice;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;

class ScriptQuestionImport implements OnEachRow, WithHeadingRow, WithMultipleSheets
{
    protected $script_id;

    public function __construct(int $script_id)
    {
        $this->script_id = $script_id;
    }

    public function sheets(): array
    {
        return [
            0 => new ScriptQuestionImport($this->script_id),
        ];
    }

    public function onRow(Row $row)
    {
        $question_columns = ['number', 'heading', 'sentence']; //, 'duration'];
        $question_data = array_slice(array_values($row->toArray()), 0, 3);
        $choice_columns = ['key', 'content'];
        $choices_data = array_slice(array_values($row->toArray()), 3);
        $question = new Question;
        $question->script_id = $this->script_id;
        foreach ($question_data as $index => $value) {
            $question->{$question_columns[$index]} = $value ?? '';
        }

        $question->save();
        $choices = [];
        $keys = range('a', 'z');
        $key_index = $column_number = 0;

        foreach ($choices_data as $index => $value) {
            if (!isset($value)) {
                continue;
            }

            $column_number++;
            $choice[$choice_columns[$column_number]] = $value;
            $choice = array_merge($choice, ['key' => strtoupper($keys[$key_index])]);
            $choices[] = new Choice($choice);

            $column_number = 0;
            $key_index++;
        }
        $question->choices()->saveMany($choices);

    }
}
