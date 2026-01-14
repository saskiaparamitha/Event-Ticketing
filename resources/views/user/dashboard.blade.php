<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>User | Cari Tiket Event</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  
  <style>
    .event-card { transition: transform .2s; border: none; border-radius: 15px; }
    .event-card:hover { transform: scale(1.02); box-shadow: 0 10px 20px rgba(0,0,0,.12), 0 4px 8px rgba(0,0,0,.06); }
    .content-wrapper { background-color: #f4f6f9; }
  </style>
</head>
<body class="hold-transition layout-top-nav">
<div class="wrapper">

  <nav class="main-header navbar navbar-expand-md navbar-light navbar-white border-bottom shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand">
        <span class="brand-text font-weight-bold text-primary"><i class="fas fa-ticket-alt"></i> EventTicketing</span>
      </a>

      <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                <i class="fas fa-user-circle mr-1"></i> {{ Auth::User()->name }} 
            </a>
            <ul class="dropdown-menu border-0 shadow">
              <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="content-wrapper">
    <div class="content-header py-5 bg-primary text-white mb-4">
      <div class="container text-center">
        <h1 class="display-4 font-weight-bold">Temukan Event Seru!</h1>
        <p class="lead">Pesan tiket event favoritmu dengan mudah, aman, dan cepat.</p>
      </div>
    </div>

    <div class="content">
      <div class="container">

        @if(session('success'))
            <script>
                window.onload = function() {
                    Swal.fire('Berhasil!', "{{ session('success') }}", 'success');
                }
            </script>
        @endif

        @if(session('error'))
            <script>
                window.onload = function() {
                    Swal.fire('Gagal!', "{{ session('error') }}", 'error');
                }
            </script>
        @endif

        <div class="row">
          @forelse($events as $event)
          <div class="col-md-4 mb-4">
            <div class="card h-100 event-card shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-between">
                    <span class="badge badge-pill badge-primary mb-2">Tersedia</span>
                    <span class="text-muted small"><i class="far fa-calendar-alt"></i> {{ $event->created_at->diffForHumans() }}</span>
                </div>
                <h4 class="card-title font-weight-bold mb-3 text-dark">{{ $event->name }}</h4>
                <p class="card-text text-muted small">Dapatkan akses eksklusif dan pengalaman tak terlupakan di event ini. Amankan tiketmu sekarang!</p>
                
                <div class="py-3 border-top border-bottom my-3 bg-light px-2 rounded">
                    <h5 class="text-success font-weight-bold mb-0">Rp {{ number_format($event->price, 0, ',', '.') }}</h5>
                    <small class="text-muted font-italic">Sisa Kuota: <strong>{{ $event->quota }} Tiket</strong></small>
                </div>

                <form id="form-book-{{ $event->id }}" action="{{ route('user.book', $event->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <label class="small text-muted">Jumlah Tiket:</label>
                        <input type="number" name="quantity" value="1" min="1" max="{{ $event->quota }}" class="form-control form-control-sm" required>
                    </div>
                    <button type="button" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2" 
                            onclick="confirmBooking('{{ $event->name }}', {{ $event->id }})">
                        <i class="fas fa-shopping-cart mr-1"></i> Pesan Tiket Sekarang
                    </button>
                </form>

              </div>
            </div>
          </div>
          @empty
          <div class="col-12 text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h3 class="text-muted">Maaf, saat ini belum ada event yang tersedia.</h3>
            <p>Silakan kembali lagi nanti!</p>
          </div>
          @endforelse
        </div>

        <!-- Riwayat Pemesanan -->
        <div class="row mt-5">
          <div class="col-12">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white py-3">
                <h3 class="card-title font-weight-bold text-primary"><i class="fas fa-history mr-2"></i>Riwayat Pemesanan Tiket</h3>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-hover table-valign-middle text-center">
                  <thead class="bg-light">
                    <tr>
                      <th>Nama Event</th>
                      <th>Jumlah Tiket</th>
                      <th>Tanggal Pesan</th>
                      <th>Status</th>
                      <th>Total Harga</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($myTickets as $ticket)
                    <tr>
                      <td class="font-weight-bold text-left pl-4">{{ $ticket->event->name }}</td>
                      <td><span class="badge badge-info px-2 py-1">{{ $ticket->quantity }} Tiket</span></td>
                      <td>
                        <small class="d-block text-muted">
                            <i class="fas fa-calendar-alt fa-fw"></i> {{ \Carbon\Carbon::parse($ticket->order_date)->format('d M Y') }}
                        </small>
                        <small class="d-block text-muted">
                            <i class="fas fa-clock fa-fw"></i> {{ \Carbon\Carbon::parse($ticket->order_date)->format('H:i') }}
                        </small>
                      </td>
                      <td>
                        @if($ticket->status == 'pending')
                          <span class="badge badge-warning px-2 py-1">Menunggu Konfirmasi</span>
                        @elseif($ticket->status == 'approved')
                          <span class="badge badge-success px-2 py-1">Disetujui</span>
                        @elseif($ticket->status == 'rejected')
                          <span class="badge badge-danger px-2 py-1">Ditolak</span>
                        @endif
                      </td>
                      <td><span class="text-success font-weight-bold">Rp {{ number_format($ticket->quantity * $ticket->event->price, 0, ',', '.') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="5" class="py-5 text-muted">
                        <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                        Belum ada riwayat pemesanan. Pesan tiket event favoritmu sekarang!
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>

  <footer class="main-footer py-4 text-center bg-white">
    <strong>Copyright &copy; 2026 <a href="#">EventTicketing</a>.</strong> All rights reserved.
  </footer>
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

function confirmBooking(eventName, eventId) {
    const quantity = document.querySelector(`#form-book-${eventId} input[name="quantity"]`).value;
    
    Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: `Apakah Anda yakin ingin memesan ${quantity} tiket untuk ${eventName}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Pesan!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Gunakan AJAX untuk menghindari CSRF expired
            const form = document.getElementById('form-book-' + eventId);
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil!', data.message, 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error!', 'Terjadi kesalahan sistem', 'error');
            });
        }
    });
}
</script>
</body>
</html>