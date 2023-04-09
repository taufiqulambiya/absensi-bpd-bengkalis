@php
$user = Request::session()->get('user');
// $user = DB::table('users')->where('id', $sess_user->id)->first();
@endphp


<?php
$cuti_notif = 0;
$izin_notif = 0;
$dinas_luar = 0;
if ($user->level == 'kabid') {
    $cuti_notif = DB::table('cuti')->join('users', 'cuti.id_user', '=', 'users.id')->where(function($q) use($user) {
        $q->where('bidang', $user->bidang)->where('users.id', '!=', $user->id);
    })->where('status', 'pending')->count();
    // $izin_notif = DB::table('izin')->join('users', 'izin.id_user', '=', 'users.id')->where(function($q) use($user) {
    //     $q->where('bidang', $user->bidang)->whereNot('users.id', $user->id);
    // })->where('status', 'pending')->count();
    $izin_notif = DB::table('izin')
        ->join('users', 'izin.id_user', '=', 'users.id')
        ->where('bidang', $user->bidang)
        ->where('tgl_mulai', '>=', date('Y-m-d'))
        ->where('users.id', '!=', $user->id)
        ->where('status', 'pending')->count();
}

if ($user->level == 'admin') {
    $cuti_notif = DB::table('cuti')->where('status', 'accepted_kabid')->count();
    $izin_notif = DB::table('izin')->where('status', 'accepted_kabid')->count();
}

if ($user->level == 'pegawai') {
    $dinas_luar = DB::table('dinas_luar')->where('id_user', $user->id)->where('selesai', '>=', date('Y-m-d'))->get()->count();
}
if ($user->level == 'atasan') {
    $cuti_notif = DB::table('cuti')->where('status', 'accepted_admin')->count();
    $izin_notif = DB::table('izin')->where('status', 'accepted_admin')->count();
}
?>
<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{route('dashboard')}}" class="site_title"><i class="fa fa-clipboard-user"></i> <span>Sistem
                    Absensi!</span></a>
        </div>

        <div class="clearfix"></div>

        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{ empty($user->gambar) ? 'https://via.placeholder.com/100?text='.$user->nama : Storage::url('public/uploads/'.$user->gambar) }}"
                    alt="{{ $user->nama }}" class="img-circle profile_img" style="aspect-ratio: 1/1">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2>{{ $user->nama }}</h2>
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>Menu Utama</h3>

                @if($user->level === 'pegawai')
                <ul class="nav side-menu">
                    <li class="{{ Request::is('panel/dashboard') ? 'active' : '' }}"><a
                            href="{{ route('dashboard') }}"><i class="fa fa-home"></i> Dashboard</a></li>
                    <li class="{{ Request::is('panel/absensi') ? 'active' : '' }}"><a
                            href="{{ route('absensi.index') }}"><i class="fa fa-clipboard-user"></i> Absensi</a></li>
                    <li class="@if(Request::is('panel/absensi/riwayat')) active @endif"><a
                            href="{{ route('absensi.riwayat') }}"><i class="fa fa-list"></i> Riwayat</a></li>
                    <li class="{{ Request::is('panel/izin') ? 'active' : '' }}"><a href="{{ route('izin.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Izin</a></li>
                    <li class="{{ Request::is('panel/cuti') ? 'active' : '' }}"><a href="{{ route('cuti.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Cuti</a></li>
                    <li class="{{ Request::is('panel/dinas_luar') ? 'active' : '' }}"><a
                            href="{{ route('dinas_luar.index') }}"><i class="fa fa-envelope"></i> Dinas Luar
                            @if ($dinas_luar > 0)
                            <span class="badge badge-primary float-right">{{ $dinas_luar }}</span>
                            @endif
                        </a></li>
                    {{-- <li><a href="javascript:void(0)"><i class="fa fa-tower-broadcast"></i> Dinas Luar</a></li> --}}
                    <li class="{{ Request::is('panel/users/' . $user->id) ? 'active' : '' }}"><a href="{{ route('users.show', $user->id) }}"><i
                                class="fa fa-user"></i> Ubah Profil</a></li>
                </ul>
                @endif

                @if($user->level === 'admin')
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/dashboard')) active @endif"><a href="{{ route('dashboard') }}"><i
                                class="fa fa-home"></i> Dashboard</a></li>
                    <li class="@if(Request::is('panel/absensi')) active @endif"><a
                            href="{{ route('absensi.index') }}"><i class="fa fa-list"></i> Absensi</a></li>
                    <li class="@if(Request::is('panel/izin')) active @endif"><a href="{{ route('izin.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Izin
                            @if ($izin_notif > 0)
                            <span class="badge badge-primary float-right">{{ $izin_notif }}</span>
                            @endif</a></li>
                    <li class="@if(Request::is('panel/cuti')) active @endif"><a href="{{ route('cuti.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Cuti
                            @if ($cuti_notif > 0)
                            <span class="badge badge-primary float-right">{{ $cuti_notif }}</span>
                            @endif</a></li>
                    <li class="@if(Request::is('panel/dinas_luar')) active @endif"><a
                            href="{{ route('dinas_luar.index') }}"><i class="fa fa-envelope"></i> Dinas Luar</a></li>
                </ul>
                <h3>Master</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/jam_kerja')) active @endif"><a
                            href="{{ route('jam_kerja.index') }}"><i class="fa fa-calendar"></i> Jam Kerja</a></li>
                    <li class="@if(Request::is('panel/master/bidang')) active @endif"><a
                            href="{{ route('master.index_bidang') }}"><i class="fa fa-th" aria-hidden="true"></i> Bidang</a></li>
                    <li class="@if(Request::is('panel/users')) active @endif"><a href="{{ route('users.index') }}"><i
                                class="fa fa-users"></i> Data User</a></li>
                </ul>
                <h3>Settings</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/users/'. $user->id)) active @endif"><a href="{{ route('users.show', $user->id) }}"><i
                                class="fa fa-user"></i> Ubah Profil</a></li>
                    <li class="@if(Request::is('panel/settings')) active @endif"><a
                            href="{{ route('settings.index') }}"><i class="fa fa-gear"></i> Pengaturan</a></li>
                </ul>
                @endif

                @if($user->level === 'kabid')
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/dashboard')) active @endif"><a href="{{ route('dashboard') }}"><i
                                class="fa fa-home"></i> Dashboard</a></li>
                    <li class="@if(Request::is('panel/izin')) active @endif"><a href="{{ route('izin.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Izin
                            @if ($izin_notif > 0)
                            <span class="badge badge-primary float-right">{{ $izin_notif }}</span>
                            @endif
                        </a></li>
                    <li class="@if(Request::is('panel/cuti')) active @endif"><a href="{{ route('cuti.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Cuti
                            @if ($cuti_notif > 0)
                            <span class="badge badge-primary float-right">{{ $cuti_notif }}</span>
                            @endif
                        </a></li>
                </ul>
                <h3>Master</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/users')) active @endif"><a href="{{ route('users.index') }}"><i
                                class="fa fa-users"></i> Data Pegawai</a></li>
                </ul>
                <h3>Settings</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/users/'.$user->id)) active @endif"><a
                            href="{{ route('users.show', $user->id) }}"><i class="fa fa-user"></i> Ubah Profil</a></li>
                </ul>
                @endif

                @if($user->level === 'atasan')
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/dashboard')) active @endif"><a href="{{ route('dashboard') }}"><i
                                class="fa fa-home"></i> Dashboard</a></li>
                    <li class="@if(Request::is('panel/izin')) active @endif"><a href="{{ route('izin.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Izin
                            @if ($izin_notif > 0)
                            <span class="badge badge-primary float-right">{{ $izin_notif }}</span>
                            @endif
                        </a></li>
                    <li class="@if(Request::is('panel/cuti')) active @endif"><a href="{{ route('cuti.index') }}"><i
                                class="fa fa-right-from-bracket"></i> Cuti
                            @if ($cuti_notif > 0)
                            <span class="badge badge-primary float-right">{{ $cuti_notif }}</span>
                            @endif
                        </a></li>
                    <li class="@if(Request::is('panel/dinas_luar')) active @endif"><a href="{{ route('dinas_luar.index') }}"><i
                                class="fa fa-envelope"></i> Dinas Luar
                            {{-- @if ($dinas_luar_notif > 0)
                            <span class="badge badge-primary float-right">{{ $dinas_luar_notif }}</span>
                            @endif --}}
                        </a></li>
                    <li class="@if(Request::is('panel/report')) active @endif"><a href="{{ route('report.index') }}"><i
                                class="fa fa-file-pdf"></i> Laporan
                        </a></li>
                </ul>
                {{-- <h3>Master</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/users')) active @endif"><a href="{{ route('users.index') }}"><i
                                class="fa fa-users"></i> Data Pegawai</a></li>
                </ul> --}}
                <h3>Settings</h3>
                <ul class="nav side-menu">
                    <li class="@if(Request::is('panel/users/'.$user->id)) active @endif"><a
                            href="{{ route('users.show', $user->id) }}"><i class="fa fa-user"></i> Ubah Profil</a></li>
                </ul>
                @endif
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>