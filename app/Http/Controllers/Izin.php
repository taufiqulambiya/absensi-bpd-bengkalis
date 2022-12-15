<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Izin as AdminIzin;
use App\Models\Izin as ModelsIzin;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Izin extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = session()->get('user');
        $level = $user->level;

        if ($level == 'admin') {
            $admin_izin = new AdminIzin();
            return $admin_izin->index();
        }

        if ($level == 'kabid') {
            $missed_count = $data = ModelsIzin::with('user')
                ->where('status', 'pending')
                ->where('tgl_mulai', '<=', date('Y-m-d'))
                ->get()
                ->filter(function ($x) {
                    $jabatan_id = session('user')->jabatan;
                    return $x->user->jabatan == $jabatan_id;
                })->count();
            return view('panel.kabid.izin.izin', compact('missed_count'));
        }

        if ($level == 'pegawai') {
            $count_queue = ModelsIzin::where('id_user', $user->id)
                ->where('status', 'pending')
                ->orWhere('status', 'accepted_kabid')
                ->get()->count();
            $izin_mendatang = ModelsIzin::where('id_user', $user->id)
                ->where('status', 'accepted_admin')
                ->where('tgl_mulai', '>', date('Y-m-d'))
                ->get()
                ->last();

            $data = [
                'level' => $level,
                'is_waiting' => $count_queue > 0,
                'has_izin' => ModelsIzin::where(['id_user' => $user->id])
                    ->where('tgl_mulai', '<=', date('Y-m-d'))
                    ->where('tgl_selesai', '>=', date('Y-m-d'))
                    ->where('status', 'accepted_admin')
                    ->get()
                    ->each(function ($x) {
                        $x->tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
                        $x->tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
                    })
                    ->first(),
                'izin_mendatang' => $izin_mendatang,
            ];
            return view('panel.pegawai.izin.izin', $data);
        }

        if ($level == 'atasan') {
            return view('panel.pimpinan.izin.izin');
        }
    }

    public function store(Request $request)
    {
        $post = $request->post();

        $request->validate([
            'jenis' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'keterangan' => 'required',
            'bukti' => 'required|mimes:jpg,jpeg,png,docx,pdf',
        ], [
            'required' => ':Attribute wajib diisi!',
            'min' => ':Attribute harus berupa angka!',
            'mimes' => 'Jenis file :Attribute harus berupa :values'
        ]);
        try {
            $id_user = $request->session()->get('user')->id;
            $post['id_user'] = $id_user;
            $tracking = [['status' => 'Pengajuan dibuat', 'date' => Carbon::now()->toDateTimeString()]];
            $post['tracking'] = json_encode($tracking);

            if (!empty($request->file())) {
                foreach ($request->file() as $key => $value) {
                    $value->storeAs('public/uploads', $value->hashName());
                    $post[$key] = $value->hashName();
                }
            }
            ModelsIzin::create($post);

            return redirect()->back()->with('success', 'Data berhasil ditambah.');
        } catch (Exception $err) {
            // echo $err->getMessage();
            return redirect()->back()->with('error', $err->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'keterangan' => 'required',
            'bukti' => 'mimes:jpg,jpeg,png,docx,pdf',
        ]);
        try {
            $post = $request->post();
            $item = ModelsIzin::findOrFail($id);
            if (!empty($request->file())) {
                foreach ($request->file() as $key => $value) {
                    if (Storage::exists('/public/uploads/' . $item->$key)) {
                        Storage::delete('/public/uploads/' . $item->$key);
                    }
                    $value->storeAs('public/uploads', $value->hashName());
                    $post[$key] = $value->hashName();
                }
            }

            $tracking = json_decode($item->tracking) ?? [];
            array_push($tracking, [
                'status' => 'Pengajuan diperbarui',
                'date' => Carbon::now()->toDateTimeString(),
            ]);
            $post['tracking'] = json_encode($tracking);
            $item->update($post);

            return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update_status(Request $request, $id)
    {
        $user = User::find(session('user')->id);
        $level = strtoupper($user->level);
        $izin = ModelsIzin::findOrFail($id);
        $post = $request->post();

        $tracking = json_decode($izin->tracking) ?? [];
        array_push($tracking, [
            'status' => $post['status'] === 'rejected' ? 'Pengajuan ditolak ' . $level : 'Pengajuan diterima ' . $level,
            'date' => Carbon::now()->toDateTimeString(),
        ]);
        $post['tracking'] = $tracking;
        $izin->update($post);
        return response([
            'status' => 'success',
            'message' => 'Status berhasil diperbarui',
        ]);
    }

    public function destroy($id)
    {
        try {
            $item = ModelsIzin::findOrFail($id);
            if (Storage::exists('/public/uploads/' . $item->bukti)) {
                Storage::delete('/public/uploads/' . $item->bukti);
            }
            $item->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function cuti()
    {
        return view('panel.cuti.cuti');
    }
}
