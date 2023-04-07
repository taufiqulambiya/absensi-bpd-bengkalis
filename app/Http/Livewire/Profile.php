<?php

namespace App\Http\Livewire;

use App\Models\MasterBidang;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;
    public $bidangs = [];
    public $user = null;
    public $form = [
        'gambar' => '',
        'nama' => '',
        'nip' => '',
        'golongan' => '',
        'jabatan' => '',
        'bidang' => '',
        'tgl_lahir' => '',
        'jk' => '',
        'alamat' => '',
        'no_telp' => '',
    ];
    public $isEdit = false;
    public function render()
    {
        return view('livewire.profile');
    }

    public function mount()
    {
        $this->user = User::findOrFail(session('user')->id);
        $this->bidangs = MasterBidang::all();

        $commonJabatan = [
            'Kabid',
            'Kasubbid',
            'Subbag',
            'Kasubbag',
            'Kasi',
            'Sekretaris',
            'Staff',
        ];

        // foreach user keys and fill form
        foreach ($this->user->getAttributes() as $key => $value) {
            if ($key == 'gambar') {
                continue;
            }
            if ($key == 'jabatan') {
                if (!in_array($value, $commonJabatan)) {
                    $this->form['jabatan'] = 'Lainnya';
                    $this->form['jabatan_lainnya'] = $value;
                } else {
                    $this->form['jabatan'] = $value;
                }
                continue;
            }
            // if key is in form
            if (array_key_exists($key, $this->form)) {
                // set form value
                $this->form[$key] = $value;
            }
        }
    }

    public function toggleEdit()
    {
        $this->isEdit = !$this->isEdit;
    }

    public function update()
    {
        $this->validate([
            'form.gambar' => isset($this->form['gambar']) ? 'image|max:1024' : '',
            'form.nama' => 'required',
            'form.nip' => 'required',
            'form.golongan' => 'required',
            'form.jabatan' => 'required',
            'form.jabatan_lainnya' => 'required_if:form.jabatan,==,Lainnya',
            'form.bidang' => 'required',
            'form.tgl_lahir' => 'required',
            'form.jk' => 'required',
            'form.alamat' => 'required',
            // 'form.no_telp' => 'required',
        ], [
                'form.gambar.image' => 'Gambar harus berupa gambar',
                'form.gambar.max' => 'Gambar maksimal 1 MB',
                'form.nama.required' => 'Nama harus diisi',
                'form.nip.required' => 'NIP harus diisi',
                'form.golongan.required' => 'Golongan harus diisi',
                'form.jabatan.required' => 'Jabatan harus diisi',
                'form.jabatan_lainnya.required_if' => 'Jabatan lainnya harus diisi',
                'form.bidang.required' => 'Bidang harus diisi',
                'form.tgl_lahir.required' => 'Tanggal lahir harus diisi',
                'form.jk.required' => 'Jenis kelamin harus diisi',
                'form.alamat.required' => 'Alamat harus diisi',
                // 'form.no_telp.required' => 'Nomor telepon harus diisi',
            ]);

        // if gambar is not empty
        if (!empty($this->form['gambar'])) {
            // upload gambar
            $this->form['gambar']->store('public/user_images');
            $this->form['gambar'] = $this->form['gambar']->hashName();
        } else {
            unset($this->form['gambar']);
        }

        $user = User::find($this->user->id);

        if ($this->form['jabatan'] == 'Lainnya') {
            $this->form['jabatan'] = $this->form['jabatan_lainnya'];
        }
        $user->update($this->form);

        $this->emit('success', 'Berhasil mengubah profil');
    }
}