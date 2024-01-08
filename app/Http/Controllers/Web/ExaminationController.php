<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ResultQuestion;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
  public function index()
  {
    $paginator = Participant::selectRaw('
        `participants`.`id` AS `participant_id`,
        `e`.*
      ')
      ->leftJoin('examinations AS e', 'e.id', '=', 'participants.examination_id')
      ->owner()
      ->orderByDesc('participants.created_at')
      ->paginate(10);

    return view('web.examinations.index', compact('paginator'));
  }

  public function show(Request $request, Participant $participant)
  {
    $participant_section_active = $participant->participant_sections()->where('status', 'Running')->first();

    return view('web.examinations.show', compact('participant', 'participant_section_active'));
  }

  public function update(Request $request, Participant $participant)
  {
    $participant->date_finish = Carbon::now()->toDateTimeString();
    $resultQuestion = new ResultQuestion($participant->id);
    $result = $resultQuestion->reports();

    $participant->result()->delete();
    $participant->result()->create(['results' => json_encode($result)]);
    $participant->save();

    return response()->api($participant);
  }

}
