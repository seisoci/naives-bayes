<div class="card">
  <div class="card-header justify-content-between">
    <div class="header-title">
      <h3 class="text-center">{{ $script['title'] ?? '' }}</h3>
    </div>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-3">Jenis Soal</dt>
      <dd class="col-9">{{ $script['type'] ?? '' }}</dd>
      <dt class="col-3">Kategori</dt>
      <dd class="col-9">{{ $script['category']['name'] ?? '' }}</dd>
    </dl>
  </div>
</div>
