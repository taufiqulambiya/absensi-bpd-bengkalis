<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Absensi</title>
    @include('layouts.top-assets')
</head>

@if(Route::current()->uri == 'auth' || Route::current()->uri == '/')

<body class="login"> @else

    <body class="nav-md"> @endif
        <x-layouts.alert />

        @if (Session::has('success'))
        <script>
            showSuccessAlert(`<?= Session::get('success') ?>`);
        </script>
        @endif
        @if (Session::has('error'))
        <script>
            showErrorAlert(`<?= Session::get('error') ?>`);
        </script>
        @endif
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <script>
            showErrorAlert(`<?= $error ?>`);
        </script>
        @endforeach
        @endif

        @yield('content')

        @include('layouts.bottom-assets')
    </body>

</html>