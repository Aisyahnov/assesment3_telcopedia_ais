<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f5f5; }

        /* PRODUCT IMAGE */
        .product-img {
            width: 100%;
            border-radius: 12px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            object-fit: contain;
        }

        /* BADGES */
        .badge-condition {
            padding: 5px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 20px;
        }
        .badge-good { background: #d6f5d6; color: #0c7a17; }
        .badge-new { background: #dce9ff; color: #003b8e; }

        /* BUY BOX */
        .buy-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 12px;
        }

        .btn-red {
            background: #9F1521;
            color: #fff;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-red:hover { background: #7c111b; }

        .seller-box {
            background: #fff;
            border: 1px solid #ddd;
            padding: 12px 15px;
            border-radius: 12px;
        }
    </style>
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
<br>
<br>
<body>

<div class="container py-5">

    <div class="row g-4">

        <!-- LEFT: PRODUCT IMAGE -->
        <div class="col-md-5">
            <img src="{{ asset('images/' . $product->image) }}" 
                 class="product-img shadow-sm"
                 alt="{{ $product->name }}">
        </div>

        <!-- CENTER: PRODUCT INFO -->
        <div class="col-md-4">

            <!-- NAME -->
            <h2 class="fw-bold">{{ $product->name }}</h2>

            <!-- CONDITION BADGE -->
            <div class="mt-2 mb-3">
                <span class="badge-condition badge-good">Very Good</span>
                <span class="badge-condition badge-new">New</span>
            </div>

            <!-- PRICE -->
            <h3 class="fw-bold text-danger">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </h3>

            <!-- DESCRIPTION -->
            <p class="mt-3 text-secondary" style="line-height: 1.7;">
                {{ $product->description ?? 'No description available.' }}
            </p>

            <!-- SELLER BOX -->
            <div class="seller-box d-flex align-items-center mt-4">
                <img src="{{ asset('images/user.png') }}"
                     class="rounded-circle me-3"
                     width="45">
                <div>
                    <strong>{{ $product->seller_name ?? 'Penjual' }}</strong><br>
                    <span class="text-muted small">Online • Trusted Seller</span>
                </div>
            </div>

            <!-- EXTRA DETAILS -->
            <div class="mt-4">
                <h6 class="fw-bold">Spesifikasi Produk</h6>
                <ul class="text-secondary small mt-2">
                    <li>Stok: {{ $product->stock }}</li>
                    <li>Kondisi: Sangat Baik</li>
                    <li>Kategori: Elektronik</li>
                </ul>
            </div>

        </div>

        <!-- RIGHT: BUY BOX -->
        <div class="col-md-3">
            <div class="buy-box shadow-sm">

                <h6 class="fw-bold mb-3">Buy or Submit Bid</h6>

                <div class="d-flex justify-content-between">
                    <span class="text-muted small">Total stock</span>
                    <span class="fw-bold">{{ $product->stock }}</span>
                </div>

                <div class="d-flex justify-content-between mt-2">
                    <span class="text-muted small">Subtotal</span>
                    <span class="fw-bold text-danger">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                </div>

                <!-- QUANTITY -->
                <label class="fw-semibold mt-3 mb-2">Quantity</label>
                <input type="number" min="1" max="{{ $product->stock }}" value="1" class="form-control mb-3">

                <!-- ADD TO CART BUTTON -->
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="btn btn-red w-100 mb-3">+ Cart</button>
                </form>

                <!-- CHAT + WISHLIST -->
                <div class="d-flex justify-content-between mt-2">
                    <a href="{{ route('chat.start', $product->id) }}" class="text-decoration-none text-dark">
                        <i class="fa-regular fa-comment"></i> Chat
                    </a>
                    <a href="#" class="text-dark text-decoration-none">
                        <i class="fa-regular fa-heart"></i> Wishlist
                    </a>
                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>
