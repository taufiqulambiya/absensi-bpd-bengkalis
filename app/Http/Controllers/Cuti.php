<?php

namespace App\Http\Controllers;

use App\Models\Cuti as ModelsCuti;
use App\Models\Izin;
use App\Models\Settings;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Cuti extends BaseController
{
    private function getNotAllowed()
    {
        $user = User::find(session('user')->id);
        $izin = Izin::where(['id_user' => $user->id])
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->last();
        // get cuti
        $cuti = ModelsCuti::where('id_user', $user->id)
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->last();

        $not_allowed = [];
        if ($izin) {
            $period = CarbonPeriod::create($izin->tgl_mulai, $izin->tgl_selesai);
            foreach ($period as $p) {
                array_push($not_allowed, $p->format('Y-m-d'));
            }
        }
        if ($cuti) {
            $tgl = explode(',', $cuti->tanggal);
            $diff = array_diff($tgl, $not_allowed);
            $not_allowed = array_merge($not_allowed, $diff);
        }
        return $not_allowed;
    }

    private function totalCutiMapper($x)
    {
        $tanggal = explode(',', $x->tanggal);
        $x->total_tahunan = 0;
        $x->total_besar = 0;
        $x->total_melahirkan = 0;
        $x->total_penting = 0;
        $x->total_ctln = 0;
        switch ($x->jenis) {
            case 'tahunan':
                $x->total_tahunan = count($tanggal);
                break;
            case 'besar':
                $x->total_besar = count($tanggal);
                break;
            case 'melahirkan':
                $x->total_melahirkan = count($tanggal);
                break;
            case 'penting':
                $x->total_penting = count($tanggal);
                break;
            case 'ctln':
                $x->total_ctln = count($tanggal);
                break;
            default:
                $x->total_tahunan = count($tanggal);
                break;
        }
    }

    public function index()
    {
        $user = session('user');
        $setting = Settings::first();
        $level = $user->level;

        if ($level == 'pegawai') {
            $all_cuti = ModelsCuti::where('id_user', $user->id)
                ->where('status', 'accepted_pimpinan')
                ->get()
                ->filter(function ($x) {
                    return Carbon::parse($x->created_at)->year == date('Y');
                })
                ->each(function ($x) {
                    return $this->totalCutiMapper($x);
                });

            $has_cuti = ModelsCuti::where('id_user', $user->id)
                ->where('status', 'accepted_pimpinan')
                ->get()
                ->filter(function ($x) {
                    $tanggal = explode(',', $x->tanggal);
                    $found = array_filter($tanggal, function ($y) {
                        $current_date = date('Y-m-d');
                        return $y > $current_date;
                    });
                    return count($found) > 0;
                })
                ->each(function ($x) {
                    $x->tanggal = array_map(function ($item) {
                        return Carbon::parse($item)->format('d/m/Y');
                    }, explode(',', $x->tanggal));
                    $x->total = count($x->tanggal) . ' Hari';
                })
                ->last();

            $data = [
                'level' => $level,
                'cuti_aktif' => ModelsCuti::where([
                    ['id_user', $user->id],
                    [function ($x) {
                        return $x->where('status', '!=', 'accepted_pimpinan')->where('status', '!=', 'rejected');
                    }]
                ])
                    ->get()
                    ->each(function ($x) {
                        $x->tanggal = explode(',', $x->tanggal);
                        $x->status_text = $this->statusGetter($x->status);
                        $x->status_class = $this->statusColor($x->status);
                    }),
                'jatah_cuti_tahunan' =>  $setting->jatah_cuti_tahunan - $all_cuti->sum('total_tahunan'),
                'jatah_cuti_besar' => $setting->jatah_cuti_besar - $all_cuti->sum('total_besar'),
                'jatah_cuti_melahirkan' => $setting->jatah_cuti_melahirkan - $all_cuti->sum('total_melahirkan'),
                'jatah_cuti_penting' => $setting->jatah_cuti_penting - $all_cuti->sum('total_penting'),
                'jatah_cuti_ctln' => $setting->jatah_cuti_ctln - $all_cuti->sum('total_ctln'),
                'has_cuti' => $has_cuti,
                'cuti_selesai' => ModelsCuti::where(
                    [
                        ['id_user', $user->id],
                        [function ($x) {
                            return $x->where('status', 'accepted_pimpinan')->orWhere('status', 'rejected');
                        }]
                    ]
                )
                    ->get()
                    ->each(function ($x) {
                        $x->tanggal = array_map(function ($y) {
                            return Carbon::parse($y)->format('d/m/Y');
                        }, explode(',', $x->tanggal));
                        $x->status_text = $this->statusGetter($x->status);
                        $x->status_class = $this->statusColor($x->status);
                    }),
                'not_allowed' => $this->getNotAllowed(),
                'user' => $user,
            ];

            return view('panel.pegawai.cuti.cuti', $data);
        }

        if ($level == 'kabid') {
            return $this->index_kabid();
        }

        if ($level == 'admin') {
            return $this->index_admin();
        }

        if ($level == 'atasan') {
            return $this->index_atasan();
        }
    }

    public function index_kabid()
    {
        $user = User::find(session('user')->id);
        $setting = Settings::first();
        $level = $user->level;
        $data = [
            'level' => $level,
            'user' => $user,
            'jatah_cuti_tahunan' =>  $setting->jatah_cuti_tahunan,
        ];
        return view('panel.kabid.cuti.cuti', $data);
    }

    public function index_admin()
    {
        $user = User::find(session('user')->id);
        $setting = Settings::first();
        $level = $user->level;
        $data = [
            'level' => $level,
            'user' => $user,
            'jatah_cuti_tahunan' => $setting->jatah_cuti_tahunan,
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
        if (count($request->allFiles()) > 0) {
            foreach ($request->file() as $key => $file) {
                $file->storeAs('public/uploads', $file->hashName());
                $post[$key] = $file->hashName();
            }
        }
        $tracking = [
            [
                'status' => 'Pengajuan dibuat.',
                'date' => Carbon::now()->toDateTimeString()
            ]
        ];
        $post['tracking'] = json_encode($tracking);

        $id_user = session('user')->id;
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
                'success' => 'Pengajuan berhasil.',
            ]);
        } else {
            return response()->json([
                'error' => 'Pengajuan gagal.',
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
