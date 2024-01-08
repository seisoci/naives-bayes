<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bulletin;
use Illuminate\Http\Request;

class BulletinController extends Controller
{
  public function __invoke(Request $request)
  {

    $config['title'] = "Pengumuman";
    $config['breadcrumbs'] = [
      ['url' => '#', 'title' => "Pengumuman"],
    ];

    $data = Bulletin::where('type', 'announcement')->first();

    return view('web.bulletin.index', compact('config', 'data'));
  }
}
