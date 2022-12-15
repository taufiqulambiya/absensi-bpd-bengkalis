<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\User;
use Illuminate\Http\Request;

class Report extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = session('user');
        $role = $user->level;
        $data = [];

        return view('panel.pimpinan.report.report', compact('data'));
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
                $pegawai = User::with('bidang')->where('id', $request->post('pegawai'))->get()->each(function ($x) {
                    $x->jabatan = $x->bidang->nama;
                });
                if ($request->post('pegawai') == '') {
                    $pegawai = User::with('bidang')->where('level', 'pegawai')->get()->each(function ($x) {
                        $x->jabatan = $x->bidang->nama ?? '-';
                    });
                }
                $return = [
                    'pegawai' => $pegawai,
                ];
                break;
            case 'absensi':
                $absensi = Absensi::with(['user', 'shift'])
                    ->whereBetween('tanggal', [$request->range[0], $request->range[1]]);
                if ($request->pegawai) {
                    $absensi->where('id_user', $request->pegawai);
                }
                $return = $absensi->get();
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
}
