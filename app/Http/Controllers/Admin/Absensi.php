<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Izin;
use App\Models\JamKerja;
use App\Models\User;
use Carbon\Carbon;

class Absensi extends BaseController
{
    public function index()
    {
        $data = [
            'days_in_current_month' => Carbon::parse(date('Y-m-d'))->locale('id-ID')->daysInMonth,
            'current_date' => !empty($_GET['tgl']) ? date($_GET['tgl'] . '/m/Y') : date('d/m/Y'),
        ];

        return view('panel.admin.absensi.absensi', $data);
    }
}
