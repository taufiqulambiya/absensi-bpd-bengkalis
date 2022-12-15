<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public $bulan_indo = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember',
    ];

    public function monthPrefix($month_num) {
        return intval($month_num) < 10 ? '0'.$month_num : $month_num;
    }

    public function getDaysInMonth($month) {
        return intval(date('d', strtotime(date('Y-m-t', strtotime(date('Y-'.$month.'-01'))))));
    }

    public function randomPassword($len = 8) {

        //enforce min length 4
        if($len < 4)
            $len = 4;
    
        //define character libraries - remove ambiguous characters like iIl|1 0oO
        $sets = array();
        $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        $sets[] = '23456789';
    
        $password = '';
        
        //append a character from each set - gets first 4 characters
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
        }
    
        //use all characters to fill up to $len
        while(strlen($password) < $len) {
            //get a random set
            $randomSet = $sets[array_rand($sets)];
            
            //add a random char from the random set
            $password .= $randomSet[array_rand(str_split($randomSet))]; 
        }
        
        //shuffle the password string before returning!
        return str_shuffle($password);
    }

    public function statusGetter($status) {
        switch ($status) {
            case 'accepted_kabid':
                return 'Diterima oleh Kabid';
            case 'accepted_admin':
                return 'Diterima oleh Admin';
            case 'rejected':
                return 'Ditolak';
            default:
                return 'Pending';
        }
    }

    public function statusColor($status) {
        switch ($status) {
            case 'accepted_kabid':
                return 'warning';
            case 'accepted_admin':
                return 'success';
            case 'rejected':
                return 'danger';
            default:
                return 'secondary';
        }
    }
}
