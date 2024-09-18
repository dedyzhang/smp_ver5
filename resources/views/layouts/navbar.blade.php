<nav>
    <h5 class="nav-page-title open-sidebar"><i class="fa-solid fa-bars"></i></h5>
    <div class="navbar-menu">
        {{-- <div class="navbar-menu-list"><i class="fa-solid fa-bell"></i></div>
        <div class="navbar-menu-list"><i class="fa-solid fa-gear"></i></div> --}}
        <div class="navbar-menu-list has-dropdown" data-target="#navbar-profile-menu" data-width="150">
            <span class="user-name me-1">{{$account->users->access && $account->users->access == "orangtua" ? Str::limit("Ortu ".$account->siswa->nama,15) : Str::limit($account->nama,15)}}
            </span>
            @if ($account->users->access && $account->users->access == "orangtua")
                <img src="{{$account->siswa->jk == 'l' ? asset('img/teacher-boy.png') : asset('img/teacher-girl.png')}}" class="user-image ">
            @else
                <img src="{{$account->jk == 'l' ? asset('img/teacher-boy.png') : asset('img/teacher-girl.png')}}" class="user-image ">
            @endif
            <div class="c-contain" id="navbar-profile-menu">
                <ul class="navbar-submenu">
                    <li class="navbar-submenu-list">
                        <a href="#">Manage Profile</a>
                    </li>
                    <li class="navbar-submenu-list">
                        <a href="#" class="ganti-password">Change Password</a>
                    </li>
                    <li class="navbar-submenu-list">
                        <a href="#" class="sign-out">Sign Out</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<script>
    $('.sign-out').click(function() {
        var logout = function() {
            loading();
            var token = '{{csrf_token()}}';
            $.ajax({
                type: "POST",
                url: "/logout",
                data: {
                    "_token": token
                },
                success: function() {
                    setTimeout(() => {
                        removeLoading();
                        cAlert("green","sukses","Akun Berhasil Logout",true,'/login');
                    }, 500);
                },
                error: function(data){
                    var errors = data.responseJSON;
                    console.log(errors);
                }
            })
        }
        cConfirm("Perhatian","Logout dari Aplikasi ini",logout);
    });
    $('.ganti-password').click(function() {
        window.location.href="{{route('ganti.password')}}";
    });
</script>
