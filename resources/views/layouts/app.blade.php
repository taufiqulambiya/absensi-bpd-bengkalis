<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Absensi</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <style>
        .btn-flat {
            border-radius: 0 !important;
        }

        /* chips */
        .chips {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .chip {
            padding: 2px;
            border-radius: 12px;
            flex: 20%;
            text-align: center;
            background: #e0e0e0;
            color: #000;
            margin: 2px;
        }
        .chips.in-table {
            display: block;
            flex: 100% !important;
            max-width: 100px;
        }
        .chips.in-table .chip {
            display: block;
            flex: 100% !important;
            max-width: 100px;
        }
        /* end chips */

        /* .chip {
            padding: 2px;
            border-radius: 12px;
            flex: 20%;
            text-align: center;
        }

        .chip.in-table {
            display: block;
            flex: 100% !important;
            max-width: 100px;
        } */

        .ui-timepicker-container {
            z-index: 1151 !important;
        }

        .bg-yellow {
            background: yellow !important;
        }

        .bg-dongker {
            background: #202A44;
             !important;
        }

        .bg-dongker .nav_menu {
            background: #202A44;
             !important;
        }

        /* jquery multidatespicker */
        table.ui-datepicker-calendar {
            border-collapse: separate;
            width: 100% !important;
        }

        .ui-datepicker-calendar td {
            border: 1px solid transparent;
        }

        .ui-datepicker .ui-datepicker-calendar .ui-state-highlight a {
            background: #743620 none;
            /* a color that fits the widget theme */
            color: white;
            /* a color that is readeable with the color above */
        }

        /* end jquery multidatespicker */

        /* preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 999999;
            background-color: #fff;
        }

        .preload.hide {
            display: none;
        }

        .preloader .spinner {
            width: 60px;
            height: 60px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -25px 0 0 -25px;
        }

        .preloader .spinner .item {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #202A44;
            -webkit-animation: sk-bouncedelay 1.4s infinite ease-in-out both;
            animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        }

        @-webkit-keyframes sk-bouncedelay {

            0%,
            80%,
            100% {
                -webkit-transform: scale(0);
            }

            40% {
                -webkit-transform: scale(1);
            }
        }
    </style>
</head>

@if(Route::current()->uri == 'auth' || Route::current()->uri == '/')

<body class="login"> @else

    <body class="nav-md"> @endif

        <div class="preloader">
            <div class="spinner">
                <div class="item"></div>
            </div>
        </div>

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
        @include('layouts.load-assets')
    </body>

</html>