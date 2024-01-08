<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use App\Models\Category;
use App\Traits\ResponseStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class BulletinController extends Controller
{
  use ResponseStatus;

  function __construct()
  {
    $this->middleware('can:bulletin-list', ['only' => ['index', 'show']]);
    $this->middleware('can:bulletin-create', ['only' => ['create', 'store']]);
    $this->middleware('can:bulletin-edit', ['only' => ['edit', 'update']]);
    $this->middleware('can:bulletin-delete', ['only' => ['destroy']]);
  }

  public function index(Request $request)
  {
    $config['title'] = "Pengumuman";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Pengumuman"],
    ];

    $data = Bulletin::all()->keyBy('type');

    return view('panel.bulletin.index', compact('config', 'data'));
  }

  public function store(Request $request)
  {
    foreach ($request['bulletin'] ?? [] as $key => $item):
      Bulletin::updateOrCreate([
        'type' => $key,
      ], [
        'description' => $item,
      ]);
    endforeach;

    return response()->json($this->responseUpdate(true));

  }


}
