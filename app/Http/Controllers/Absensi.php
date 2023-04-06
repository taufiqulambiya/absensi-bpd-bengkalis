<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Absensi as AdminAbsensi;
use App\Models\Absensi as ModelsAbsensi;
use App\Models\Cuti;
use App\Models\DinasLuar;
use App\Models\Izin;
use App\Models\JamKerja;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Absensi extends BaseController
{

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sess_user = $request->session()->get('user');
        $user = User::find($sess_user->id);
        $user_level = $sess_user->level;

        if ($user_level == 'admin') {
            $admin_absensi = new AdminAbsensi();
            return $admin_absensi->index();
        }

        if ($user_level == 'pegawai') {
            $cuti = Cuti::lastPengajuan(session('user')->id);
            $izin = Izin::lastPengajuan(session('user')->id);
            $dinas_luar = DinasLuar::lastPengajuan(session('user')->id);

            $has_cuti = $cuti != null;
            $has_izin = $izin != null;
            $has_dinas = $dinas_luar != null;

            $jam_kerja = JamKerja::getAktif();

            $missed_out = ModelsAbsensi::getMissedOut(session('user')->id);
            $has_missed_out = $missed_out != null;

            $current_absensi = ModelsAbsensi::getCurrentAbsensi(session('user')->id);

            $setting = Settings::first();

            $data = compact('setting', 'jam_kerja', 'has_cuti', 'has_izin', 'has_dinas', 'has_missed_out', 'current_absensi', 'cuti', 'izin', 'dinas_luar', 'missed_out');
        }

        return view('panel.pegawai.absensi.absensi', $data);
    }

    public function print() {
        // print all harian
        
    }

    public function store(Request $request)
    {
        $user = session('user');

        $exclude_keys = ['_token', '_method', 'suffix'];
        $post = array_except($request->post(), $exclude_keys);

        $post['tanggal'] = date('Y-m-d');
        $post['id_user'] = $user->id;

        $dok_masuk = $request->dok_masuk;
        $dok_masuk = str_replace('data:image/png;base64,', '', $dok_masuk);
        $dok_masuk = str_replace(' ', '+', $dok_masuk);
        $imageName = fake('id-ID')->uuid() . '.' . 'png';
        Storage::disk('public')->put('uploads/' . $imageName, base64_decode($dok_masuk));

        $post['dok_masuk'] = $imageName;
        $post['status'] = 'hadir';
        $post['waktu_masuk'] = date('H:i:s');

        $result = ModelsAbsensi::create($post);

        return response()->json([
            'data' => $result,
            'success' => 'Rekam absen berhasil.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dok_keluar' => 'required',
        ]);

        $data = ModelsAbsensi::find($id);
        $post = $request->post();
        $post['status'] = 'hadir';
        $post['waktu_keluar'] = date('H:i:s');
        $post['total_jam'] = Carbon::parse($data->waktu_masuk)->diffInMinutes(Carbon::now()) / 60;

        if (!empty($request->dok_keluar)) {
            $image = str_replace('data:image/png;base64,', '', $request->dok_keluar);
            $image = str_replace(' ', '+', $image);
            $imageName = fake('id-ID')->uuid() . '.' . 'png';
            Storage::disk('public')->put('uploads/' . $imageName, base64_decode($image));
            $post['dok_keluar'] = $imageName;
        }
        $result = $data->update($post);
        if ($result) {
            return response()->json([
                'data' => $post,
                'success' => 'Rekam absen berhasil.',
            ]);
        } else {
            return response()->json([
                'data' => $post,
                'error' => 'Rekam absen gagal.',
            ]);
        }
    }

    private function calculateTotalJam($waktuMasuk, $waktuKeluar)
    {
        $diffInHours = Carbon::parse($waktuKeluar)->diffInHours(Carbon::parse($waktuMasuk));

        if ($diffInHours < 0) {
            return Carbon::parse($waktuKeluar)->diffInMinutes(Carbon::parse($waktuMasuk)) / 60;
        }

        return $diffInHours;
    }

    private function formatJamAbsen($mulai, $selesai)
    {
        return Carbon::parse($mulai)->format('H:i') . ' - ' . Carbon::parse($selesai)->format('H:i \W\I\B');
    }

    private function isMidnight($waktu)
    {
        return Carbon::parse($waktu)->isMidnight();
    }


    private function formatAbsensi($absensi)
    {
        $absensi->waktu_masuk = Carbon::parse($absensi->waktu_masuk)->format('H:i') . ' WIB';
        $absensi->waktu_keluar = Carbon::parse($absensi->waktu_keluar)->format('H:i') . ' WIB';
        $absensi->total_jam = $this->calculateTotalJam($absensi->waktu_masuk, $absensi->waktu_keluar);
        $absensi->jam_absen = $this->formatJamAbsen($absensi->shift->mulai, $absensi->shift->selesai);
        $absensi->has_out = !$this->isMidnight($absensi->waktu_keluar);

        return $absensi;
    }

    public function show(Request $request, $id)
    {
        $absensi = ModelsAbsensi::with('user', 'shift')->find($id) ?? abort(404);

        $absensi = $this->formatAbsensi($absensi);
        return view('panel.admin.absensi.detail', compact('absensi'));
    }


    private function getImageEncoded($path)
    {
        $data = Storage::disk('public')->get('uploads/' . $path);
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $base64 = base64_encode($data);
        return "data:image/$type;base64,$base64";
    }

    public function riwayat()
    {
        $print = request('print');

        $mode = request('mode');

        $data = [
            'absensi' => ModelsAbsensi::getRiwayatV2(),
            'absensiPaginate' => ModelsAbsensi::getRiwayat(true),
        ];
        // dd($data['absensi']);
        if ($mode == 'json') {
            return response()->json($data);
        }

        if (!empty($print)) {

            if ($print == 'id') {
                $idAbsensi = request('id');
                $data['absensi'] = ModelsAbsensi::getRiwayatById($idAbsensi) ?? abort(404);
                $absensi = $data['absensi'];
                $absensi->dok_masuk = $this->getImageEncoded($absensi->dok_masuk);
                $absensi->dok_keluar = $this->getImageEncoded($absensi->dok_keluar);
            }
            $user = $data['absensi']->first()->user;
            $data['user'] = $user;

            // dump($data['absensi']);
            $pdf = PDF::loadView("panel.pegawai.absensi.print.riwayat-$print", $data);

            if ($print == 'all') {
                $pdf->setPaper('A4', 'landscape');
            }
            $fileName = 'riwayat-absensi-' . $user->nama . '-' . date('Y-m-d') . '.pdf';
            return $pdf->stream($fileName);
        }
        return view('panel.pegawai.absensi.riwayat', $data);
    }
}