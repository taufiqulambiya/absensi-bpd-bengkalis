<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\User;
use App\Models\Report as ReportModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Report extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty($_GET['jenis'])) {
            return $this->print();
        }

        $user = session('user');
        $role = $user->level;

        return view('panel.pimpinan.report.report');
    }

    public function print()
    {
        $jenis = request()->get('jenis');
        // $pegawai = request()->get('pegawai');
        // $tanggal_awal = request()->get('tanggal_awal');
        // $tanggal_akhir = request()->get('tanggal_akhir');
        // $jenis_cuti = request()->get('jenis_cuti');

        $data = ReportModel::getData();
        // dd($data);
        // dd($data['data']);

        $view = 'panel.pimpinan.report.' . $jenis;
        $pdf = Pdf::loadView($view, $data);
        return $pdf->stream('report.pdf');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $return = [];
        switch ($request->filter) {
            case 'pegawai':
                // make variable $pegawai where id is array of $request->post('pegawai'), then get it with bidangs
                $pegawai = User::with('bidangs')->whereIn('id', $request->post('pegawai-select'))->get()->each(function ($x) {
                    $x->jabatan = $x->bidangs->nama;
                });
                // if $request->post('pegawai') is empty, then make variable $pegawai where level is pegawai, then get it with bidangs
                if ($request->post('pegawai-select') == '') {
                    $pegawai = User::with('bidangs')->where('level', 'pegawai')->get()->each(function ($x) {
                        $x->jabatan = $x->bidangs->nama ?? '-';
                    });
                }
                $return = $pegawai;

                // $pegawai = User::with('bidangs')->where('id', $request->post('pegawai'))->get()->each(function ($x) {
                //     $x->jabatan = $x->bidang->nama;
                // });
                // if ($request->post('pegawai') == '') {
                //     $pegawai = User::with('bidangs')->where('level', 'pegawai')->get()->each(function ($x) {
                //         $x->jabatan = $x->bidang->nama ?? '-';
                //     });
                // }
                // $return = [
                //     'pegawai' => $pegawai,
                // ];
                break;
            case 'absensi':
                $split_range = explode(' - ', $request->range);
                $start_date = date('Y-m-d', strtotime($split_range[0]));
                $end_date = date('Y-m-d', strtotime($split_range[1]));

                $absensi = Absensi::with(['user', 'shift'])
                    ->whereBetween('tanggal', [$start_date, $end_date]);
                if ($request->post('pegawai-select')) {
                    $absensi->whereIn('id_user', $request->post('pegawai-select'));
                }
                $absensi = $absensi->get();


                // $absensi = Absensi::with(['user', 'shift'])
                //     ->whereBetween('tanggal', [$request->range[0], $request->range[1]]);
                // if ($request->pegawai) {
                //     $absensi->where('id_user', $request->pegawai);
                // }
                // $return = $absensi->get();
                break;
            case 'izin':
                $data = Izin::with('user')
                    ->whereBetween('tgl_mulai', [$request->range[0], $request->range[1]]);
                if ($request->jenis) {
                    $data->where('jenis', $request->jenis);
                }
                if ($request->pegawai) {
                    $data->where('id_user', $request->pegawai);
                }
                $return = $data->get();
                break;
            case 'cuti':
                $data = Cuti::with('user');
                if ($request->jenis) {
                    $data->where('jenis', $request->jenis);
                }
                if ($request->pegawai) {
                    $data->where('id_user', $request->pegawai);
                }
                $return = $data->get()->filter(function ($x) use ($request) {
                    $tanggal = explode(',', $x->tanggal);
                    $found = false;
                    foreach ($tanggal as $t) {
                        if ($t >= $request->range[0] and $t <= $request->range[1]) {
                            $found = true;
                        }
                    }
                    return $found;
                });
                break;
            case 'dinas_luar':
                $data = DinasLuar::with('user')
                    ->whereBetween('mulai', [$request->range[0], $request->range[1]]);
                if ($request->pegawai) {
                    $data->where('id_user', $request->pegawai);
                }
                $return = $data->get();
                break;
            default:
                # code...
                break;
        }
        return response()->json($return);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_image_base64($image = '')
    {
        $path = Storage::disk('public')->path('uploads/' . $image);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return response()->json($base64);
    }
}