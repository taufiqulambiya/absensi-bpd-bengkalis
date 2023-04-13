<?php

use Carbon\Carbon;

function mapStatus($status)
{
    switch ($status) {
        case 'accepted_kabid':
            return [
                'text' => 'Diterima oleh Kabid',
                'color' => 'warning',
                'ori' => $status,
            ];
        case 'accepted_admin':
            return [
                'text' => 'Diterima oleh Admin',
                'color' => 'success',
                'ori' => $status,
            ];
        case 'accepted_pimpinan':
            return [
                'text' => 'Diterima oleh Pimpinan',
                'color' => 'success',
                'ori' => $status,
            ];
        case 'rejected':
            return [
                'text' => 'Ditolak',
                'color' => 'danger',
                'ori' => $status,
            ];
        default:
            return [
                'text' => 'Pending',
                'color' => 'secondary',
                'ori' => $status,
            ];
    }
}

function mapJenisCuti($jenis)
{
    $map_jenis = ['tahunan' => 'Tahunan', 'besar' => 'Besar', 'melahirkan' => 'Melahirkan', 'penting' => 'Karena Alasan Penting', 'ctln' => 'Diluar Tanggungan Negara'];

    return $map_jenis[$jenis];
}

function getCustomAttributes($rule)
{
    $customAttributes = [
        'setting' => [
            'jatah_cuti_tahunan' => 'Jatah Cuti Tahunan',
            'jatah_cuti_besar' => 'Jatah Cuti Besar',
            'jatah_cuti_melahirkan' => 'Jatah Cuti Melahirkan',
            'jatah_cuti_penting' => 'Jatah Cuti Karena Alasan Penting',
            'jatah_cuti_ctln' => 'Jatah Cuti Diluar Tanggungan Negara'
        ]
    ];
    return $customAttributes[$rule];
}

function formatAbsensi($data)
{
    // dump($data->shift);
    $data->formatted_shift = $data->shift ? Carbon::parse($data->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($data->shift->selesai)->format('H:i') . ' WIB' : 'Libur';

    $waktu_masuk = Carbon::parse($data->waktu_masuk);
    $waktu_keluar = Carbon::parse($data->waktu_keluar);

    $total_jam = 0;
    // $total_jam = $waktu_keluar->diffInHours($waktu_masuk);
    // $total_menit = $waktu_keluar->diffInMinutes($waktu_masuk) % 60;
    // $data->total_jam = $total_jam == 0 ? $total_menit . ' menit' : $total_jam . ' jam ' . $total_menit . ' menit';

    // if waktu masuk is greater then waktu keluar
    if ($waktu_masuk->greaterThan($waktu_keluar)) {
        // add 1 day to waktu keluar
        $waktu_keluar->addDay();
        $total_jam = $waktu_keluar->diffInHours($waktu_masuk);
        $total_menit = $waktu_keluar->diffInMinutes($waktu_masuk) % 60;
        $data->total_jam = $total_jam == 0 ? $total_menit . ' menit' : $total_jam . ' jam ' . $total_menit . ' menit';
    } else {
        $total_jam = $waktu_keluar->diffInHours($waktu_masuk);
        $total_menit = $waktu_keluar->diffInMinutes($waktu_masuk) % 60;
        $data->total_jam = $total_jam == 0 ? $total_menit . ' menit' : $total_jam . ' jam ' . $total_menit . ' menit';
    }

    $data->formatted_waktu_masuk = $waktu_masuk->format('H:i \W\I\B');
    $data->formatted_waktu_keluar = $waktu_keluar->format('H:i \W\I\B');

    $data->formatted_tanggal = Carbon::parse($data->tanggal)->format('d/m/Y');

    return $data;
}

function formatStatusText($status)
{
    switch ($status) {
        case 'accepted_kabid':
            return 'Diterima oleh Kabid';
        case 'accepted_admin':
            return 'Diterima oleh Admin';
        case 'accepted_pimpinan':
            return 'Diterima oleh Pimpinan';
        case 'rejected':
            return 'Ditolak';
        default:
            return 'Pending';
    }
}

function formatStatusCuti($status)
{
    switch ($status) {
        case 'accepted_kabid':
            return 'Diterima oleh Kabid';
        case 'accepted_admin':
            return 'Diterima oleh Admin';
        case 'accepted_pimpinan':
            return 'Diterima oleh Pimpinan';
        case 'rejected':
            return 'Ditolak';
        default:
            return 'Pending';
    }
}

function formatStatusCutiColor($status)
{
    switch ($status) {
        case 'accepted_kabid':
            return 'warning';
        case 'accepted_admin':
        case 'accepted_pimpinan':
            return 'success';
        case 'rejected':
            return 'danger';
        default:
            return 'secondary';
    }
}

function rangeCheck($start, $end, $range) {
    return ($start >= $range[0] && $start <= $range[1]) || ($end >= $range[0] && $end <= $range[1]);
}

function getDurationExceptWeekend($start, $end) {
    $start = Carbon::parse($start);
    $end = Carbon::parse($end);

    $total = 0;
    $current = $start->copy();

    while ($current->lte($end)) {
        if ($current->isWeekday()) {
            $total++;
        }

        $current->addDay();
    }

    return $total;
}