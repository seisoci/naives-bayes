<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TicketController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:tickets-list', ['only' => ['index', 'show']]);
    $this->middleware('can:tickets-create', ['only' => ['create', 'store']]);
    $this->middleware('can:tickets-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:tickets-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Kategori";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Kategori"],
    ];
    if ($request->ajax()) {
      $data = Ticket::with(['examination', 'owner', 'participant']);

      return DataTables::of($data)->make(true);
    }

    return view('panel.tickets.index', compact('config'));
  }
}
