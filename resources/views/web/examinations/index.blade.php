@extends('layouts.web.master')

@section('content')
  <div class="container py-3">
    <div class="row">
        @php $prev_date = null; @endphp
        <h1 class="fw-bolder fs-2 text-uppercase text-center my-3">Jadwal Ujian</h1>
        @if (count($paginator->items()) > 0)
          @foreach ($paginator ?? [] as $item)
            <div class="col-md-4 col-lg-4">
              <div class="card card-exam">
                <img class="card-img-top" src="{{ asset('storage/' . $item->poster) }}" alt="Card image cap">
                <div class="card-body">
                  <h4 class="card-title">{{ \Illuminate\Support\Str::limit($item->name, 150, $end='...') }}</h4>
                  <a href="{{ route('examinations.show', ['participant'=> $item->participant_id]) }}" class="btn btn-primary btn-sm w-100">Masuk</a>
                </div>
              </div>
            </div>
          @endforeach
          <div class="d-flex justify-content-center">
            {{ $paginator->links() }}
          </div>
        @else
          {!! $bulletin['description'] ?? '' !!}
        @endif
      </div>
  </div>
@endsection

@push('style')
  <style>
    .card-exam{
      min-height: 380px;
    }
    .card-exam .card-title{
      min-height:50px;
    }
  </style>
@endpush

@push('script')
  <script>
    runCountdown();

    function runCountdown() {
      let as = this,
        data = $(as.elements.root).data(),
        timeRemaining =
          parseInt(data.examination.duration) -
          parseInt(data.participant.duration_used);

      let time = moment()
        .add(timeRemaining, "s")
        .format("YYYY/MM/DD HH:mm:ss");

      let durationUsed = parseInt(data.participant.duration_used);
      $(as.elements.mainCountdown)
        .countdown(time)
        .on({
          "update.countdown": function (event) {
            durationUsed++;
            $(this)
              .find(".hours")
              .html(event.strftime(event.strftime("%H")));
            $(this)
              .find(".minutes")
              .html(event.strftime(event.strftime("%M")));
            $(this)
              .find(".seconds")
              .html(event.strftime(event.strftime("%S")));
            data.participant.duration_used = durationUsed;
            $(as.elements.root).data(data);
            $(as.elements.root).attr("as--duration-used", durationUsed);
          },
          "finish.countdown": function (event) {
            $("#as__btnModalCountdown").trigger("click");
            setTimeout(function () {
              $(as.elements.btnFinish).trigger("click");
            }, 3000);
          },
        });

      $.post(window.location.href, {
        _method: "PUT",
        participant: {
          duration_used: $(as.elements.root).attr("as--duration-used"),
        },
        participant_section: {
          status: "On Going",
        },
      });

      $(as.elements.instruction).remove();
      $(as.elements.root).attr("as--has-running", 1);
      $(as.elements.btnStart).remove();
      if (data.section.auto_next == 0) {
        $(as.elements.btnNext).removeClass("d-none");
      }

      window.addEventListener(
        "beforeunload",
        function (e) {
          e.preventDefault();
          as.leave();
          e.returnValue = "Are you sure you want to exit?";
          return e.returnValue;
        },
        false
      );
    }
  </script>
@endpush
