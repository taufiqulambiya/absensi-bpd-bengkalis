@php
    $sess_user = Request::session()->get('user');
    $user = DB::table('users')->where('id', $sess_user->id)->first();
@endphp

<!-- top navigation -->
 <div class="top_nav">
     <div class="nav_menu">
         <div class="nav toggle">
             <a id="menu_toggle"><i class="fa fa-bars"></i></a>
         </div>
         <nav class="nav navbar-nav">
             <ul class="navbar-right">
                 <li class="nav-item dropdown open" style="padding-left: 15px;">
                     <a href="javascript:;" class="user-profile dropdown-toggle" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                         <img src="{{ empty($user->gambar) ? 'https://via.placeholder.com/100?text='.$user->nama : Storage::url('public/user_images/'.$user->gambar) }}" alt="{{ $user->nama }}">{{ $user->nama }}
                     </a>
                     <div class="dropdown-menu dropdown-usermenu pull-right" aria-labelledby="navbarDropdown">
                         <a class="dropdown-item" href="{{ route('users.index') }}"> Profile</a>
                         <a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-sign-out pull-right"></i> Keluar</a>
                     </div>
                 </li>
                 @if ($user->level === 'kabid')
                 <li class="nav-item">
                     <a href="{{ route('toggle_kabid_level') }}" class="btn btn-info btn-sm">Login Sebagai @if ($sess_user->level == 'kabid')
                        Pegawai
                     @else
                         Kabid
                     @endif</a>
                 </li>
                 @endif
                 @if ($user->level === 'admin')
                 <li class="nav-item">
                     <a href="{{ route('toggle_admin_level') }}" class="btn btn-info btn-sm">Login Sebagai @if ($sess_user->level == 'admin')
                        Pegawai
                     @else
                         Admin
                     @endif</a>
                 </li>
                 @endif
             </ul>
         </nav>
     </div>
 </div>
 <!-- /top navigation -->