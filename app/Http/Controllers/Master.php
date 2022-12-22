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
        $all_users = User::all();

        $kabid_ids = $data->map(function ($x) {
            return $x->kabids->id ?? '';
        })->toArray();
        $kabid_allowed = User::whereNotIn('id', $kabid_ids)->get();
        $all_kabid = $data->map(function ($x) {
            return $x->kabids;
        });

        return view('panel.admin.bidang.bidang', compact('data', 'kabid_allowed', 'all_users'));
    }

    public function store_bidang(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:tb_bidang,nama',
        ]);

        MasterBidang::create($request->post());

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }

    public function update_bidang(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|unique:tb_bidang,nama,' . $id,
        ]);
        $data = MasterBidang::find($id);

        // cek apakah user terdaftar sebagai kabid di data lain
        // $cek1 = MasterBidang::where('kabid', $request->kabid)->first();
        // if ($cek1) {
        //     $cek1->kabid = null;
        //     $cek1->update();
        // }

        // update data
        $data->update($request->post());

        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy_bidang($id)
    {
        try {
            MasterBidang::destroy($id);
            return response()->json([
                'success' => 'Berhasil menghapus data.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Gagal menghapus data.',
            ]);
        }
    }
}
