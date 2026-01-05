<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Bukti Pembayaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
      <img src="{{ asset('images/logo.png') }}" alt="logo" width="150" class="me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center">

        <form class="d-flex ms-3 search-form" role="search" style="flex: 1; max-width: 800px;">
          <input class="form-control me-2" type="search" placeholder="Cari produk..." aria-label="Search">
          <button class="btn btn-outline-danger" type="submit">Search</button>
        </form>

        <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Customer Service</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Category</a></li>
        <li class="nav-item"><a class="nav-link" href="#">Seller</a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('cart.index') }}"><i class="fa fa-shopping-cart"></i></a></li>

        <li class="nav-item dropdown ms-3">

          <!-- USER NAME -->
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fa fa-user-circle"></i>
            {{ Auth::check() ? Auth::user()->name : 'Guest' }}
          </a>

          <ul class="dropdown-menu dropdown-menu-end">

            @if(Auth::check())
              <li class="dropdown-item-text">Hi, <strong>{{ Auth::user()->name }}</strong></li>
              <li><hr class="dropdown-divider"></li>

              <!-- LOGOUT FORM -->
              <li>
                <form action="{{ route('logout') }}" method="POST" class="px-3">
                  @csrf
                  <button class="btn btn-sm btn-outline-danger w-100">Logout</button>
                </form>
              </li>

            @else
              <li><a class="dropdown-item" href="{{ route('login.show') }}">Login</a></li>
              <li><a class="dropdown-item" href="{{ route('register.show') }}">Register</a></li>
            @endif

          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>

<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">

    <div class="card shadow p-4" style="width: 450px;">

        <h3 class="text-center mb-4 text-danger">Upload Bukti Pembayaran</h3>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ms-3">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM UPLOAD --}}
        <form method="POST" enctype="multipart/form-data"
              action="{{ route('payment.upload.save', $order->id) }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Bukti Pembayaran</label>
                <input type="file" name="proof" class="form-control" required>
            </div>

            <button class="btn btn-danger w-100 mt-3">Upload</button>

            <p class="text-center mt-3">
                <a href="{{ route('landing') }}" class="text-decoration-none">Kembali ke Beranda</a>
            </p>
        </form>

    </div>

</div>

</body>
</html>