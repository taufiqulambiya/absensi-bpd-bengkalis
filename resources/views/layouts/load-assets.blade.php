<?php
$styles = [
    // 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css',
    // 'https://balubes.com/assets/jquery-ui/themes/humanity/jquery-ui.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/pepper-grinder/jquery-ui.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.css',
    URL::to('/').'/admin-assets/vendors/bootstrap/dist/css/bootstrap.min.css',
    URL::to('/').'/admin-assets/vendors/nprogress/nprogress.css',
    URL::to('/').'/admin-assets/vendors/animate.css/animate.min.css',
    URL::to('/').'/admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.css',
    'https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf_viewer.min.css',
    URL::to('/').'/css/tracking.css',
    URL::to('/').'/admin-assets/build/css/custom.min.css',
    // 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css',
];

$jss = [
    URL::to('/').'/admin-assets/vendors/jquery/dist/jquery.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.js',
    URL::to('/').'/admin-assets/vendors/jquery.simple-calendar/jquery.simple-calendar.js',
    URL::to('/').'/admin-assets/vendors/sweetalert/sweetalert2@11.js',
    URL::to('/').'/admin-assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/pdfmake.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.5/vfs_fonts.min.js',
    // 'https://cdn.jsdelivr.net/npm/pdfjs-dist@2.16.105/build/pdf.min.js',
    URL::to('/').'/admin-assets/vendors/Chart.js/dist/Chart.min.js',
    URL::to('/').'/admin-assets/vendors/gauge.js/dist/gauge.min.js',
    URL::to('/').'/admin-assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js',
    URL::to('/').'/admin-assets/vendors/iCheck/icheck.min.js',
    URL::to('/').'/admin-assets/vendors/skycons/skycons.js',
    URL::to('/').'/admin-assets/vendors/datatables.net/js/jquery.dataTables.min.js',
    URL::to('/').'/admin-assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
    URL::to('/').'/admin-assets/vendors/moment/min/moment.min.js',
    URL::to('/').'/admin-assets/vendors/bootstrap-daterangepicker/daterangepicker.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/loadjs/4.2.0/loadjs.min.js',
    // 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js',
    // 'https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.fp.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js',
    'https://cdnjs.cloudflare.com/ajax/libs/qs/6.11.1/qs.min.js',
];
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/loadjs/4.2.0/loadjs.min.js"></script>
<script>
    const baseURL = `{{ URL::to('/') }}`;
    const dangerConfirmator = ({
        text = 'Tindakan ini akan menghapus data, lanjutkan?',
        confirmText = 'Ya, hapus',
    }, callback = () => {}) => {
        Swal.mixin({
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-secondary",
            },
            buttonsStyling: false,
        }).fire({
            title: 'Warning',
            text,
            showCancelButton: true,
            showConfirmButton: true,
            icon: 'warning',
            confirmButtonText: confirmText,
            cancelButtonText: 'Batal',
        }).then((res) => {
            if (res.isConfirmed) {
                callback();
            }
        })
    }


    const css = `<?= json_encode($styles) ?>`;
    const js = `<?= json_encode($jss) ?>`;

    const cssArr = JSON.parse(css);
    const jsArr = JSON.parse(js);

    cssArr.forEach((css) => {
        loadjs(css, {
            async: false,
        });
    });

    jsArr.forEach((js) => {
        loadjs(js, {
            async: false,
            success: () => {
                initialize();
            },
        });
    });

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
    }
</script>