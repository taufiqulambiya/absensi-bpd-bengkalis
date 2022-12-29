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
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];
        $data = ModelsJamKerja::orderBy('status')->get();
        
        $disable_days = [];
        foreach ($data as $key => $value) {
            $used_days = explode(',', $value->days);
            foreach ($used_days as $day) {
                array_push($disable_days, trim($day));
            }
        }
        // dd($disable_days);
        $allowed = array_filter($days, function($x) use ($disable_days) {
            $found = array_search($x, $disable_days);
            return $found === false and $found !== 0;
        });
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
            $model = new ModelsJamKerja();
            $filtered_keys = ['_token', 'status'];

            foreach ($request->post() as $key => $value) {
                if (!in_array($key, $filtered_keys)) {
                    $model->$key = $value;
                }
            }
            $model->status = $request->post('status') == 'on' ? 'aktif' : 'nonaktif';
            $model->days = join(', ', $request->days);
            $model->save();
            return redirect()->back()->with('success', 'Data berhasil ditambah.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if(empty($request->post('update_status'))) {
            $request->validate([
                'mulai' => 'required',
                'selesai' => 'required',
                'keterangan' => 'required',
            ]);
        }

        try {
            $filtered_keys = ['_method', 'update_status', '_token', 'status'];
            $item = ModelsJamKerja::find($id);

            foreach ($request->post() as $key => $value) {
                if (!in_array($key, $filtered_keys)) {
                    $item->$key = $value;
                }
            }
            
            $item->status = $request->post('status') == 'on' ? 'aktif' : 'nonaktif';
            if ($request->has('days')) {
                $item->days = join(', ', $request->days);
            }
            $item->save();
            return redirect()->back()->with('success', 'Data berhasil diubah.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $item = ModelsJamKerja::findOrFail($id);

            $item->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal menghapus shift, terdapat data absen yang terkait jam ini.');
        }
    }
}
