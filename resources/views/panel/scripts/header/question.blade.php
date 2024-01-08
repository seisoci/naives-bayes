<div class="card">
  <div class="card-header justify-content-between">
    <div class="header-title">
      <h3 class="text-center">{{ $script['title'] ?? '' }}</h3>
    </div>
  </div>
  <div class="card-body">
    <table>
      <tr>
        <td class="align-top">{{ $question['number'] ?? '' }}. </td>
        <td>{!! $question['sentence'] ?? '' !!}</td>
      </tr>
      <tr>
        <table class="table table-sm table-borderless table-striped mt-1">
          @foreach($question['choices'] ?? [] as $item)
            <tr>
              <td class="text-end" width="50">{{ $item['key'] }}</td>
              <td>{!! $item['content'] !!}</td>
            </tr>
          @endforeach
        </table>
      </tr>
    </table>
  </div>
</div>
