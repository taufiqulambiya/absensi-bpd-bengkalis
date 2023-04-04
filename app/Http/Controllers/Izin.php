<?php

namespace App\Http\Controllers;

use App\Models\Izin as ModelsIzin;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class Izin extends BaseController
{
    private function transformData($data)
    {
        return $data->map(function ($x) {
            $start_date = Carbon::parse($x->tgl_mulai);
            $end_date = Carbon::parse($x->tgl_selesai);

            $x->tgl_mulai = $start_date->format('d/m/Y');
            $x->tgl_selesai = $end_date->format('d/m/Y');

            // get different in days inclde the start date
            $x->durasi = $start_date->diffInDays($end_date) + 1 . ' Hari';
            $x->status = mapStatus($x->status);
            $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
            if ($x->status == 'pending') {
                $tgl_selesai = Carbon::parse($x->tgl_selesai);
                $current_date = Carbon::parse(date('Y-m-d'));
                $x->terlewat = $current_date->isAfter($tgl_selesai);
            }
            return $x;
        });
    }

    private function mapData($x)
    {
        $start_date = Carbon::parse($x->tgl_mulai);
        $end_date = Carbon::parse($x->tgl_selesai);

        $x->formatted_tgl_mulai = $start_date->format('d/m/Y');
        $x->formatted_tgl_selesai = $end_date->format('d/m/Y');

        // get different in days inclde the start date
        $x->formatted_durasi = $start_date->diffInDays($end_date) + 1 . ' Hari';
        $x->formatted_status = formatStatusText($x->status);
        $x->bukti_url = $x->bukti ? Storage::url('public/uploads/' . $x->bukti) : '#';
        if ($x->status == 'pending') {
            $tgl_selesai = Carbon::parse($x->tgl_selesai);
            $current_date = Carbon::parse(date('Y-m-d'));
            $x->terlewat = $current_date->isAfter($tgl_selesai);
        }
        return $x;
    }

    public function printById($id)
    {
        $data = ModelsIzin::find($id);
        $izin = $this->mapData($data);
        $user = User::find($data->id_user);
        $pdf = PDF::loadView('panel.pegawai.izin.print', compact('izin', 'user'));
        return $pdf->stream();
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $print = $request->print;
        if (!empty($print) && $print == 'id') {
            return $this->printById($request->id);
        }

        $status = request()->view ?? 'pending';
        $user = session('user');
        $level = $user->level;

        $pending_count = 0;
        $allow_ajukan = ModelsIzin::getAllowAjukan($user->id);

        $activeIzin = ModelsIzin::lastPengajuan($user->id);

        if ($activeIzin) {
            $allow_ajukan = false;
        }

        $view_by_role = [
            'pegawai' => 'panel.pegawai.izin.izin',
            'kabid' => 'panel.kabid.izin.izin',
            'admin' => 'panel.admin.izin.izin',
            'atasan' => 'panel.pimpinan.izin.izin',
        ];
        return view($view_by_role[$level], compact('level', 'status', 'activeIzin', 'allow_ajukan', 'pending_count'));
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

            // cek 1: apakah ada izin yang masih berlangsung, dibandingkan dengan tanggal yg diajukan
            $cekIzin = ModelsIzin::where('id_user', $id_user)
                ->where('status', 'accepted_pimpinan')
                ->where('tgl_mulai', '<=', $post['tgl_mulai'])
                ->where('tgl_selesai', '>=', $post['tgl_selesai'])
                ->get();

            if ($cekIzin->count() > 0) {
                return redirect()->back()->with('error', 'Slot izin sudah terisi!');
            }

            $post['id_user'] = $id_user;
            $tracking = [['status' => 'Pengajuan dibuat', 'date' => Carbon::now()->toDateTimeString()]];
            $post['tracking'] = json_encode($tracking);

            if (!empty($request->file())) {
                foreach ($request->file() as $key => $value) {
                    $value->storeAs('public/uploads', $value->hashName());
                    $post[$key] = $value->hashName();
                }
            }

            $user = User::find($id_user);
            if ($user and $user->level == 'kabid') {
                $post['status'] = 'accepted_kabid';
            }
            if ($user and $user->level == 'admin') {
                $post['status'] = 'accepted_admin';
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