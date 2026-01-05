<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4 bg-light">

<div class="container">
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

<br>
<br>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">

        {{-- LEFT: CART ITEMS --}}
        <div class="col-md-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">Produk yang dibeli</div>
                <div class="card-body">

                    <table class="table table-bordered text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>
                       @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->product->price,0,',','.') }}</td>
                                <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        {{-- RIGHT: FORM --}}
        <div class="col-md-5">
            <form method="POST" action="{{ route('checkout.save') }}" enctype="multipart/form-data">
                @csrf

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">Detail Pembayaran</div>
                    <div class="card-body">

                        {{-- ADDRESS --}}
                        <div class="mb-3">
                            <label class="form-label">Alamat Pengiriman</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>

                        {{-- PAYMENT METHOD --}}
                        <div class="mb-3">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="Transfer Bank">Transfer Bank</option>
                                <option value="E-Wallet">E-Wallet</option>
                                <option value="COD">COD</option>
                            </select>
                        </div>

                        {{-- TOTAL --}}
                        <div class="mb-3">
                            <label class="form-label">Total Pembayaran</label>
                            <input type="text" class="form-control bg-light" value="Rp {{ number_format($total,0,',','.') }}" readonly>

                            <input type="hidden" name="total" value="{{ $total }}">
                        </div>

                        <button class="btn btn-danger w-100">Bayar Sekarang</button>

                    </div>
                </div>

            </form>
        </div>

    </div>
</div>

</body>
</html>
