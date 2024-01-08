<?php

namespace App\Http\Controllers\Web;

use App\Helpers\ResultQuestion;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ResultController extends Controller
{
  public function index(Request $request)
  {
    $config['title'] = 'Hasil Tes';

    if ($request->ajax()) {
      $data = Participant::selectRaw('
          `participants`.`id` AS `participant_id`,
          `participants`.`date_finish`,
          `participants`.`examination_id`,
          `participants`.`ticket_id`,
          `e`.*
        ')
        ->leftJoin('examinations AS e', 'e.id', '=', 'participants.examination_id')
        ->withSum('participant_sections', 'duration_used')
        ->with(['user', 'ticket', 'examination.category'])
        ->owner();

      return DataTables::of($data)
        ->editColumn('date_finish', function(Participant $participant) {
          if(isset($participant->date_finish)){
            return Carbon::parse($participant->date_finish)->isoFormat('DD MMMM YYYY HH:mm');
          }
          return $participant->date_finish;
        })
        ->make(true);
    }

    return view('web.results.index', compact('config'));
  }

  public function show($id, Request $request){
    $config['title'] = "Detail Laporan";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Detail Laporan"],
    ];

    $paricipant = Participant::owner()->findOrFail($id);
    $resultQuestion = new ResultQuestion($paricipant->id);
    $result = $resultQuestion->viewWeb($request->type);

    return view('web.results.show', compact('config', 'result'));
  }

}
