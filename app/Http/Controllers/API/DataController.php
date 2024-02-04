<?php

namespace App\Http\Controllers\API;

use App\Helpers\NaiveBayesClassifier;
use App\Http\Controllers\Controller;
use App\Models\Hero;
use App\Models\NaiveBayes;
use Illuminate\Http\Request;

class DataController extends Controller
{
  public function heroes()
  {
    $data = Hero::orderBy('nama')->get();
    $data = $data->map(function ($hero) {
      $hero['image'] = url("/storage/images/thumbnail/{$hero['image']}");
      return $hero;
    });
    return response()->json($data);
  }

  public function predict()
  {
    $data = [
      'hero' => Hero::select('id', 'nama')->orderBy('nama')->get(),
      'tipe_build' => ['Damage', 'Magic', 'Tank'],
      'emblem' => ['Tank', 'Assassin', 'Mage', 'Marksman'],
      'hasil' => ['Menang', 'Kalah'],
    ];
    return response()->json($data);
  }

  public function storePredict(Request $request){

    $dataTraining = NaiveBayes::selectRaw('
        `hero_pick`.`nama` as `hero`,
        `hero_musuh`.`nama` as `hero_musuh`,
        `naive_bayes`.`tipe_build`,
        `naive_bayes`.`emblem`,
        `naive_bayes`.`hasil`
      ')
      ->leftJoin('heroes AS hero_pick', 'hero_pick.id', '=', 'naive_bayes.hero_id')
      ->leftJoin('heroes AS hero_musuh', 'hero_musuh.id', '=', 'naive_bayes.hero_musuh_id')
      ->get()
      ->toArray();

    $naiveBayes = new NaiveBayesClassifier($dataTraining);
    $result = $naiveBayes->predict($request->all());

//    if($result == "Menang"){
//      $result = true;
//    }else{
//      $result = false;
//    }


    return response()->json($result);

  }
}
