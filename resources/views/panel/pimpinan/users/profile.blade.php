@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<style>
    .prfltbl input {
        font-size: 14px;
    }

    .image-container {
        position: relative;
    }
    .upload-image {
        display: inline-block;
        position: absolute;
        left: 50%;
        bottom: 4px;
        transform: translateX(-50%);
    }
    #user-image {
        aspect-ratio: 1/1;
    }
</style>
<div class="container body">
    <div class="main_container">
        <!-- sidebar -->
        @include('layouts.sidebar')
        @include('layouts.topbar')


        <!-- page content -->
        <div class="right_col" role="main">
            <div class="">
                <div class="page-title">
                    <div class="title_left">
                        <h3>@yield('title')</h3>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Profil Utama</h4>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-info position-absolute" style="z-index: 2" title="Perbarui" id="toggle-edit">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </button>
                                <form action="" id="profile" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $user->id }}">
                                    <table class="table table-bordered prfltbl">
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="image-container mb-3">
                                                        @if(Storage::has('public/uploads/' .
                                                        $user->gambar))
                                                        <img src="{{ Storage::url('public/uploads/'.$user->gambar) }}"
                                                            alt="Profile" class="d-block w-50 rounded-circle mx-auto img-thumbnail" id="user-image">
                                                        @else
                                                        <img src="https://via.placeholder.com/200?text={{ $user->nama }}"
                                                            alt="Profile"
                                                            class="d-block w-50 rounded-circle mx-auto img-thumbnail" id="user-image">
                                                        @endif

                                                        <div class="upload-image text-center mt-3 input">
                                                            <label for="gambar" style="cursor: pointer">
                                                                <button type="button" style="pointer-events: none" class="btn btn-primary rounded-circle">
                                                                    <i class="fas fa-camera" style="pointer-events: none"></i>
                                                                </button>
                                                            </label>
                                                            <input type="file" name="gambar" id="gambar" accept="image/*" hidden>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Nama</td>
                                                <td class="text">{{ $user->nama }}</td>
                                                <td class="input">
                                                    <input type="text" class="form-control" name="nama"
                                                        placeholder="Nama..." value="{{ $user->nama }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>NIP</td>
                                                <td class="text">{{ $user->nip }}</td>
                                                <td class="input">
                                                    <input type="text" class="form-control" name="nip"
                                                        placeholder="NIP..." value="{{ $user->nip }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Golongan</td>
                                                <td class="text">{{ $user->golongan }}</td>
                                                <td class="input">
                                                    <input type="text" class="form-control" name="golongan"
                                                        placeholder="Golongan..." value="{{ $user->golongan }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jabatan</td>
                                                <td class="text">{{ $user->jabatan }}</td>
                                                <td class="input">
                                                    <div class="form-group">
                                                        <select class="form-control" name="jabatan" id="jabatan">
                                                            <option value="Kabid" @if ($user->jabatan == 'Kabid')
                                                                selected
                                                                @endif>Kabid</option>
                                                            <option value="Kasubbid" @if ($user->jabatan == 'Kasubbid')
                                                                selected
                                                                @endif>Kasubbid</option>
                                                            <option value="Subbag" @if ($user->jabatan == 'Subbag')
                                                                selected
                                                                @endif>Subbag</option>
                                                            <option value="Kasubbag" @if ($user->jabatan == 'Kasubbag')
                                                                selected
                                                                @endif>Kasubbag</option>
                                                            <option value="Staff" @if ($user->jabatan == 'Staff')
                                                                selected
                                                                @endif>Staff</option>
                                                            <option value="-1" @if ($user->jabatan != 'Kabid' and
                                                                $user->jabatan != 'Kasubbid' and $user->jabatan !=
                                                                'Subbag' and $user->jabatan != 'Kasubbag' and
                                                                $user->jabatan != 'Staff')
                                                                selected
                                                                @endif>Lainnya</option>
                                                        </select>
                                                    </div>
                                                    <div id="jabatan-lainnya-wrapper">
                                                        @if ($user->jabatan != 'Kabid' and $user->jabatan != 'Kasubbid'
                                                        and $user->jabatan != 'Subbag' and $user->jabatan != 'Kasubbag'
                                                        and $user->jabatan != 'Staff')
                                                        <input type="text" class="form-control" name="jabatan"
                                                            id="jabatan-lainnya" placeholder="Isikan jabatan..."
                                                            value="{{ $user->jabatan }}" required>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Bidang</td>
                                                <td class="text">{{ $user->bidangs->nama }}</td>
                                                <td class="input">
                                                    <select name="jabatan" id="jabatan" class="form-control" disabled>
                                                        <option value="">-- PILIH --</option>
                                                        @foreach (DB::table('tb_bidang')->get() as $item)
                                                        <option value="{{ $item->id }}" @if ($user->bidangs->id ==
                                                            $item->id)
                                                            selected
                                                            @endif>{{ $item->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Lahir</td>
                                                <td class="text">{{ $user->tgl_lahir }}</td>
                                                <td class="input">
                                                    <input type="date" class="form-control" name="tgl_lahir"
                                                        value="{{ $user->tgl_lahir }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Kelamin</td>
                                                <td class="text">{{ $user->jk }}</td>
                                                <td class="input">
                                                    <select name="jk" id="jk" class="form-control">
                                                        <option value="">-- PILIH --</option>
                                                        <option value="Laki-laki" @if ($user->jk == 'Laki-laki')
                                                            selected
                                                            @endif>Laki-laki</option>
                                                        <option value="Perempuan" @if ($user->jk == 'Perempuan')
                                                            selected
                                                            @endif>Perempuan</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Alamat</td>
                                                <td class="text">{{ $user->alamat }}</td>
                                                <td class="input">
                                                    <input type="text" class="form-control" name="alamat"
                                                        placeholder="Alamat..." value="{{ $user->alamat }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No. Telp</td>
                                                <td class="text">{{ $user->no_telp }}</td>
                                                <td class="input">
                                                    <input type="text" class="form-control" name="no_telp"
                                                        placeholder="No. HP..." value="{{ $user->no_telp }}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <div class="text-center input">
                                                        <button class="btn btn-primary" id="btn-submit"
                                                            type="submit">Perbarui</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Perbarui Password</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('update_password', $user->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="old-password">Password Lama</label>
                                        <input type="password" class="form-control" name="old-password"
                                            id="old-password" placeholder="Password lama...">
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password Baru</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Password baru...">
                                    </div>
                                    <div class="form-group">
                                        <label for="password2">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" name="password2" id="password2"
                                            placeholder="Konfirmasi password baru...">
                                    </div>

                                    <button class="btn btn-warning" type="submit">
                                        <i class="fas fa-key"></i>
                                        <span>Perbarui Password</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- footer content -->
        <footer>
            <div class="pull-right">
                Sistem Informasi Absensi Kab. Bengkalis &copy 2022
            </div>
            <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
    </div>
</div>

<script>
    class Profile {
        init() {
            $('.input').hide();
        }
        toggleForm() {
            $('.input').toggle();
            $('.text').toggle();
        }

        // submit() {
        //     const formInputs = $('form#profile').serializeArray();
        //     const payload = formInputs.reduce((a, b) => {
        //         return { ...a, [b.name]: b.value } 
        //     }, {});
        //     const formURL = `${baseURL}/panel/users/${payload.id}`;
        //     payload.isXhr = true;
        //     console.log(payload);
        //     // $.post(formURL, {
        //     //     ...payload,
        //     // }, (res) => {
        //     //     if (res === 'ok') {
        //     //         showSuccessAlert('Profil berhasil diperbarui.', () => {
        //     //             window.location.reload();
        //     //         })
        //     //     }
        //     // });
        // }
    }

    const profile = new Profile();

    $('#toggle-edit').click(profile.toggleForm);

    profile.init();

    // $('#btn-submit').click(() => {
    //     profile.submit();
    // });
    $('#gambar').change(function () {
        const value = $(this).prop('files');
        const file = value[0];
        if (file){
          let reader = new FileReader();
          reader.onload = function(event){
            $('#user-image').attr('src', event.target.result);
          }
          reader.readAsDataURL(file);
        }
        console.log(value);
    });

    $('#jabatan').on('change', function() {
        const val = $(this).val();
        if (val === '-1') {
            $('#jabatan-lainnya-wrapper').html(`<input type="text" class="form-control" name="jabatan" id="jabatan-lainnya" placeholder="Isikan jabatan..." required>`);
        } else {
            $('#jabatan-lainnya-wrapper').html(null);
        }
    });
</script>
@endsection