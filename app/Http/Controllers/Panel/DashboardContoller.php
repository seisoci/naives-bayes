<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Script;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardContoller extends Controller
{
  function __construct()
  {
    $this->middleware('can:dashboard-list', ['only' => ['index', 'show']]);
    $this->middleware('can:dashboard-create', ['only' => ['create', 'store']]);
    $this->middleware('can:dashboard-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:dashboard-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Dashboard";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Dashboard"],
    ];

    $data = [
      'userCount' => User::count(),
      'scriptCount' => Script::query()->example(false)->count(),
      'scriptExampleCount' => Script::query()->example(true)->count(),
      'questionCount' => Question::count(),
      'examinationCount' => Examination::count(),
      'examinationPlanCount' => Examination::where('status', 'Plan')->count(),
      'examinationOnGoingCount' => Examination::where('status', 'On Going')->count(),
      'examinationDoneCount' => Examination::where('status', 'Done')->count(),
      'ticketCount' => Ticket::count(),
      'ticketAvailableCount' => Ticket::whereNull('owner_id')->count(),
      'ticketHavingCount' => Ticket::whereNotNull('owner_id')->count(),
      'ticketUsedCount' => Participant::count(),
    ];

    return view('panel.dashboard.index', compact('config', 'data'));
  }
}
