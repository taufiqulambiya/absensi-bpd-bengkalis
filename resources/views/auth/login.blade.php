@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="login_wrapper">
    <div class="animate form login_form">
        <section class="login_content">
            <form action="{{ route('auth') }}" method="POST">
                @csrf
                <h1>Masuk</h1>

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

                <div>
                    <input type="text" name="nip" id="nip" class="form-control" value="{{ old('nip') }}"
                        placeholder="Nomor Induk Pegawai" />
                </div>
                <div>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                </div>
                <div>
                    <button type="submit" class="btn btn-default submit">Masuk</button>
                </div>

                <div class="clearfix"></div>

                <div class="separator">
                    <div class="clearfix"></div>
                    <br />

                    <div>
                        <h1><i class="fa fa-files-o"></i> Sistem Absensi!</h1>
                        <p>2022 All Rights Reserved. Sistem Absensi!</p>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection