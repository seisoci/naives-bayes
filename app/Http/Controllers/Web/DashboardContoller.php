<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardContoller extends Controller
{

  public function index(Request $request)
  {

    $config['title'] = "Dashboard";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Dashboard"],
    ];

    if ($request->ajax()) {
      $model = Examination::query();

      return DataTables::of($model)->make(true);
    }
    return view('web.dashboard.index', compact('config'));
  }
}
