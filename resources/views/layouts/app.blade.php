<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <title>Koperasi Amigo</title>
    <!-- tell the browser to be responsive to screen width -->
    <meta content="width=device-width,initial-scale=1, maximum-scale=1, user-scalable=no," name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" type="text/css" 
    href="{{ asset('/admin-lte/bootstrap/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" type="text/css" 
    href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- jvectormap -->
    <link rel="stylesheet" type="text/css" 
    href="{{asset('/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">

    <!-- select2 -->
    <link rel="stylesheet" type="text/css" 
    href="{{asset('/admin-lte/plugins/select2/select2.min.css')}}">

    <!-- Datatables -->
    <link rel="stylesheet" type="text/css" 
    href="{{ asset('/admin-lte/plugins/datatables/dataTables.bootstrap.css') }}">

    <!-- theme style -->
    <link rel="stylesheet" type="text/css" 
    href="{{asset('/admin-lte/dist/css/AdminLTE.min.css')}}">

    <!-- admin lte skins. choose a skin from css/skins
    folder insetead of downloading all of them to reduce the load -->
    <link rel="stylesheet" type="text/css" 
    href="{{asset('/admin-lte/dist/css/skins/_all-skins.min.css')}}">

     <link rel="stylesheet" type="text/css" 
    href="{{asset('/admin-lte/plugins/datepicker/datepicker3.css')}}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery 2.2.3 -->
    <script src="{{ asset('/admin-lte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>


</head>
<body class="hold-transition skin-red sidebar-mini">
    <div class="wrapper">
        <!-- Main Header -->
        <header class="main-header">
            <!--  Logo -->
            <a href="{{url('home')}}" class="logo">
                <!-- minii logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <b>K</b>A
                </span>
                <!-- logo for regular state
                and mobile devices -->
                <span class="logo-lg">
                    <b>Koperasi</b>Amigo         
                </span>

            </a>


            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- sidebar toggle button -->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">
                        Toggle Navigation
                    </span>
                </a>

                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    @if(Auth::check())
                                        <img src="{{ asset('/img/admin_avatar.png') }}" class="user-image" alt="User Image"/>
                                        <!-- hidden-xs hides the username on small devices so only the images appears. -->
                                        <span class="hidden-xs">{{ auth()->user()->name }}</span>
                                    @else
                                        <!-- The user image in the navbar-->
                                        <img src="{{asset('/img/default-50x50.gif')}}" class="user-image" alt="User Image">
                                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                        <span class="hidden-xs">User</span>
                                    @endif
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    @if(Auth::check())
                                        <img src="{{asset('/img/admin_avatar.png')}}" class="img-circle" alt="User Image">
                                        <p>
                                            {{auth()->user()->name}}
                                            <small>{{auth()->user()->email}}</small>
                                        </p>
                                    @else
                                         <img src="{{ asset('/img/default-50x50.gif') }}" class="img-circle" alt="User Image">
                                        
                                         <p>
                                            User
                                            <small>Email</small>
                                        </p>
                                    @endif                                    
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    @if(Auth::check())
                                        <!-- <div class="pull-left">
                                            <a href="{{url('/settings/profile')}}" class="btn btn-default btn-flat">Profile</a>
                                        </div> -->
                                        <div class="pull-right">
                                            <a href="{{route('logout')}}" class="btn btn-default btn-flat"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" >Sign Out
                                            </a>
                                        </div>
                                    @else
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    @endif    
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>   
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar : style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    @if (Auth::check())
                        <div class="pull-left image">
                            <img src="{{ asset('/img/admin_avatar.png') }}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>{!! auth()->user()->name !!}</p>
                            <!-- Status -->
                                <a href="#">
                                    <i class="fa fa-circle text-success"></i>
                                    <?php 
                                        $roles = auth()->user()->roles()->get();
                                        foreach ($roles as $r) {
                                    ?>
                                            {{ $r->display_name }} 
                                    <?php  
                                        }
                                    ?>
                                </a>
                        </div>
                    @else
                        <div class="pull-left image">
                            <img src="{{ asset('/img/default-50x50.gif') }}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>User</p>
                            <!-- Status -->
                            <a href="#">
                                <i class="fa fa-circle text-success"></i>
                                Belum Terdaftar
                            </a>
                        </div>
                    @endif
                </div>
                <!-- Sidebar Menu -->
                <ul class="sidebar-menu">
                    <li class="header">MENU</li>
                        @if (Auth::check())
                            <!--
                            Hanya coba buat menu 'active' pakai Blade macro.
                            Tapi Lebih enak pakai Request::is()
                            -->
                             {!! Html::smartNav(url('home'), 'fa-dashboard', 'Dashboard') !!}

                            <!--
                            <li class="treeview {!! Request::is('home') ? 'active' : '' !!}">
                                <a href="{{ url('home') }}">
                                    <i class="fa fa-dashboard"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            -->

                            <!-- Optionally, you can add icons to the links -->
                           
                            <!-- <li class="treeview {!! Request::is('admin/authors*') ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-user-circle"></i>
                                    <span>Penulis</span>
                                </a>
                            </li> -->

                            <!-- <li class="treeview {!! Request::is('admin/*books*') ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-book"></i>
                                    <span>Buku</span>
                                </a>
                            </li> -->

                            <li class="treeview {!! (Request::is('admin/master*') ) ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-book"></i> <span>Master</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{!! Request::is('admin/master/departments*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/departments') }}">
                                            <i class="fa fa-building"></i> Department
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/units*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/units') }}">
                                            <i class="fa fa-building"></i> Unit
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/anggotas*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/anggotas') }}">
                                            <i class="fa fa-male"></i> Anggota
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/jabatan*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/jabatan') }}">
                                            <i class="fa fa-male"></i> Jabatan
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/keteranganpinjaman*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/keteranganpinjaman') }}">
                                            <i class="fa fa-book"></i> Keterangan Pinjaman
                                        </a>
                                    </li>
                                     <li class="{!! Request::is('admin/master/jenissimpanan*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/jenissimpanan') }}">
                                            <i class="fa fa-money"></i> Jenis Simpanan
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/jenistransaksi*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/jenistransaksi') }}">
                                            <i class="fa fa-money"></i> Jenis Transaksi
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/coagroups*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/coagroups') }}">
                                            <i class="fa fa-book"></i> COA Group
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/master/coa') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/master/coa') }}">
                                            <i class="fa fa-book"></i> COA
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="treeview {!! (Request::is('admin/transaction*') ) ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-money"></i> <span>Income</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{!! Request::is('admin/transaction/simpanan*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/transaction/simpanan') }}">
                                            <i class="fa fa-angle-right"></i>Simpanan
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/transaction/penarikan*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/transaction/penarikan') }}">
                                            <i class="fa fa-angle-right"></i>Penarikan
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview {!! (Request::is('admin/loan*') ) ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-money"></i> <span>Peminjaman</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{!! Request::is('admin/loan/peminjaman*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/loan/peminjaman') }}">
                                            <i class="fa fa-angle-right"></i>Peminjaman
                                        </a>
                                        <li class="{!! Request::is('admin/loan/angsuran*') ? 'active' : '' !!}">
                                            <a href="{{ url('/admin/loan/angsuran') }}">
                                                <i class="fa fa-angle-right"></i>Angsuran
                                            </a>
                                        </li>
                                    </li>
                                </ul>
                            </li>

                            <li class="treeview {!! (Request::is('admin/report*') ) ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-book"></i> <span>Laporan</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{!! Request::is('admin/report/reportpeminjaman*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/report/reportpeminjaman') }}">
                                            <i class="fa fa-angle-right"></i>Proyeksi Angsuran
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/report/reportrekap*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/report/reportrekap') }}">
                                            <i class="fa fa-angle-right"></i>Laporan Rekap
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/report/reportbulanrekap*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/report/reportbulanrekap') }}">
                                            <i class="fa fa-angle-right"></i>Laporan Rekap Per Bulan
                                        </a>
                                    </li>
                                    <li class="{!! Request::is('admin/report/reportsaldoperanggota*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/report/reportsaldoperanggota') }}">
                                            <i class="fa fa-angle-right"></i>Laporan Saldo Per Anggota
                                        </a>
                                    </li>
                                     <li class="{!! Request::is('admin/report/rekapperanggota*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/report/rekapperanggota') }}">
                                            <i class="fa fa-angle-right"></i>Laporan Rekap Per Anggota
                                        </a>
                                    </li>
                                </ul>
                            </li>

                           
                            @if (auth()->user()->hasRole('superadmin'))
                            <li class="treeview {!! (Request::is('admin/users*') || Request::is('admin/roles*') ) ? 'active' : '' !!}">
                                <a href="#">
                                    <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{!! Request::is('admin/users*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/users/') }}">
                                            <i class="fa fa-users"></i> Users
                                        </a>
                                    </li>
                                     <li class="{!! Request::is('admin/roles*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/roles/') }}">
                                            <i class="fa fa-cog"></i> Roles
                                        </a>
                                    </li>
                                     <li class="{!! Request::is('admin/settingcoa*') ? 'active' : '' !!}">
                                        <a href="{{ url('/admin/settingcoa/') }}">
                                            <i class="fa fa-cog"></i> Setting Coa
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <li class="treeview {!! Request::is('logout') ? 'active' : '' !!}">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i>
                                    <span>Sign out</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @else
                            <li class="treeview">
                                <a href="{{ url('/register') }}">
                                    <i class="fa fa-sign-in"></i>
                                    <span>Daftar Baru</span>
                                </a>
                            </li>

                            <li class="treeview">
                                <a href="{{ url('/login') }}">
                                    <i class="fa fa-lock"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                        @endif

                </ul>
                <!-- /.sidebar-menu --> 
            </section>
        </aside>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('dashboard')
                </h1>
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">

                <!-- Your Page Content Here -->
                @include('layouts._flash')
                @yield('content')

            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                AGI
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; {!! date("Y") !!} <a href="#">Koperasi Amigo</a>.</strong> All rights reserved.
        </footer>

        <!-- ./wrapper -->

    </div>




<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('/admin-lte/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('/admin-lte/plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/admin-lte/dist/js/app.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('/admin-lte/plugins/sparkline/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap -->
<script src="{{ asset('/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- ChartJS 1.0.1 -->
<script src="{{ asset('/admin-lte/plugins/chartjs/Chart.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('/admin-lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/admin-lte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/admin-lte/plugins/select2/select2.full.min.js') }}"></script>
<!-- Custom JS -->
<script src="{{ asset('/js/custom.js') }}"></script>
<!-- Datepicker -->




@yield('scripts')

</body>
</html>