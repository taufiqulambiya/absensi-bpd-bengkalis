<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Assets extends Controller
{
    //
    public function getAssets() {
        $styles = [
            // 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css',
            // 'https://balubes.com/assets/jquery-ui/themes/humanity/jquery-ui.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/pepper-grinder/jquery-ui.min.css',
            'https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-multidatespicker/1.6.6/jquery-ui.multidatespicker.min.css',
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

        return response()->json([
            'styles' => $styles,
            'jss' => $jss,
        ]);
    }
}
