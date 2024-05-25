<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? "Puskesmas" }} | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{ asset('images/logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $title ?? "Puskesmas" }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Pendaftaran Pasien Header -->
          <li class="nav-header">Pendaftaran Pasien</li>
          <li class="nav-item">
            <a href="/patients/create" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>Pasien Baru</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/oldpatients/create" class="nav-link">
              <i class="nav-icon fas fa-user-clock"></i>
              <p>Pasien Lama</p>
            </a>
          </li>

          <!-- Laporan Header -->
          <li class="nav-header">Laporan</li>
          <li class="nav-item">
            <a href="/patients" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Laporan Harian</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/reports/monthly" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Laporan Bulanan</p>
            </a>
          </li>

          <!-- Grafik Kunjungan Pasien Dropdown -->
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Grafik Kunjungan Pasien
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/chart/harian" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Harian</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/chart/mingguan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mingguan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/chart/bulanan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bulanan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/chart/tahunan" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tahunan</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Pengaturan User Header -->
          @if(auth()->user()->role == 'admin')
          <li class="nav-header">Pengaturan User</li>
          <li class="nav-item">
            <a href="/users" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>User</p>
            </a>
          </li>
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <br>
      @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
@yield('js')
</body>
</html>
