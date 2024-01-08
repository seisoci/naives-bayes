<!doctype html>
<html lang="en" data-layout="horizontal" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
      data-sidebar-image="none" data-preloader="disable" data-bs-theme="light" data-layout-width="fluid"
      data-layout-position="fixed" data-layout-style="default" data-sidebar-visibility="show">
<head>
  <head>
    <meta charset="utf-8"/>
    <title>{{ $config['title'] ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('storage') }}/{{ $settings->favicon ?? '' }}">
    @include('layouts.style')
  </head>

<body>

<div id="layout-wrapper">
  @include('layouts.web.header')

  <div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
      <!-- Dark Logo-->
      <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
        <span class="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="17">
                    </span>
      </a>
      <!-- Light Logo-->
      <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="assets/images/logo-sm.png" alt="" height="22">
                    </span>
        <span class="logo-lg">
                        <img src="assets/images/logo-light.png" alt="" height="17">
                    </span>
      </a>
      <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
              id="vertical-hover">
        <i class="ri-record-circle-line"></i>
      </button>
    </div>

    @include('layouts.web.sidebar')

    <div class="sidebar-background"></div>
  </div>

  <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                  id="NotificationModalbtn-close"></button>
        </div>
        <div class="modal-body">
          <div class="mt-2 text-center">
            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                       colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
              <h4>Are you sure ?</h4>
              <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
            </div>
          </div>
          <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
          </div>
        </div>

      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <div class="vertical-overlay"></div>

  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>
</div>


<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
  <i class="ri-arrow-up-line"></i>
</button>

<div id="preloader">
  <div id="status">
    <div class="spinner-border text-primary avatar-sm" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>
</div>

@include('layouts.script')
<script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
