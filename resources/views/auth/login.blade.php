@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="login_wrapper">
    <h4 class="text-center">Sistem Informasi Absensi Pegawai</h1>
        <div class="card">
            <div class="card-body">
                <p class="text-center">Masuk Untuk Memulai Sistem</p>
                <form action="{{ route('auth') }}" method="POST">
                    @csrf

                    @if(Session::has('error'))
                    <div class="alert alert-danger text-left">
                        <span class="d-block">{{ Session::get('error') }}</span>
                    </div>
                    @endif
                    @if ($errors->any())
                    <div class="alert alert-danger text-left">
                        @foreach ($errors->all() as $error)
                        <span class="d-block">{{ $error }}</span>
                        @endforeach
                    </div>
                    @endif

                    <div class="form-group">
                        <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip') }}"
                            placeholder="Nomor Induk Pegawai" />
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Password" />
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block submit">Masuk</button>
                    </div>

                    <hr>
                    <p class="text-center">2022 All Rights Reserved. Sistem Absensi, Bengkalis, Riau.</p>
                </form>
            </div>
        </div>
</div>
@endsection