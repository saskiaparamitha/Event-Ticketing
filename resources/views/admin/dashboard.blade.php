<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin | Dashboard Event</title>

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
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
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
            <h1 class="m-0 font-weight-bold text-dark">Dashboard Overview</h1>
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
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info shadow">
              <div class="inner">
                <h3>{{ $events->count() }}</h3>
                <p>Total Event</p>
              </div>
              <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success shadow">
              <div class="inner">
                <h3>{{ $events->sum('quota') }}</h3>
                <p>Total Slot Tiket</p>
              </div>
              <div class="icon"><i class="fas fa-ticket-alt"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning shadow">
              <div class="inner">
                <h3>{{ $ticketOrders->where('status', 'pending')->count() }}</h3>
                <p>Pemesanan Pending</p>
              </div>
              <div class="icon"><i class="fas fa-clock"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger shadow">
              <div class="inner">
                <h3>{{ $ticketOrders->where('status', 'approved')->sum('quantity') }}</h3>
                <p>Tiket Terjual</p>
              </div>
              <div class="icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold">Daftar Event</h3>
                <div class="card-tools">
                  <button class="btn btn-primary btn-sm rounded-pill px-3" data-toggle="modal" data-target="#modalTambahEvent">
                    <i class="fas fa-plus mr-1"></i> Tambah Event
                  </button>
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-valign-middle text-center">
                  <thead class="bg-light">
                    <tr>
                      <th>Nama Event</th>
                      <th>Harga</th>
                      <th>Waktu & Lokasi</th>
                      <th>Kuota</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($events as $event)
                    <tr>
                      <td class="font-weight-bold text-left pl-4">{{ $event->name }}</td>
                      <td><span class="text-success font-weight-bold">Rp {{ number_format($event->price, 0, ',', '.') }}</span></td>
                      <td>
                        <small class="d-block text-muted text-left">
                            <i class="fas fa-calendar-alt fa-fw"></i> {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                        </small>
                        <small class="d-block text-muted text-left">
                            <i class="fas fa-map-marker-alt fa-fw"></i> {{ $event->location }}
                        </small>
                      </td>
                      <td><span class="badge badge-info px-2 py-1">{{ $event->quota }} Tiket</span></td>
                      <td>
                        <a href="{{ route('admin.event.edit', $event) }}" class="btn btn-warning btn-xs text-white"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('admin.event.delete', $event) }}" method="POST" style="display: inline;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="5" class="py-5 text-muted">Belum ada event tersedia. Silakan klik tombol Tambah Event.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar Pemesanan Tiket -->
        <div class="row mt-4">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold">Daftar Pemesanan Tiket</h3>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-valign-middle text-center">
                  <thead class="bg-light">
                    <tr>
                      <th>Pemesan</th>
                      <th>Event</th>
                      <th>Jumlah</th>
                      <th>Tanggal Pesan</th>
                      <th>Status</th>
                      <th>Total Harga</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($ticketOrders as $order)
                    <tr>
                      <td class="font-weight-bold text-left pl-4">{{ $order->user->name }}</td>
                      <td class="text-left">{{ $order->event->name }}</td>
                      <td><span class="badge badge-info px-2 py-1">{{ $order->quantity }} Tiket</span></td>
                      <td>
                        <small class="d-block text-muted">
                            <i class="fas fa-calendar-alt fa-fw"></i> {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                        </small>
                        <small class="d-block text-muted">
                            <i class="fas fa-clock fa-fw"></i> {{ \Carbon\Carbon::parse($order->order_date)->format('H:i') }}
                        </small>
                      </td>
                      <td>
                        @if($order->status == 'pending')
                          <span class="badge badge-warning px-2 py-1">Menunggu</span>
                        @elseif($order->status == 'approved')
                          <span class="badge badge-success px-2 py-1">Disetujui</span>
                        @elseif($order->status == 'rejected')
                          <span class="badge badge-danger px-2 py-1">Ditolak</span>
                        @endif
                      </td>
                      <td><span class="text-success font-weight-bold">Rp {{ number_format($order->quantity * $order->event->price, 0, ',', '.') }}</span></td>
                      <td>
                        @if($order->status == 'pending')
                          <button type="button" class="btn btn-success btn-xs" title="Setujui" onclick="approveOrder({{ $order->id }})">
                            <i class="fas fa-check"></i> Setujui
                          </button>
                          <button type="button" class="btn btn-danger btn-xs" title="Tolak" onclick="rejectOrder({{ $order->id }})">
                            <i class="fas fa-times"></i> Tolak
                          </button>
                        @else
                          <span class="text-muted small">-</span>
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="7" class="py-5 text-muted">Belum ada pemesanan tiket.</td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

  <footer class="main-footer text-sm text-center">
    <strong>Copyright &copy; 2026 <a href="#">EventTicketing</a>.</strong> All rights reserved.
  </footer>
</div>

<div class="modal fade" id="modalTambahEvent" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-plus-circle mr-2"></i>Buat Event Baru</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.event.store') }}" method="POST">
        @csrf
        <div class="modal-body py-4">
          <div class="form-group">
            <label class="font-weight-bold">Nama Event</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Konser Jazz Jakarta" required>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">Harga Tiket (Rp)</label>
                    <input type="number" name="price" class="form-control" placeholder="150000" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="font-weight-bold">Total Kuota</label>
                    <input type="number" name="quota" class="form-control" placeholder="100" required>
                </div>
            </div>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Tanggal Event</label>
            <input type="date" name="date" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="font-weight-bold">Lokasi</label>
            <input type="text" name="location" class="form-control" placeholder="Nama Gedung atau Alamat" required>
          </div>
        </div>
        <div class="modal-footer bg-light justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold">Simpan Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function approveOrder(orderId) {
    Swal.fire({
        title: 'Konfirmasi Approve',
        text: "Apakah Anda yakin ingin menyetujui pemesanan ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Setujui!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/ticket-order/${orderId}/approve`,
                type: 'POST',
                success: function(response) {
                    Swal.fire('Berhasil!', response.success || 'Pemesanan berhasil disetujui!', 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                }
            });
        }
    });
}

function rejectOrder(orderId) {
    Swal.fire({
        title: 'Konfirmasi Reject',
        text: "Apakah Anda yakin ingin menolak pemesanan ini? Kuota akan dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/ticket-order/${orderId}/reject`,
                type: 'POST',
                success: function(response) {
                    Swal.fire('Berhasil!', response.success || 'Pemesanan berhasil ditolak!', 'success')
                        .then(() => location.reload());
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
                }
            });
        }
    });
}
</script>
</body>
</html>