<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\AnswerChoice;
use App\Models\Examination;
use App\Models\Participant;
use App\Models\ParticipantResult;
use App\Models\ParticipantSection;
use App\Models\User;
use App\Traits\ResponseStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExaminationParticipantController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:examinations-list', ['only' => ['index', 'show']]);
    $this->middleware('can:examinations-create', ['only' => ['create', 'store']]);
    $this->middleware('can:examinations-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:examinations-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request, Examination $examination)
  {
    $config['title'] = "Tiket";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Tiket"],
    ];

    if ($request->ajax()) {
      $data = Participant::where('examination_id', $examination->id)
        ->with(['user', 'ticket'])
        ->select('participants.*');

      return DataTables::of($data)
        ->make(true);
    }

    return view('panel.examinations.participants.index', compact('config', 'examination'));
  }

  public function create(Examination $examination)
  {
    $config['title'] = "Tambah Peserta";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Peserta"],
      ['url' => '#', 'title' => "Tambah Peserta"],
    ];

    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.examinations.participants.store', $examination['id'])
    ];

    return view('panel.examinations.participants.form', compact('config', 'examination'));
  }

  public function store(Request $request, Examination $examination)
  {
    $validator = Validator::make($request->all(), [
      'users' => 'required|array|min:1'
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $examination->load('sections.script');
        $data = $validator->safe();
        $participants = $data['users'];
        $participants_count = collect($participants)->count();
        $tickets = $examination->tickets()->where('owner_id', NULL)->limit($participants_count)->get();
        $tickets_count = $tickets->count();

        if ($participants_count > $tickets_count) {
          return response()->api(['users' => ["Jumlah tiket tidak cukup."]], 400);
        }
//        if($examination->category_id == 6){
//          foreach ()
//            endforeach
//          foreach ($examination->sections as $index => $section) {
//
////          if($section->script->sub_category_id == 10 && ){}
//
//            $questions = $section->script->questions()->select('id', 'script_id', 'heading', 'number');
//            if ($section->sorting_mode == 'Random') {
//              $questions->orderBy('heading');
//            }
//            $questions = $questions->orderBy('number')->get();
//            $grouped = $questions->groupBy('heading');
//            $section->question_groups = $grouped->values()->all();
//          }
//        }else{
//
//
//        }


        foreach ($examination->sections as $index => $section) {


          $questions = $section->script->questions()->select('id', 'script_id', 'heading', 'number');
          if ($section->sorting_mode == 'Random') {
            $questions->orderBy('heading');
          }
          $questions = $questions->orderBy('number')->get();
          $grouped = $questions->groupBy('heading');
          $section->question_groups = $grouped->values()->all();
        }





        $index = 0;
        foreach ($tickets as $ticket) {
          $ticket->owner_id = $participants[$index];
          if ($ticket->save()) {
            $user = User::find($ticket->owner_id);
            $participant = new Participant;
            $participant->ticket_id = $ticket->id;
            $participant->user_id = $ticket->owner_id;
            $participant->examination_id = $ticket->examination_id;
            $participant->age = Carbon::parse($user->birthday)->age;

            if ($participant->save()) {
              foreach ($examination->sections as $section) {
                if($examination->category_id == 6 && $section->script->sub_category_id == 10 && $user->sex != 'Male'){
                  continue;
                }

                if($examination->category_id == 6 && $section->script->sub_category_id == 11 && $user->sex != 'Female'){
                  continue;
                }

                $participant_section = new ParticipantSection;
                $participant_section->section_id = $section->id;
                $participant_section->participant_id = $participant->id;
                $participant_section->save();

                $number = 0;
                $answers = [];
                foreach ($section->question_groups as $collections) {
                  $questions = $collections;
                  if ($section->sorting_mode == 'Random') {
                    $questions = $questions->shuffle()->all();
                  }
                  foreach ($questions as $question) {
//                    $number++;
                    $answers[] = new Answer([
                      'question_id' => $question->id, 'number' => $question->number
                    ]);
                  }
                }
                $participant_section->answers()->saveMany($answers);
              }
            }
          }
          $index++;
        }
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.examinations.participants.index', $examination['id'])));
      } catch (\Throwable $throw) {
        Log::error($throw);
        DB::rollBack();
        $response = response()->json(['error' => $throw->getMessage()]);
      }
    } else {
      $response = response()->json(['error' => $validator->errors()->all()]);
    }
    return $response;
  }

  public function destroy(Request $request, Examination $examination, Participant $participant)
  {
    $response = response()->json($this->responseDelete(false));
    DB::beginTransaction();
    try {
      $participant->answers()->delete();
      $participant->participant_sections()->delete();
      $participant->delete();
      $response = response()->json($this->responseDelete(true));
      DB::commit();
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

  public function resetAnswers($examination_id, $id)
  {
    DB::beginTransaction();
    try {
      $participantSection = ParticipantSection::where('participant_id', $id)->get();
      ParticipantResult::where('participant_id', $id)->delete();
      foreach ($participantSection as $item):
        $ps = ParticipantSection::find($item['id']);
        $ps->update([
          'v_demo' => 0,
          'v_introduction' => 0,
          'status' => 'Ready',
          'duration_used' => 0,
          'start_at' => NULL,
        ]);

        $answers = Answer::where('participant_section_id', $item['id'])->pluck('id');
        AnswerChoice::whereIn('answer_id', $answers ?? [])->delete();

      endforeach;
      DB::commit();
      $response = response()->json($this->responseUpdate(true, '', 'Jawaban Berhasil Direset'));
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

}
