 <!-- <div class="navbar-custom">
    <div class="topbar">
      <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

        <div class="logo-box">

          <a class='logo-light' href='index.html'> <img src="{{ url('public/logo.png') }}" alt="logo" class="logo-lg" height="22"> <img src="{{ url('public/admin') }}/images/logo-sm.png" alt="small logo" class="logo-sm" height="22"> </a>

          <a class='logo-dark' href='index.html'> <img src="{{ url('public/logo.png') }}" alt="dark logo" class="logo-lg" height="22"> <img src="{{ url('public/admin') }}/images/logo-sm.png" alt="small logo" class="logo-sm" height="22"> </a> </div>

        <button class="button-toggle-menu"> <i class="mdi mdi-menu"></i> </button>
      </div>
      <ul class="topbar-menu d-flex align-items-center gap-4">


        <li class="dropdown">

            <div class="button-list">
                <a href="{{ url('sale') }}"><button type="button" class="btn btn-danger rounded-pill waves-effect waves-light">
                   <i class='bx bx-plus-circle'></i> Add Sale
                </button></a>
                <a href="{{ url('purchase') }}"><button type="button" class="btn btn-info rounded-pill waves-effect waves-light">
                 <i class='bx bx-plus-circle'></i> Add purchase
                </button></a>

            </div>
        </li>

        <li class="d-none d-md-inline-block">

<input type="text" id="setcurrentdate" class="datepicker form-control" value="{{ Session::get('setcurrentdate') }}">
        </li>
        <li class="d-none d-md-inline-block">
            <button class="btn btn-dark btn-sm" onclick="setdate()">Set Current date</button>
                    </li>
        <li class="d-none d-md-inline-block"> <a class="nav-link" href="#" data-bs-toggle="fullscreen"> <i class="mdi mdi-fullscreen font-size-24"></i> </a> </li>






        <li class="nav-link" id="theme-mode"> <i class="bx bx-moon font-size-24"></i> </li>
        <li class="dropdown"> <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> <img src="{{ url('public/admin') }}/images/users/avatar-4.jpg" alt="user-image" class="rounded-circle"> <span class="ms-1 d-none d-md-inline-block"> {{ Session::get('logintype') }} <i class="mdi mdi-chevron-down"></i> </span> </a>
          <div class="dropdown-menu dropdown-menu-end profile-dropdown ">

            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome {{ Session::get('loginname') }}</h6>
            </div>

            <a href="javascript:void(0);" class="dropdown-item notify-item"> <i class="fe-settings"></i> <span>Settings</span> </a>

            <div class="dropdown-divider"></div>

            <a class='dropdown-item notify-item' href='{{ url('logout') }}'> <i class="fe-log-out"></i> <span>Logout</span> </a> </div>
        </li>
      </ul>
    </div>
  </div> -->


  <!-- manpreet -->
<style>
       .fixed-header.fix-h {
            position: fixed;
            top: 0;
            width: calc(100%  - 240px);
            z-index: 1000;
            right: 0;
            background-color: #fff;
        }
        .hamburger-menu {
            cursor: pointer;
        }
        @media (max-width: 575px) {
            h4.d-s-none {
                display: none;
            }
            .hamburger-menu {
                padding-bottom: 20px
            }
        }

</style>
<div class="fixed-header fix-h  px-2">
    <div class="container-fluid">
        <div class="main-header-info">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-4">
                    <h4 class="d-s-none">Welcome {{ Session::get('loginname') }}</h4>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-8">
                    <div class="hamburger-menu  justify-content-end d-flex" id="hamburger">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


