@props(['status'])

@if ($status)
    <div class="mb-4">
      <div class="alert alert-success" role="alert">
        {{ $status }}
      </div>
    </div>
@endif
