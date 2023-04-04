<?php

namespace App\Http\Controllers;

use App\Models\JamKerja as ModelsJamKerja;
use Illuminate\Http\Request;

class JamKerja extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $data = ModelsJamKerja::orderBy('status');
        $data = $data->whereNull('deleted_at');
        $data = $data->get();
        $disable_days = [];
        foreach ($data as $item) {
            $disable_days = array_merge($disable_days, explode(', ', $item->days));
        }

        // dd($disable_days);
        $allowed = array_diff($days, $disable_days);

        return view('panel.admin.jam_kerja.jam_kerja', compact('data', 'allowed', 'days'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mulai' => 'required',
            'selesai' => 'required',
            'keterangan' => 'required',
        ]);
        try {
            $post = $request->post();
            $filtered_keys = ['_token'];
            $post['status'] = $request->post('status') == 'on' ? 'aktif' : 'nonaktif';
            $post['days'] = join(', ', $request->days);

            $item = ModelsJamKerja::create($post);

            return redirect()->back()->with('success', 'Data berhasil ditambahkan.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $is_update_status = $request->update == 'status' ? true : false;

        if ($is_update_status) {
            $item = ModelsJamKerja::where('id', $id)->first();
            $item->status = $item->status == 'aktif' ? 'nonaktif' : 'aktif';
            $item->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah.',
                'data' => $item
            ]);
        }

        $request->validate([
            'mulai' => 'required',
            'selesai' => 'required',
            'keterangan' => 'required',
        ]);

        try {
            $filtered_keys = ['_method', '_token', 'status'];
            $post = $request->post();
            $post['status'] = $request->post('status') == 'on' ? 'aktif' : 'nonaktif';
            $post['days'] = join(', ', $request->days);

            $item = ModelsJamKerja::where('id', $id)->update($post);
            // return redirect()->back()->with('success', 'Data berhasil diubah.');
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diubah.',
                'data' => $post
            ]);
        } catch (\Throwable $th) {
            // return redirect()->back()->with('error', $th->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted_at = date('Y-m-d H:i:s');

            ModelsJamKerja::where('id', $id)->update(['deleted_at' => $deleted_at]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus.',
            ]);

            // $item->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus shift, terdapat data absen yang terkait jam ini.');
        }
    }
}
