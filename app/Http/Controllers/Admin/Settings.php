<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings as ModelsSettings;
use Illuminate\Http\Request;

class Settings extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ModelsSettings::first();

        if (empty($data)) {
            $init = [
                'base_lat' => 0,
                'base_long' => 0,
                'jatah_cuti_tahunan' => 0,
                'jatah_cuti_besar' => 0,
                'jatah_cuti_melahirkan' => 0,
                'jatah_cuti_penting' => 0,
                'jatah_cuti_ctln' => 0,
            ];
            ModelsSettings::create($init);
            return redirect()->route('settings.index');
        }
        return view('panel.admin.settings.settings', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $base_data = ModelsSettings::first();

        if($base_data) {
            // update
            $base_data->update($request->post());
        } else {
            ModelsSettings::create([
                'base_lat' => $request->base_lat,
                'base_long' => $request->base_long,
                'jatah_cuti' => $request->jatah_cuti,
            ]);
        }
        return response()->json([
            'success' => 'Data berhasil disimpan.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
