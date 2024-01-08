<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Interfaces\TicketInterface;
use App\Models\Answer;
use App\Models\Examination;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ResponseStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ExaminationTicketController extends Controller
{
  use ResponseStatus;

  private TicketInterface $ticketRepository;

  function __construct(TicketInterface $ticketRepository)
  {
    $this->middleware('can:examinations-list', ['only' => ['index', 'show']]);
    $this->middleware('can:examinations-create', ['only' => ['create', 'store']]);
    $this->middleware('can:examinations-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:examinations-delete', ['only' => ['destroy']]);
    $this->ticketRepository = $ticketRepository;
  }

  public function index(Request $request, Examination $examination)
  {
    $config['title'] = "Tiket";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Tiket"],
    ];

    if ($request->ajax()) {
      $data = Ticket::where('examination_id', $examination['id'])
        ->with(['owner', 'participant']);

      return DataTables::of($data)
        ->make(true);
    }

    return view('panel.examinations.tickets.index', compact('config', 'examination'));
  }

  public function create(Examination $examination)
  {
    $config['title'] = "Tambah Sesi Naskah Soal";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Sesi Naskah Soal"],
      ['url' => '#', 'title' => "Tambah Sesi Naskah Soal"],
    ];
    $config['form'] = (object)[
      'method' => 'POST',
      'action' => route('panel.examinations.tickets.store', $examination['id'])
    ];

    return view('panel.examinations.tickets.form', compact('config', 'examination'));
  }

  public function store(Request $request, Examination $examination)
  {
    $validator = Validator::make($request->all(), [
      'qty' => 'required|numeric'
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        $this->ticketRepository->store($request['qty'], $examination);
        DB::commit();
        $response = response()->json($this->responseStore(true, null, route('panel.examinations.tickets.index', $examination['id'])));
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

  public function edit(Examination $examination, Ticket $ticket)
  {
    $config['title'] = "Edit Tiket";
    $config['breadcrumbs'] = [
      ['url' => route('panel.examinations.index'), 'title' => "Tiket"],
      ['url' => '#', 'title' => "Edit Tiket"],
    ];

    $config['form'] = (object)[
      'method' => 'PUT',
      'action' => route('panel.examinations.tickets.update', [
        'examination' => $examination['id'],
        'ticket' => $ticket['id'],
      ])
    ];

    return view('panel.examinations.tickets.form-edit', compact('config', 'examination', 'ticket'));
  }

  public function update(Request $request, Examination $examination, Ticket $ticket)
  {
    $validator = Validator::make($request->all(), [
      'owner_id' => 'nullable'
    ]);

    if ($validator->passes()) {
      DB::beginTransaction();
      try {
        if (!empty($ticket->participant->id)) {
          return response()->json($this->responseUpdate(false, '', 'Tiket Sudah Digunakan'));
        }

        $ticket->owner_id = isset($validator->safe()['owner_id']) ? $validator->safe()['owner_id'] : null;
        $ticket->save();

        DB::commit();
        $response = response()->json($this->responseUpdate(true, route('panel.examinations.tickets.index', $examination['id'])));
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

  public function destroy(Request $request, Examination $examination, Ticket $ticket)
  {
    DB::beginTransaction();
    try {
      if (empty($ticket->participant->id)) {
        $ticket->delete();
        $response = response()->json($this->responseDelete(true));
      } else {
        $response = response()->json($this->responseDelete(false, 'reload', 'Tidak bisa dihapus karena sudah digunakan'));
      }
      DB::commit();
    } catch (\Throwable $throw) {
      Log::error($throw);
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

  public function enroll(Request $request, Examination $examination, Ticket $ticket)
  {
    if (!isset($ticket->owner->id)) {
      return response()->api([
        'global_message' => ['Tiket ini belum ada pemiliknya']
      ], 400);
    }

    DB::beginTransaction();
    try {
      $examination_sections = $examination->sections()->orderBy('number', 'asc')->get();
      foreach ($examination_sections as $section) {
        $questions = $section->script->questions();
        if ($section->sorting_mode == 'Random') {
          $questions = $questions->inRandomOrder();
        } else {
          $questions = $questions->orderBy('number', 'asc');
        }
        $section->questions = $questions->get();
      }
      $user = User::find($ticket->owner_id);

      $participant = new Participant;
      $participant->ticket_id = $ticket->id;
      $participant->user_id = $ticket->owner_id;
      $participant->examination_id = $ticket->examination_id;
      $participant->age = Carbon::parse($user->birthday)->age;

      if ($participant->save()) {
        foreach ($examination_sections as $section) {
          $participant_section = new ParticipantSection;
          $participant_section->section_id = $section->id;
          $participant_section->participant_id = $participant->id;
          $participant_section->save();
          $number = 0;
          $answers = [];
          foreach ($section->questions as $question) {
            $number++;
            $answers[] = new Answer([
              'question_id' => $question->id, 'number' => $number
            ]);
          }
          $participant_section->answers()->saveMany($answers);
        }
      }
      $ticket->refresh();
      DB::commit();
      $response = response()->json($this->responseUpdate(true, route('panel.examinations.tickets.index', $examination['id'])));
    } catch (\Throwable $throw) {
      Log::error($throw);
      DB::rollBack();
      $response = response()->json(['error' => $throw->getMessage()]);
    }
    return $response;
  }

}
