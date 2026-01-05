<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Telcopedia - Landing</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    .hero-card { border-radius: 12px; overflow: hidden; }
    .product-card { border-radius: 10px; transition: transform .15s; }
    .product-card:hover { transform: translateY(-6px); box-shadow:0 10px 20px rgba(0,0,0,0.08); }
    .category-btn.active { border-color:#9F1521; color:#9F1521; }
    .testimonial { background:#fff; border-radius:10px; padding:20px; box-shadow:0 6px 18px rgba(0,0,0,0.04); }
  </style>
</head>
<body class="bg-light">

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

<body class="p-5">
<!-- Body -->
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- TABLE CART --}}
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
        <tr>
            <th>Gambar</th>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        </thead>

        <tbody>
        @forelse($items as $item)
            <tr>
                <td><img src="{{ asset('images/'.$item->product->image) }}" width="60"></td>

                <td>{{ $item->product->name }}</td>

                <td>
                    <form method="POST" action="{{ route('cart.update') }}">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="number" name="quantity"
                               value="{{ $item->quantity }}"
                               min="1" max="{{ $item->product->stock }}"
                               class="form-control text-center">
                        <button type="submit" class="btn btn-sm btn-warning mt-1">Update</button>
                    </form>
                </td>

                <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>

                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>

                <td>{{ $item->product->stock }}</td>

                <td>
                    <form method="POST" action="{{ route('cart.remove') }}">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-muted">Keranjang masih kosong</td>
            </tr>
        @endforelse
        </tbody>
    </table>


    {{-- COUPON --}}
    <div class="row mt-4">

        <div class="col-md-6">
            <h5>🎟️ COUPON</h5>
            <form method="POST" action="{{ route('cart.applyCoupon') }}">
                @csrf
                <input type="text" name="coupon_code" placeholder="Masukkan kode kupon" class="form-control mb-2">
                <button type="submit" class="btn btn-dark">Apply Coupon</button>
            </form>
        </div>

        <div class="col-md-6">
            <h5>💰 ORDER SUMMARY</h5>

            <p>Subtotal: <b>Rp {{ number_format($subtotal, 0, ',', '.') }}</b></p>
            <p>Biaya Admin: Rp {{ number_format($adminFee, 0, ',', '.') }}</p>
            <p>Diskon: Rp {{ number_format($discount, 0, ',', '.') }}</p>

            <hr>
            <h4>Total: <span class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</span></h4>

            <a href="{{ route('checkout.index') }}" class="btn btn-success mt-2">Proceed to Checkout</a>

        </div>

    </div>

</div>
</body>
</html>
