<?php
$styles = [
    // 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css',
    // 'https://balubes.com/assets/jquery-ui/themes/humanity/jquery-ui.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/pepper-grinder/jquery-ui.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.css',
    asset('/admin-assets/vendors/jquery.simple-calendar/simple-calendar.css'),
    asset('/admin-assets/vendors/bootstrap/dist/css/bootstrap.min.css'),
    asset('/admin-assets/vendors/nprogress/nprogress.css'),
    asset('/admin-assets/vendors/animate.css/animate.min.css'),
    asset('/admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.css'),
    'https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf_viewer.min.css',
    asset('/css/tracking.css'),
    asset('/admin-assets/build/css/custom.min.css'),
    // 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css',
];

$jss = [
    asset('/admin-assets/vendors/jquery/dist/jquery.min.js'),
    'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.js',
    asset('/admin-assets/vendors/jquery.simple-calendar/jquery.simple-calendar.js'),
    asset('/admin-assets/vendors/sweetalert/sweetalert2@11.js'),
    asset('/admin-assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js'),
    // 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.min.js',
    // 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js',
    asset('/admin-assets/vendors/Chart.js/dist/Chart.min.js'),
    asset('/admin-assets/vendors/gauge.js/dist/gauge.min.js'),
    asset('/admin-assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js'),
    asset('/admin-assets/vendors/iCheck/icheck.min.js'),
    asset('/admin-assets/vendors/skycons/skycons.js'),
    asset('/admin-assets/vendors/datatables.net/js/jquery.dataTables.min.js'),
    asset('/admin-assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js'),
    asset('/admin-assets/vendors/moment/min/moment.min.js'),
    asset('/admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.js'),
    // 'https://cdnjs.cloudflare.com/ajax/libs/loadjs/4.2.0/loadjs.min.js',
    // 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.fp.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/qs/6.11.1/qs.min.js',
];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Absensi</title>
    {{-- @vite('resources/css/app.css') --}}
    @livewireStyles
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
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

        @include('layouts.load-assets')
        <script>
            const initDataTable = () => {
                try {
                    // select .table with exclude .no-tabledata class
                    $(".table:not(.no-tabledata)").DataTable({
                        responsive: true,
                        autoWidth: false,
                        language: { url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json" },
                    });
                } catch (error) {
                    console.log(error);
                }
            };

            document.addEventListener("livewire:load", function () {
                const lw = window.livewire;

                lw.on("success", (message, reload = false) => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        text: message,
                    }).then((result) => {
                        if (reload) {
                            location.reload();
                        }
                    });
                });
                lw.on("error", (message, reload = false) => {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: message,
                    }).then((result) => {
                        if (reload) {
                            location.reload();
                        }
                    });
                });
                lw.on("successHtml", (message, reload = false) => {
                    Swal.fire({
                        icon: "success",
                        title: "Berhasil",
                        html: message,
                    }).then((result) => {
                        if (reload) {
                            location.reload();
                        }
                    });
                });
                lw.on("errorHtml", (message, reload = false) => {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        html: message,
                    }).then((result) => {
                        if (reload) {
                            location.reload();
                        }
                    });
                });
                
                lw.on("initDataTable", initDataTable);

                lw.on('closeModal', id => {
                    $(`#${id}`).modal('hide');
                });
            });

            const baseURL = `{{ URL::to('/') }}`;
            const css = `<?= json_encode($styles) ?>`;
            const js = `<?= json_encode($jss) ?>`;

            const cssArr = JSON.parse(css);
            const jsArr = JSON.parse(js);
            
            function loadCss(_css) {
                return new Promise((resolve, reject) => {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = _css;
                    link.onload = () => resolve();
                    link.onerror = () => reject();
                    document.head.appendChild(link);
                });
            }

            function loadJs(_js) {
                return new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = _js;
                    script.onload = () => resolve();
                    script.onerror = () => reject();
                    document.head.appendChild(script);
                });
            }

            function initialize() {
                $('.preloader').fadeOut();
                
                $('.modal').each(function() {
                    const modalAnims = ['fade', 'slide', 'rotate', 'flip', 'bounce', 'zoom'];
                    if (!modalAnims.some((anim) => $(this).hasClass(anim))) {
                        $(this).addClass('fade');
                    }
                });

                $('#menu_toggle').on('click', function() {
                    if ($('body').hasClass('nav-md')) {
                        $('body').removeClass('nav-md').addClass('nav-sm');
                    } else {
                        $('body').removeClass('nav-sm').addClass('nav-md');
                    }
                });

                const navHeight = $('.nav_menu').height();
                $('.right_col[role="main"]').css('min-height', $(window).height() - navHeight);

                $('table').each(function() {
                    if (!$(this).parent().hasClass('table-responsive')) {
                        $(this).wrap('<div class="table-responsive"></div>');
                    }
                });

                initDataTable();

                const path = window.location.pathname;
                if (/https:\/\/absensi-bpdbengkalis.my.id/i.test(window.location.href)) {
                    const jsURL = `https://absensi-bpdbengkalis.my.id/public/${path.replace('/', '')}.js`;
                    loadJs(jsURL);
                } else {
                    const jsURL = `${baseURL}/${path.replace('/', '')}.js`;
                    loadJs(jsURL);
                }
            }

            function loadAllSequently() {
                const hosting = 'https://absensi-bpdbengkalis.my.id/';
                const assetPath = 'public/';
                const newCssArr = cssArr.map((css) => {
                    if (css.includes(hosting)) {
                        return css.replace(hosting, `${hosting}${assetPath}`);
                    }
                    return css;
                });
                const newJsArr = jsArr.map((js) => {
                    if (js.includes(hosting)) {
                        return js.replace(hosting, `${hosting}${assetPath}`);
                    }
                    return js;
                });
                // updates: keep loading next css if previous css failed to load
                newCssArr.reduce((promise, css) => {
                    return promise.then(() => loadCss(css).catch(() => {}));
                }, Promise.resolve()).then(() => {
                    newJsArr.reduce((promise, js) => {
                        return promise.then(() => loadJs(js).catch(() => {}));
                    }, Promise.resolve()).then(() => {
                        initialize();
                    });
                });
            }

            loadAllSequently();
        </script>

        @livewireScripts
    </body>

</html>