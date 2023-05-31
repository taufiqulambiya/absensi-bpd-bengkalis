<?php

namespace App\Http\Controllers;

use App\Models\MasterBidang;
use App\Models\User as ModelsUser;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class User extends BaseController
{
    private function transformBidang($data) {
        return $data->map(function($item) {
            return [
                'name' => $item->nama,
                'id' => $item->id
            ];
        });
    }

    public function index(Request $request)
    {
        $user = $request->session()->get('user');
        $level = $user->level;

        if ($level == 'pegawai') {
            $data = ModelsUser::find($user->id);
            return view('panel.pegawai.users.profil', compact('data'));
        }
        if ($level == 'kabid') {
            $data['data'] = ModelsUser::with('bidangs')
                ->where('bidang', $user->bidang)
                ->get();
            return view('panel.kabid.users.users', $data);
        }
        $data['data'] = ModelsUser::with('bidangs')->get();
        $data['bidang'] = $this->transformBidang(MasterBidang::all());
        return view('panel.admin.users.users', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'unique:users,nip|min:18',
            'nama' => 'required',
            'jk' => 'required',
            'alamat' => 'required|min:10',
            'golongan' => 'required',
            'level' => 'required',
        ]);
        try {
            $password = $this->passwordByName($request->nama);
            $post = $request->post();

            $post['password'] = password_hash($password, PASSWORD_DEFAULT);

            $found_atasan = ModelsUser::with(['bidang'])
                ->where('level', 'atasan')
                ->get()
                ->filter(function ($q) {
                    return $q->bidang;
                });
            if ($found_atasan->count() > 0 and $request->level === 'atasan') {
                throw new Error('Jabatan Atasan hanya dapat dipegang oleh satu Pengguna');
            }

            ModelsUser::create($post);

            return redirect()->back()->with('success', "Data berhasil diinput. PASSWORD USER: $password, harap minta User untuk segera mengganti Password.");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        if (!empty($request->post('update_password'))) {
            return $this->_update_password($request, $id);
        }
        $request->validate([
            'nama' => 'required',
            'nip' => 'required|min:18|unique:users,nip,' . $id,
            'tgl_lahir' => 'date|required',
            'no_telp' => 'required',
            'gambar' => 'mimes:jpg,jpeg,png',
        ], [], ['nip' => 'NIP']);
        // try {
        $item = ModelsUser::findOrFail($id);

        $found_atasan = ModelsUser::with(['bidangs' => function ($q) {
            return $q->where('nama', 'Atasan');
        }])->get()->filter(function ($q) {
            return $q->bidangs;
        });
        if ($found_atasan->count() > 0 and $found_atasan->first()->id != $id) {
            $first_atasan = $found_atasan->first();
            if ($request->bidang == $first_atasan->bidang) {
                throw new Error('Jabatan Atasan hanya dapat dipegang oleh satu Pengguna');
            }
            // if ($request->level == 'atasan') {
            //     throw new Error('Jabatan Atasan hanya dapat dipegang oleh satu Pengguna');
            // }
        }

        $found_admin = ModelsUser::where('level', 'admin');
        if ($found_admin->count() == 1 and $found_admin->first()->id == $id and $request->level != 'admin') {
            throw new Error('Ups, harap ada minimal satu Admin pada Sistem.');
        }

        $post = $request->post();
        if (!empty($request->file())) {
            foreach ($request->file() as $key => $value) {
                if (Storage::exists('/public/uploads/' . $item->$key)) {
                    Storage::delete('/public/uploads/' . $item->$key);
                }
                $value->storeAs('public/uploads', $value->hashName());
                $post[$key] = $value->hashName();
            }
        }
        $item->update($post);
        if ($request->isXhr) {
            return response()->json('ok');
        }
        return redirect()->back()->with('success', 'Data berhasil diperbarui.');
        // } catch (\Throwable $th) {
        //     // dd($th);
        //     return redirect()->back()->with('error', $th->getMessage());
        // }
    }

    private function _update_password($request, $id)
    {
        $request->validate([
            'old-password' => 'required',
            'new-password' => 'required|min:8',
            'new-password2' => 'required|same:new-password',
        ], [], ['old-password' => 'Password lama', 'new-password' => 'Password baru', 'new-password2' => 'Konfirmasi password']);

        try {
            $user = ModelsUser::find($id);
            $verify = password_verify($request->post('old-password'), $user->password);

            if ($verify) {
                $password = password_hash($request->post('new-password'), PASSWORD_DEFAULT);
                $user->update(['password' => $password]);
                return redirect()->back()->with('success', 'Password berhasil diperbarui.');
            } else {
                throw new Exception('Password lama salah.');
            }
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function show($id)
    {
        $sess_user = session('user');
        if ($sess_user->id != $id) {
            return 'Access denied';
        }
        $user = ModelsUser::with('bidangs')->findOrFail($id);
        if ($user->level == 'pegawai') {
            return view('panel.pegawai.users.profile', compact('user'));
        }
        if ($user->level == 'kabid') {
            return view('panel.kabid.users.profile', compact('user'));
        }
        if ($user->level == 'admin') {
            // $user = ModelsUser::find($id);
            return view('panel.admin.users.profile', compact('user'));
        }
        if ($user->level == 'atasan') {
            return view('panel.pimpinan.users.profile', compact('user'));
        }
    }

    public function destroy($id)
    {
        $item = ModelsUser::find($id);

        $found_admin = ModelsUser::where('level', 'admin');

        if ($found_admin->count() == 1 and $found_admin->first()->id == $id) {
            return response()->json([
                'error' => 'Pastikan terdapat satu user Admin pada sistem.'
            ]);
        }

        $item->delete();
        return response()->json([
            'success' => 'User berhasil dihapus'
        ]);
    }

    public function update_password(Request $request, $id)
    {
        $user = ModelsUser::findOrFail($id);
        $request->validate([
            'password2' => 'required|min:5|same:password',
            'password' => 'required|min:5',
            'old-password' => 'required|min:5',
        ], [], [
            'old-password' => 'Password lama',
            'password' => 'Password baru',
            'password2' => 'Konfirmasi password',
        ]);

        $is_valid = password_verify($request->post('old-password'), $user->password);
        if ($is_valid) {
            $password = password_hash($request->password, PASSWORD_DEFAULT);
            $user->password = $password;
            $user->update();

            return redirect()->back()->with('success', 'Password berhasil diubah.');
        } else {
            return redirect()->back()->with('error', 'Password lama salah.');
        }
    }

    public function reset_password(Request $request)
    {
        $id = $request->id;
        $user = ModelsUser::findOrFail($id);
        $password = $this->randomPassword(5);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $user->password = $hashed_password;
        $user->update();
        return response()->json($password);
    }

    public function toggle_kabid_level()
    {
        $current_sess = session('user');
        $user = ModelsUser::find($current_sess->id);
        if ($user->level != 'kabid') {
            return 'Access denied';
        }
        if ($current_sess->level == 'kabid') {
            session('user')->level = 'pegawai';
        } else {
            session('user')->level = 'kabid';
        }
        return redirect()->route('dashboard');
    }
    public function toggle_admin_level()
    {
        $current_sess = session('user');
        $user = ModelsUser::find($current_sess->id);
        if ($user->level != 'admin') {
            return 'Access denied';
        }
        if ($current_sess->level == 'admin') {
            session('user')->level = 'pegawai';
        } else {
            session('user')->level = 'admin';
        }
        return redirect()->route('dashboard');
    }
}
