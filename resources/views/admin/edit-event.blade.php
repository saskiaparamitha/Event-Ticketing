<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin | Edit Event</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light pl-2">Tiket Event Admin</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark">Edit Event</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Edit Event</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <div class="content">
      <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="icon fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold">Edit Event: {{ $event->name }}</h3>
              </div>
              <div class="card-body">
                <form action="{{ route('admin.event.update', $event) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="form-group">
                    <label class="font-weight-bold">Nama Event</label>
                    <input type="text" name="name" class="form-control" value="{{ $event->name }}" placeholder="Contoh: Konser Jazz Jakarta" required>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Harga Tiket (Rp)</label>
                            <input type="number" name="price" class="form-control" value="{{ $event->price }}" placeholder="150000" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="font-weight-bold">Total Kuota</label>
                            <input type="number" name="quota" class="form-control" value="{{ $event->quota }}" placeholder="100" required>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="font-weight-bold">Tanggal Event</label>
                    <input type="date" name="date" class="form-control" value="{{ $event->date }}" required>
                  </div>
                  <div class="form-group">
                    <label class="font-weight-bold">Lokasi</label>
                    <input type="text" name="location" class="form-control" value="{{ $event->location }}" placeholder="Nama Gedung atau Alamat" required>
                  </div>
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary px-4 font-weight-bold">Update Event</button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <footer class="main-footer text-sm text-center">
    <strong>Copyright &copy; 2026 <a href="#">EventTicketing</a>.</strong> All rights reserved.
  </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>