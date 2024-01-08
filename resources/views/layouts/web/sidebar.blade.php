<div id="scrollbar">
  <div class="container-fluid">
    <div id="two-column-menu">
    </div>
    <ul class="navbar-nav" id="navbar-nav">
      <li class="nav-item">
        <a class="nav-link menu-link" href="{{ route('dashboard.index') }}">
          <i class="fa-solid fa-house-user"></i><span>Dashbooard</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link menu-link" href="{{ route('examinations.index') }}">
          <i class="fa-solid fa-graduation-cap"></i><span>Ujian</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link menu-link" href="{{ route('results.index') }}">
          <i class="fa-regular fa-file"></i></i><span>Hasil</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link menu-link" href="{{ route('bulletin.index') }}">
          <i class="fa-solid fa-bullhorn"></i><span>Pengumuman</span>
        </a>
      </li>
    </ul>
  </div>
</div>
