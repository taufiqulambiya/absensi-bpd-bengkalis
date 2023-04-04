<?php
$styles = [
    // 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css',
    'https://balubes.com/assets/jquery-ui/themes/humanity/jquery-ui.min.css',
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
];
?>

@foreach ($styles as $style)
<link rel="stylesheet" href="{{ $style }}">
@endforeach

<style>
    .btn-flat {
        border-radius: 0 !important;
    }

    .chip {
        padding: 2px;
        border-radius: 12px;
        flex: 20%;
        text-align: center;
    }

    .chip.in-table {
        display: block;
        flex: 100% !important;
        max-width: 100px;
    }

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
</style>

@foreach ($jss as $js)
<script src="{{ $js }}"></script>
@endforeach

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
</script>
