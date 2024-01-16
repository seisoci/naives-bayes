<div class="card">
  <div class="card-header justify-content-between">
    <div class="header-title">
      <div class="row">
        <div class="col-sm-12 col-lg-12">
          <h4 class="card-title">Hasil Prediksi</h4>
        </div>
      </div>
    </div>
  </div>
  <div class="card-body">
    <h3 class="text-center mb-3">Hasil Prediksi: {{ $result }}</h3>
    <h3>Rumus:</h3>
    <h5>Menang: {{ $naiveBayesRumus['Menang'] }}</h5>
    <h5>Kalah: {{ $naiveBayesRumus['Kalah'] }}</h5>

    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Menang</th>
        <th>Kalah</th>
      </tr>
      <tr>
        <th>{{ $naiveBayesClass['Menang'] }}</th>
        <th>{{ $naiveBayesClass['Kalah'] }}</th>
      </tr>
      </thead>
    </table>
    @foreach($naiveBayes as $key => $item)
      <table class="table table-bordered">
        <thead>
        <tr>
          <th>{{ ucwords($key) }}</th>
          <th>Menang</th>
          <th>Kalah</th>
        </tr>
        <tbody>
        @foreach($item as $keyChild => $itemChild)
          <tr>
            <td>{{ $keyChild }}</td>
            <td>{{ $itemChild['Menang'] }}</td>
            <td>{{ $itemChild['Kalah'] }}</td>
          </tr>
        @endforeach
        </tbody>
      </table>
    @endforeach
  </div>
</div>

