<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\DinasLuar as ModelsDinasLuar;
use App\Models\Izin;
use App\Models\JamKerja;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Faker\Core\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DinasLuar extends Controller
{
    public function index()
    {
        $role = session('user')->level;
        $users = User::all()->each(function ($x) {
            $tgl_izin = [];
            $izin = Izin::where('id_user', $x->id)->where('status', 'accepted_pimpinan')->get();
            foreach ($izin as $item) {
                $period = CarbonPeriod::between(Carbon::parse($item->tgl_mulai), Carbon::parse($item->tgl_selesai));
                foreach ($period as $p) {
                    array_push($tgl_izin, $p->format('Y-m-d'));
                }
            }
            $x->tgl_izin = $tgl_izin;
            $tgl_cuti = [];
            $izin = Cuti::where('id_user', $x->id)->where('status', 'accepted_pimpinan')->get();
            foreach ($izin as $item) {
                $tgl_cutis = explode(',', $item->tanggal);
                $tgl_cuti = array_merge($tgl_cuti, $tgl_cutis);
            }
            $x->tgl_cuti = $tgl_cuti;
        });
        if ($role == 'admin') {
            return view('panel.admin.dinas_luar.dinas_luar', compact('users'));
        }
        return view('panel.pegawai.dinas_luar.dinas_luar');
    }

    public function store(Request $request)
    {
        $jam = JamKerja::first();
        $post = $request->post();

        $dl = ModelsDinasLuar::where('id_user', $post['id_user'])->get()->last();

        if (!empty($dl)) {
            $selesai = Carbon::parse($dl->selesai);
            if (Carbon::now()->isBefore($selesai)) {
                return redirect()->back()->with('error', 'Masih ada data DINAS LUAR aktif dari pegawai ini.');
            }
        }

        $request->validate([
            'id_user' => 'required',
            'mulai' => 'required',
            'selesai' => 'required',
            'file' => 'required',
            'maksud' => 'required|min:10',
            'lokasi' => 'required|min:3',
        ], [], ['id_user' => 'Pegawai', 'mulai' => 'Tanggal Mulai', 'selesai' => 'Tanggal Selesai', 'file' => 'Berkas Surat', 'maksud' => 'Maksud Perjalanan Dinas', 'lokasi' => 'Lokasi Perjalanan Dinas']);

        $period = CarbonPeriod::between(Carbon::parse($post['mulai']), Carbon::parse($post['selesai']));
        if ($request->files->count() > 0) {
            foreach ($request->allFiles() as $key => $file) {
                $post[$key] = $file->hashName();
                Storage::putFileAs('public/uploads/', $file, $file->hashName());
            };
        }
        $uuid = new Uuid();
        $relation_id = $uuid->uuid3();
        $post['record_id'] = $relation_id;
        $result = ModelsDinasLuar::create($post);
        if ($result) {
            foreach ($period as $p) {
                $date = $p->format('Y-m-d');
                $absen = [
                    'tanggal' => $date,
                    'id_user' => $post['id_user'],
                    'id_jam' => $jam->id,
                    'dok_masuk' => '',
                    'dok_keluar' => '',
                    'status' => 'dinas',
                    'waktu_masuk' => $jam->mulai,
                    'waktu_keluar' => $jam->selesai,
                    'dinas_id' => $relation_id,
                ];
                Absensi::create($absen);
            }

            return redirect()->back()->with('success', 'Dinas luar berhasil ditambah.');
        } else {
            return redirect()->back()->with('error', 'Dinas luar gagal ditambah.');
        }
    }

    public function update(Request $request, $id)
    {
        $post = $request->post();
        $data = ModelsDinasLuar::find($id);

        $request->validate([
            'id_user' => 'required',
            'mulai' => 'required',
            'selesai' => 'required',
            // 'file' => 'required',
            'maksud' => 'required|min:10',
            'lokasi' => 'required|min:3',
        ], [], ['id_user' => 'Pegawai', 'mulai' => 'Tanggal Mulai', 'selesai' => 'Tanggal Selesai', 'file' => 'Berkas Surat', 'maksud' => 'Maksud Perjalanan Dinas', 'lokasi' => 'Lokasi Perjalanan Dinas']);

        $absensi = Absensi::where('dinas_id', $data->record_id)->get()->map(function ($x) {
            return $x->tanggal;
        })->toArray();
        $dates = [];
        $period = CarbonPeriod::between(Carbon::parse($post['mulai']), Carbon::parse($post['selesai']));
        foreach ($period as $p) {
            array_push($dates, $p->format('Y-m-d'));
        }
        $to_deletes = array_diff($absensi, $dates);
        $to_adds = array_diff($dates, $absensi);
        foreach ($to_deletes as $item) {
            $found = Absensi::where('dinas_id', $data->record_id)->where('tanggal', $item);
            if ($found) {
                $found->delete();
            }
        }
        foreach ($to_adds as $item) {
            $jam = JamKerja::first();
            $absen = [
                'tanggal' => $item,
                'id_user' => $post['id_user'],
                'id_jam' => $jam->id,
                'dok_masuk' => '',
                'dok_keluar' => '',
                'status' => 'dinas',
                'waktu_masuk' => $jam->mulai,
                'waktu_keluar' => $jam->selesai,
                'dinas_id' => $data->record_id,
            ];
            Absensi::create($absen);
        }
        if ($request->files->count() > 0) {
            foreach ($request->allFiles() as $key => $file) {
                $post[$key] = $file->hashName();
                Storage::delete('public/uploads/' . $data->file);
                Storage::putFileAs('public/uploads/', $file, $file->hashName());
            };
        }
        $result = $data->update($post);
        if ($result) {
            return redirect()->back()->with('success', 'Dinas luar berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Dinas luar gagal diubah.');
        }
    }

    public function destroy($id)
    {
        $found = ModelsDinasLuar::find($id);
        $absensi = Absensi::where('dinas_id', $found->record_id)->delete();
        $found->delete();
        return response()->json('success');
    }
}
