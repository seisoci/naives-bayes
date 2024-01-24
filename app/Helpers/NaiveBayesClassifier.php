<?php

namespace App\Helpers;

class NaiveBayesClassifier {
  private $rumusPredict = [];
  private $trainingData = [];
  private $classCounts = [];
  private $featureCounts = [];
  private $tableCounts = [];

  public function __construct($trainingData) {
    $this->trainingData = $trainingData;
    $this->calculateProbabilities();
  }

  public function calculateProbabilities() {
    $totalData = count($this->trainingData);

    // Menghitung jumlah masing-masing kelas
    foreach ($this->trainingData as $data) {
      $class = $data['hasil'];
      if (!isset($this->classCounts[$class])) {
        $this->classCounts[$class] = 0;
      }
      $this->classCounts[$class]++;
    }

    // Menghitung jumlah masing-masing fitur untuk setiap kelas
    foreach ($this->trainingData as $data) {
      foreach ($data as $key => $value) {
        if ($key !== 'hasil') {
          $class = $data['hasil'];
          if (!isset($this->featureCounts[$class][$key][$value])) {
            $this->featureCounts[$class][$key][$value] = 0;
          }
          $this->featureCounts[$class][$key][$value]++;
        }
      }
    }

    // Menghitung probabilitas
    foreach ($this->classCounts as $class => $count) {
      foreach ($this->featureCounts[$class] as $featureKey => $featureValues) {
        foreach ($featureValues as $value => $valueCount) {
//          $this->featureCounts[$class][$featureKey][$value] = $this->featureCounts[$class][$featureKey][$value]."/{$count} = ". $this->featureCounts[$class][$featureKey][$value] / $count;
          $this->tableCounts[$class][$featureKey][$value] = $this->featureCounts[$class][$featureKey][$value]."/{$count} = ". $this->featureCounts[$class][$featureKey][$value] / $count;
//          dd($this->featureCounts[$class][$featureKey][$value] = $value++);
          $this->featureCounts[$class][$featureKey][$value] /= $count;
//          dd($this->featureCounts[$class][$featureKey][$value]);
        }
      }
      $this->classCounts[$class] /= $totalData;
    }
  }

  public function predict($data) {
    $classProbabilities = []; // Menyimpan probabilitas untuk setiap kelas
    $predictedClass = '';
    $maxProbability = -1;
    $rumus = '';

    foreach ($this->classCounts as $class => $classProbability) {
      $probability = $classProbability; // Menginisialisasi probabilitas kelas
      $rumus .= $classProbability." * "; // Menginisialisasi probabilitas kelas
      // Perhitungan probabilitas fitur diberikan kelas
      $keysArray = array_keys($data);
      $lastKey = end($keysArray);
      $kali = '';
      foreach ($data as $key => $value) {
        if ($key !== 'hasil') {
          // Menggunakan probabilitas fitur diberikan kelas (P(xi|C))
          // Memeriksa jika fitur ada dalam data pelatihan
          if (isset($this->featureCounts[$class][$key][$value])) {
            // Perhitungan probabilitas fitur diberikan kelas
            $probability *= $this->featureCounts[$class][$key][$value];

            if ($key !== $lastKey) {
              $kali =  " * ";
            }

            $rumus .= $this->featureCounts[$class][$key][$value] .$kali;
          } else {
            // Handle jika fitur tidak ada di data pelatihan menggunakan Laplace smoothing
            // Ini hanyalah contoh pendekatan, dalam kasus nyata mungkin diperlukan pendekatan yang lebih canggih
            $probability *= 0.01; // Contoh: Laplace smoothing
            if ($key !== $lastKey) {
              $kali =  " * ";
            }
            $rumus .= "0.01 {$kali}";
          }
        }
        $kali = '';
      }
      $this->rumusPredict[$class] = $rumus ." = ".number_format($probability, 10);
      $rumus = '';

      // Menyimpan nilai probabilitas untuk setiap kelas
      $classProbabilities[$class] = $probability;

      // Memilih kelas dengan probabilitas tertinggi sebagai kelas prediksi
      if ($probability > $maxProbability) {
        $maxProbability = $probability;
        $predictedClass = $class;
      }
    }
    unset($this->trainingData);
    return $predictedClass;
  }

  public function getTableCounts() {
    // Menampilkan jumlah masing-masing fitur untuk setiap kelas

    $organizedData = [];
    foreach ($this->tableCounts as $outcome => $categories) {
      foreach ($categories as $categoryName => $categoryValues) {
        foreach ($categoryValues as $itemName => $itemValue) {
          $organizedData[$categoryName][$itemName][$outcome] = $itemValue;
        }

        // Menambahkan nilai default 0 jika item tidak ada dalam array 'Menang' atau 'Kalah'
        $allItems = array_keys($this->tableCounts['Menang'][$categoryName] + $this->tableCounts['Kalah'][$categoryName]);
        $missingItems = array_diff($allItems, array_keys($categories[$categoryName]));

        foreach ($missingItems as $missingItem) {
          $organizedData[$categoryName][$missingItem][$outcome] = '0';
        }
      }
    }
    return $organizedData;
  }

  public function getRumusPredict(){
    return $this->rumusPredict;
  }

  public function getClassCount(){
    return $this->classCounts;
  }
}
