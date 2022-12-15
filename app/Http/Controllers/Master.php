<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use App\Models\User;
use Illuminate\Http\Request;

class Master extends Controller
{
    public function index_bidang()
    {
        $data = MasterBidang::with(['users', 'kabids'])->get();

        $kabid_ids = $data->map(function ($x) {
            return $x->kabids->id;
        })->toArray();
        $kabid_allowed = User::whereNotIn('id', $kabid_ids)->get();
        $all_kabid = $data->map(function($x) {
            return $x->kabids;
        });

        return view('panel.admin.bidang.bidang', compact('data', 'kabid_allowed'));
    }

    public function store_bidang(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:tb_bidang,nama',
        ]);

        MasterBidang::create($request->post());

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
}
