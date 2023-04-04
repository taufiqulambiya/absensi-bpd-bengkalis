<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Absensi</title>
    @include('layouts.top-assets')

    @vite('resources/css/app.css')
    @livewireStyles
</head>

@if(Route::current()->uri == 'auth' || Route::current()->uri == '/') <body class="login"> @else
    <body class="nav-md"> @endif
        <x-layouts.alert />
        @yield('content')

        {{-- @include('layouts.bottom-assets') --}}

        <div id="current-route" data-route="{{ Route::current()->uri }}"></div>

        {{-- data container --}}
        {{-- <div class="data-container" data-user="{{ session('user') }}"></div> --}}
        <div class="data-container" data-role="{{ session('user')->level ?? '' }}"></div>
        <div class="data-container" data-token="{{ csrf_token() }}"></div>
        <div class="data-container" data-base-url="{{ url('/') }}"></div>
        <div class="data-container" data-current-url="{{ url()->current() }}"></div>
        <div class="data-container" data-success-flashdata="{{ Session::get('success') }}"></div>
        {{-- <div class="data-container" data-error-flashdata="{{ Session::get('error') }}"></div> --}}
        <div class="data-container" data-errors="{{ json_encode($errors->all()) }}"></div>
        {{-- end data container --}}

        @vite('resources/js/app.js')
        @livewireScripts
    </body>

</html>