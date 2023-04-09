<?php

namespace App\Http\Controllers;

use App\Models\Cuti as ModelsCuti;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Cuti extends BaseController
{
    public function index()
    {
        $print = request()->get('print');
        if (!empty($print)) {
            $id = request()->get('id');
            return $this->printById($id);
        }

        $user = session('user');
        if ($user->level == 'pegawai') {
            $jatahCuti = ModelsCuti::getJatahCuti($user->id);
            $pengajuanCutiAktif = ModelsCuti::getPengajuanCutiAktif($user->id);
            $cutiAktif = ModelsCuti::getCutiAktif($user->id);
            $hasCuti = $cutiAktif->count() > 0;

            $disableDates = ModelsCuti::getDisableDates($user->id);

            $status = request()->get('view') ?? 'pending';
            $dataCuti = ModelsCuti::getByStatusAndRole($status, $user->level);

            // allow ajukan, allowed only if has not cuti that pending
            $allowedAjukan = ModelsCuti::getIsAllowedAjukan($user->id);

            $data = [
                'data' => $dataCuti,
                'level' => $user->level,
                'active_tab' => $status,
                'pengajuan_cuti_aktif' => $pengajuanCutiAktif,
                'cuti_aktif' => $cutiAktif,
                'has_cuti' => $hasCuti,
                'jatah_cuti' => $jatahCuti,
                'disable_dates' => $disableDates,
                'allowed_ajukan' => $allowedAjukan,
            ];

            
            if (!empty(request()->get('mode')) && request()->get('mode') == 'json') {
                return response()->json($data);
            }

            return view('panel.pegawai.cuti.cuti', $data);
        }

        if ($user->level == 'kabid') {
            return $this->index_kabid();
        }

        if ($user->level == 'admin') {
            return $this->index_admin();
        }

        if ($user->level == 'atasan') {
            return $this->index_atasan();
        }
    }

    public function printById($id) {
        $cuti = ModelsCuti::find($id) ?? abort(404);
        $user = User::with('bidangs')->find($cuti->id_user);

        $tanggal = explode(',', $cuti->tanggal);
        $tanggal = array_map(function ($x) {
            return Carbon::parse($x)->format('d/m/Y');
        }, $tanggal);
        $cuti->total = count($tanggal) . ' Hari';
        $cuti->tanggal = implode(', ', $tanggal);

        $replaceStatus = [
            'pending' => 'Menunggu Persetujuan',
            'accepted_pimpinan' => 'Disetujui Pimpinan',
            'accepted_admin' => 'Disetujui Admin',
            'accepted_kabid' => 'Disetujui Kabid',
            'rejected' => 'Ditolak',
        ];
        $cuti->status = $replaceStatus[$cuti->status];

        $data = [
            'cuti' => $cuti,
            'user' => $user,
        ];

        $pdf = PDF::loadView('panel.kabid.cuti.print', $data);
        return $pdf->stream();
    }

    public function index_kabid()
    {
        $user = User::find(session('user')->id);
        $jatahCuti = Settings::getJatahCuti();

        $level = $user->level;
        $data = [
            'level' => $level,
            'user' => $user,
            'jatah_cuti' =>  $jatahCuti,
        ];
        return view('panel.kabid.cuti.cuti', $data);
    }

    public function index_admin()
    {
        $user = User::find(session('user')->id);
        $jatahCuti = Settings::getJatahCuti();

        $level = $user->level;
        $data = [
            'level' => $level,
            'user' => $user,
            'jatah_cuti' =>  $jatahCuti,
        ];
        return view('panel.admin.cuti.cuti', $data);
    }

    public function index_atasan()
    {
        $user = User::find(session('user')->id);
        $setting = Settings::first();
        $level = $user->level;
        $data = [
            'level' => $level,
            'user' => $user,
            'jatah_cuti_tahunan' => $setting->jatah_cuti_tahunan,
        ];
        return view('panel.pimpinan.cuti.cuti', $data);
    }

    public function store(Request $request)
    {
        $post = $request->post();
        // delete _token
        unset($post['_token']);

        if (count($request->allFiles()) > 0) {
            foreach ($request->file() as $key => $file) {
                $file->storeAs('public/uploads', $file->hashName());
                $post[$key] = $file->hashName();
            }
        }

        $id_user = $post['id_user'];
        $user = User::find($id_user);
        if ($user and $user->level == 'kabid') {
            $post['status'] = 'accepted_kabid';
        }
        if ($user and $user->level == 'admin') {
            $post['status'] = 'accepted_admin';
        }
        $success = ModelsCuti::create($post);

        if ($success) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan cuti berhasil diajukan',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan cuti gagal diajukan',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();
        $item = ModelsCuti::find($id);

        if (count($request->allFiles()) > 0) {
            foreach ($request->file() as $key => $file) {
                $old = $item[$key];
                Storage::delete('public/uploads/' . $old);
                $file->storeAs('public/uploads', $file->hashName());
                $post[$key] = $file->hashName();
            }
        }
        $tracking = json_decode($item->tracking) ?? [];
        if ($request->status) {
            $level = session('user')->level;
            array_push($tracking, [
                'status' => $post['status'] === 'rejected' ? 'Pengajuan ditolak ' . $level : 'Pengajuan diterima ' . $level,
                'date' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            array_push($tracking, [
                'status' => 'Pengajuan diperbarui',
                'date' => Carbon::now()->toDateTimeString(),
            ]);
        }
        $post['tracking'] = json_encode($tracking);
        $success = $item->update($post);
        if ($success) {
            return response()->json([
                'success' => 'Pengajuan berhasil diperbarui.',
            ]);
        } else {
            return response()->json([
                'error' => 'Pengajuan gagal diperbarui.',
            ]);
        }
    }

    public function destroy($id)
    {
        $item = ModelsCuti::find($id);

        try {
            Storage::delete('public/uploads/' . $item->bukti);
            $item->delete();
            return redirect()->back()->with('success', 'Pengajuan dibatalkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Pengajuan gagal dibatalkan');
        }
    }
}
