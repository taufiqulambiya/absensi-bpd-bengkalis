<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class Modal extends Component
{
    public $jabatans = [
        'Kabid',
        'Kasubbid',
        'Subbag',
        'Kasubbag',
        'Kasi',
        'Sekretaris',
        'Staff',
        'Lainnya'
    ];
    public $form = [
        'nama' => '',
        'jk' => 'Laki-laki',
        'tgl_lahir' => '',
        'nip' => '',
        'golongan' => '',
        'jabatan' => '',
        'jabatan_lainnya' => '',
        'bidang' => '',
        'alamat' => '',
        'no_telp' => '',
        'level' => 'pegawai',
    ];
    public $isEdit = false;
    protected $listeners = ['fillEdit' => 'fillEdit', 'resetForm' => 'resetForm'];
    public function render()
    {
        return view('livewire.users.modal');
    }

    public function resetForm()
    {
        $this->form = [
            'nama' => '',
            'jk' => 'Laki-laki',
            'tgl_lahir' => '',
            'nip' => '',
            'golongan' => '',
            'jabatan' => '',
            'jabatan_lainnya' => '',
            'bidang' => '',
            'alamat' => '',
            'no_telp' => '',
            'level' => 'pegawai',
        ];
        $this->isEdit = false;
    }

    public function fillEdit($item)
    {
        $this->form = $item;
        $jabatan = $item['jabatan'];
        if (!in_array($jabatan, $this->jabatans)) {
            $this->form['jabatan'] = 'Lainnya';
            $this->form['jabatan_lainnya'] = $jabatan;
        }
        $this->isEdit = true;
    }

    public function store()
    {
        $minBirthDay = date('Y-m-d', strtotime('-15 years'));
        $maxNipLength = 18;
        $this->validate([
            'form.nama' => 'required',
            'form.tgl_lahir' => 'required|date|before:' . $minBirthDay,
            'form.nip' => 'required|numeric|digits_between:15,' . $maxNipLength . '|unique:users,nip' .
            ($this->isEdit ? ',' . $this->form['id'] : ''),
            'form.jabatan' => 'required',
            'form.jabatan_lainnya' => 'required_if:jabatan,==,Lainnya',
            'form.bidang' => 'required',
            'form.golongan' => 'required',
            'form.alamat' => 'required',
            'form.no_telp' => 'numeric|digits_between:1,15',
        ], [
                'form.nama.required' => 'Nama tidak boleh kosong',
                'form.tgl_lahir.required' => 'Tanggal lahir tidak boleh kosong',
                'form.tgl_lahir.date' => 'Tanggal lahir harus berupa tanggal',
                'form.tgl_lahir.before' => 'Tanggal lahir tidak boleh kurang dari 15 tahun',
                'form.nip.required' => 'NIP tidak boleh kosong',
                'form.nip.numeric' => 'NIP harus berupa angka',
                'form.nip.digits_between' => 'NIP harus berupa angka dengan panjang minimal 15 dan maksimal ' . $maxNipLength . ' karakter',
                'form.nip.unique' => 'NIP sudah terdaftar',
                'form.jabatan.required' => 'Jabatan tidak boleh kosong',
                'form.jabatan_lainnya.required_if' => 'Jabatan lainnya tidak boleh kosong',
                'form.bidang.required' => 'Bidang tiak boleh kosong',
                'form.golongan.required' => 'Golongan tidak boleh kosong',
                'form.alamat.required' => 'Alamat tidak boleh kosong',
                'form.no_telp.numeric' => 'No. Telp harus berupa angka',
                'form.no_telp.digits_between' => 'No. Telp harus berupa angka dengan panjang maksimal 15 karakter',
            ]);
        $payload = $this->form;
        $payload['jabatan'] = $payload['jabatan'] == 'Lainnya' ? $payload['jabatan_lainnya'] : $payload['jabatan'];
        if ($this->isEdit) {
            $user = User::find($payload['id']);

            if ($user->level == 'admin') {
                $allAdmin = User::where('level', 'admin')->get();
                if ($allAdmin->count() == 1 && $this->form['level'] != 'admin') {
                    $this->addError('form.level', 'Harap setidaknya ada 1 user dengan level admin');
                    return;
                }
            }
            if ($user->level == 'atasan') {
                $allAtasan = User::where('level', 'atasan')->get();
                if ($allAtasan->count() == 1 && $user->level == 'atasan' && $payload['level'] != 'atasan') {
                    $this->addError('form.level', 'Harap setidaknya ada 1 user dengan level atasan');
                    return;
                }
            }


            $user->update($payload);
            $this->emit('success', 'User berhasil diubah');
            $this->emit('toggleModal');
            $this->emit('refreshTable');
        } else {
            $pimpinan = User::where('level', 'atasan')->first();
            if ($this->form['level'] == 'atasan' && $pimpinan) {
                $this->addError('form.level', 'Hanya boleh ada 1 user dengan level pimpinan');
                return;
            }

            $randomPassword = $this->getRandomPassword();
            $payload['password'] = bcrypt($randomPassword);

            User::create($payload);

            $msgHtml = '<p>User baru berhasil ditambahkan, password: <b>' . $randomPassword . '</b>, beritahu user untuk segera mengganti passwordnya.</p>';
            $this->emit('successHtml', $msgHtml);
            $this->emit('toggleModal');
            $this->emit('refreshTable');
        }
    }

    private function getRandomPassword()
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyz';
        $key = '';
        for ($i = 0; $i < 8; $i++) {
            $key .= $pattern[rand(0, 35)];
        }
        return $key;
    }
}