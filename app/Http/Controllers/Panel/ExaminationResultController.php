<?php

namespace App\Http\Controllers\Panel;

use App\Helpers\ResultQuestion;
use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Normalization;
use App\Models\Participant;
use App\Models\ParticipantSection;
use App\Models\Section;
use App\Models\User;
use App\Models\VPoints;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExaminationResultController extends Controller
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
    $config['title'] = "Hasil Ujian";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Hasil Ujian"],
    ];

     $examination->loadSum('sections', 'duration');

    if ($request->ajax()) {
      $data = Participant::selectRaw('
          `participants`.`id` AS `participant_id`,
          `participants`.`user_id`,
          `participants`.`ticket_id`,
          `participants`.`examination_id`,
          `participants`.`date_finish`,
          `e`.*
        ')
        ->leftJoin('examinations AS e', 'e.id', '=', 'participants.examination_id')
        ->where('examination_id', $examination->id)
        ->with(['user', 'ticket', 'examination.category'])
        ->withSum('participant_sections', 'duration_used');

      return DataTables::of($data)
        ->make(true);
    }

    return view('panel.examinations.results.index', compact('config', 'examination'));
  }

  public function show(Request $request, Examination $examination, Participant $participant)
  {
    $config['title'] = "Detail Laporan";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Detail Laporan"],
    ];

    $resultQuestion = new ResultQuestion($participant->id);
    $result = $resultQuestion->view($request->type);

    return view('panel.examinations.results.result', compact('config', 'result'));
  }
}
