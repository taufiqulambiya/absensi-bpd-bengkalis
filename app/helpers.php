<?php

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

function getCustomAttributes($rule) {
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
