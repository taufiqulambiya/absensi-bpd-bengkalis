<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    use HasFactory;

    static public function getData() {
        $jenis = request()->get('jenis') ?? 'pegawai';
        $pegawai = request()->get('pegawai') ?? [];
        $tanggal_awal = request()->get('tanggal_awal') ?? null;
        $tanggal_akhir = request()->get('tanggal_akhir') ?? date('Y-m-d');

        $data = [
            'data' => [],
        ];

        if (empty($tanggal_awal)) {
            $data['tanggal_awal'] = 'terdahulu';
            $data['tanggal_akhir'] = date('d/m/Y', strtotime($tanggal_akhir));
        }

        if ($tanggal_awal && $tanggal_akhir) {
            $data['tanggal_awal'] = date('d/m/Y', strtotime($tanggal_awal));
            $data['tanggal_akhir'] = date('d/m/Y', strtotime($tanggal_akhir));
        }

        switch ($jenis) {
            case 'pegawai':
                $data['data'] = User::with('bidangs')->where('level', 'pegawai')
                    ->when($pegawai, function ($query, $pegawai) {
                        return $query->whereIn('id', $pegawai);
                    })
                    ->get();
                break;
            case 'absensi':
                $absensi = Absensi::with(['user', 'shift'])
                    ->when($pegawai, function ($query, $pegawai) {
                        return $query->whereIn('id_user', $pegawai);
                    })
                    ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                        return $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
                    })
                    ->orderBy('tanggal', 'asc')
                    ->get()
                    ->map(function ($x) {
                        $x->formatted_tanggal = Carbon::parse($x->tanggal)->format('d/m/Y');

                        $x->formatted_waktu_masuk = Carbon::parse($x->waktu_masuk)->format('H:i') . ' WIB';
                        $x->formatted_waktu_keluar = Carbon::parse($x->waktu_keluar)->format('H:i') . ' WIB';

                        $shift = $x->shift;
                        $mulai = $shift->mulai;
                        $selesai = $shift->selesai;
                        $x->formatted_jam_kerja = Carbon::parse($mulai)->format('H:i') . ' - ' . Carbon::parse($selesai)->format('H:i') . ' WIB';
                        return $x;
                    });
                // group by user
                $groupByUser = $absensi->groupBy('id_user');
                $data['data'] = [];
                foreach ($groupByUser as $key => $value) {
                    $data['data'][$key] = [
                        'user' => $value[0]->user,
                        'absensi' => $value,
                    ];
                }
                break;
            case 'izin':
                $data['data'] = Izin::with('user')
                    ->when($pegawai, function ($query, $pegawai) {
                        return $query->whereIn('id_user', $pegawai);
                    })
                    ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
                        return $query->whereBetween('tgl_mulai', [$tanggal_awal, $tanggal_akhir]);
                    })
                    ->orderBy('tgl_mulai', 'asc')
                    ->get()
                    ->map(function ($x) {
                        $x->formatted_tgl_mulai = Carbon::parse($x->tgl_mulai)->format('d/m/Y');
                        $x->formatted_tgl_selesai = Carbon::parse($x->tgl_selesai)->format('d/m/Y');
                        $x->formatted_durasi = Carbon::parse($x->tgl_mulai)->diffInDays(Carbon::parse($x->tgl_selesai)) + 1 . ' hari';
                        return $x;
                    });
                $groupByUser = $data['data']->groupBy('id_user');
                $data['data'] = [];
                foreach ($groupByUser as $key => $value) {
                    $data['data'][$key] = [
                        'user' => $value[0]->user,
                        'izin' => $value,
                    ];
                }
                break;
            case 'cuti':
                $jenis_cuti = request()->get('jenis_cuti');
                $data['data'] = Cuti::with('user')
                    ->when($pegawai, function ($query, $pegawai) {
                        return $query->whereIn('id_user', $pegawai);
                    })
                    ->when($jenis_cuti, function ($query, $jenis_cuti) {
                        return $query->whereIn('jenis', $jenis_cuti);
                    })
                    ->get()
                    ->filter(function ($x) use ($tanggal_awal, $tanggal_akhir) {
                        $tanggals = explode(',', $x->tanggal);
                        $first = Carbon::parse($tanggals[0])->format('Y-m-d');
                        // return Carbon::parse($first)->between($tanggal_awal, $tanggal_akhir);
                        if ($tanggal_awal && $tanggal_akhir) {
                            return Carbon::parse($first)->between($tanggal_awal, $tanggal_akhir);
                        }
                        return true;
                    })
                    ->map(function ($x) {
                        $formatted_tanggals = [];
                        $tanggals = explode(',', $x->tanggal);
                        foreach ($tanggals as $tanggal) {
                            $formatted_tanggals[] = Carbon::parse($tanggal)->format('d/m/Y');
                        }
                        $x->formatted_tanggal = implode(', ', $formatted_tanggals);
                        $x->durasi = count($tanggals) . ' hari';
                        $x->status = formatStatusText($x->status);
                        return $x;
                    });
                $groupByUser = $data['data']->groupBy('id_user');
                $data['data'] = [];
                foreach ($groupByUser as $key => $value) {
                    $data['data'][$key] = [
                        'user' => $value[0]->user,
                        'cuti' => $value,
                    ];
                }
                break;
            default:
                break;
        }

        return $data;
    }
}
