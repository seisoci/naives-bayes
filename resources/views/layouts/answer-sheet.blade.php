<!DOCTYPE html>
<html lang="en">
<head>
  <head>
    <meta charset="utf-8"/>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('storage') }}/{{ $settings->favicon ?? '' }}">

    @include('layouts.style')
  </head>
<head>
    @include('layouts.style')
</head>
<body>
    <div id="app" class="app">
        <main class="main">
            @yield('content')
        </main>
    </div>
    @include('layouts.script')
</body>
</html>
