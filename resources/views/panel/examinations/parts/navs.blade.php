<ul class="nav nav-tabs card-header-tabs">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('panel.examinations.show') ? 'active' : null }}" href="{{ route('panel.examinations.show',['examination'=>$examination]) }}">Ringkasan</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('panel.examinations.sections.*') ? 'active' : null }}" href="{{ route('panel.examinations.sections.index',['examination'=>$examination]) }}">Sesi Naskah Soal</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('panel.examinations.tickets.*') ? 'active' : null }}" href="{{ route('panel.examinations.tickets.index',['examination'=>$examination]) }}">Tiket</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('panel.examinations.participants.*') ? 'active' : null }}" href="{{ route('panel.examinations.participants.index',['examination'=>$examination]) }}">Peserta</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('panel.examinations.results.*') ? 'active' : null }}" href="{{ route('panel.examinations.results.index',['examination'=>$examination]) }}">Hasil</a>
    </li>
</ul>
