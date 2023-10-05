<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Mememaza</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
	<meta name="get-url" content="{{ url('/') }}">
	<?php $path = ''; ?>
    <!-- Scripts -->
	
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  
  <!-- JQVMap -->
  <!--<link rel="stylesheet" href="{{ cdn('admin/plugins/jqvmap/jqvmap.min.css') }}">-->
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ cdn($path.'admin/plugins/summernote/summernote-bs4.min.css') }}">
  
  @stack('style')
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="/admin/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>
  
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <span class="fas fa-th-large"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
          <a href="{{ url('changepassword') }}" class="dropdown-item">
            <span class="text-muted text-sm">Change Password</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.logout') }}" class="dropdown-item"  onclick="event.preventDefault();
										 document.getElementById('logout-form').submit();">
            
            <span class="text-muted text-sm">Logout</span>
          </a>
        </div>
      </li>
      <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
			@csrf
		</form>
    </ul>
  </nav>
  <!-- /.navbar -->

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
	@include('admin.layouts.left-menu')
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @include('admin.layouts.header')
    <!-- /.content-header -->

    <!-- Main content -->
	@yield('content')
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('admin.layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
		<h5>Customize AdminLTE</h5>
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


<!-- jQuery -->
<script src="{{ cdn($path.'admin/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ cdn($path.'admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ cdn($path.'admin/plugins/bootstrap/js/bootstrap.bundle.min.j') }}s"></script>
<!-- ChartJS -->
<script src="{{ cdn($path.'admin/plugins/chart.js/Chart.min.js') }}"></script>

<!-- DataTables  & Plugins -->
<script src="{{ cdn($path.'admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<!-- Sparkline -->
<script src="{{ cdn($path.'admin/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap 
<script src="{{ cdn('admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ cdn('admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>-->
<!-- jQuery Knob Chart -->
<script src="{{ cdn($path.'admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ cdn($path.'admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ cdn($path.'admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ cdn('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ cdn($path.'admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ cdn($path.'admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ cdn($path.'admin/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="{{ cdn('admin/dist/js/demo.js') }}"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ cdn($path.'admin/dist/js/pages/dashboard.js') }}"></script>
<script src="{{ cdn($path.'admin/admin.js') }}"></script>
@stack('scripts')

</body>
</html>