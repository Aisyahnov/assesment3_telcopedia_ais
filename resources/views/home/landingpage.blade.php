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


<!-- HERO / CAROUSEL -->
<div class="container my-4">
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="p-4">
              <h1 class="display-6 text-danger">Find Quality Used Items at Affordable Prices!</h1>
              <p class="lead">Look for high-quality used goods at pocket-friendly prices. Start shopping now!</p>
              <a href="{{ route('cart.index') }}" class="btn btn-danger">Start Shopping</a>
            </div>
          </div>
          <div class="col-lg-5">
            <img src="{{ asset('images/hero1.jpg') }}" class="img-fluid rounded hero-card" alt="hero">
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <div class="p-4">
              <h2 class="text-danger">Sustainable & Affordable</h2>
              <p class="mb-3">Give items a second life and support circular economy in campus community.</p>
              <a href="#products" class="btn btn-outline-danger">Browse Newest</a>
            </div>
          </div>
          <div class="col-lg-5">
            <img src="{{ asset('images/hero2.jpg') }}" class="img-fluid rounded hero-card" alt="hero2">
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>
</div>

<!-- CATEGORIES -->
<div class="container mb-4">
  <h5 class="mb-3">Featured Category</h5>
  <div class="d-flex flex-row gap-2 overflow-auto pb-2">
    @foreach($categories as $cat)
      <button class="btn btn-outline-secondary category-btn" data-cat="{{ $cat }}">{{ $cat }}</button>
    @endforeach
    <button class="btn btn-outline-secondary category-btn" data-cat="All">All</button>
  </div>
</div>

<!-- PRODUCTS -->
<div id="products" class="container mb-5">
  <h5 class="mb-3">Newest!</h5>

  <div class="row g-3" id="productsContainer">
    @foreach($products as $p)
      <div class="col-8 col-md-3 product-item" data-category="{{ $p['category'] }}">
        <div class="card product-card h-150">
          <img src="{{ asset('images/'.$p['image']) }}" class="card-img-top" alt="{{ $p['name'] }}" style="height:170px; object-fit:cover;">
          <div class="card-body">
            <h6 class="card-title">{{ $p['name'] }}</h6>
            <p class="text-muted small mb-1">{{ $p['category'] }}</p>
            <p class="fw-bold">Rp {{ number_format($p['price'],0,',','.') }}</p>
            <div class="d-flex gap-2">
              <form action="{{ route('cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $p['id'] }}">
                    <button class="btn btn-sm btn-danger">Tambah</button>
                </form>
              <a href="{{ route('product.show', $p->id) }}" class="btn btn-sm btn-outline-secondary">Lihat</a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

<!-- INFORMASI / BENEFITS -->
<div class="container mb-5">
  <div class="row align-items-center">
    <div class="col-md-6">
      <h4>There are many benefits you can get from shopping here!</h4>
      <ul class="mt-3">
        <li>Prices are affordable but quality is still good</li>
        <li>Reduces waste and is environmentally friendly</li>
        <li>Ease of shopping online</li>
        <li>Transparency of product condition</li>
      </ul>
    </div>
    <div class="col-md-6 text-center">
      <img src="{{ asset('images/info.jpg') }}" class="img-fluid rounded" style="max-height:270px; object-fit:cover;">
    </div>
  </div>
</div>

<!-- TESTIMONIAL -->
<div class="container mb-5">
  <h5 class="mb-3">Testimonial</h5>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="testimonial p-3">
        <p>"I was skeptical about buying second-hand items online, but this platform exceeded my expectations!"</p>
        <div class="d-flex align-items-center mt-3">
          <img src="{{ asset('images/testi1.jpg') }}" width="48" class="rounded-circle me-2">
          <div>
            <div class="fw-bold">Aisyah Noviani</div>
            <small class="text-muted">Student</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial p-3">
        <p>"I love how easy it is to find gently used items at affordable prices."</p>
        <div class="d-flex align-items-center mt-3">
          <img src="{{ asset('images/testi2.jpg') }}" width="48" class="rounded-circle me-2">
          <div>
            <div class="fw-bold">Siti Amany</div>
            <small class="text-muted">Student</small>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="testimonial p-3">
        <p>"The selection is amazing! I found exactly what I needed."</p>
        <div class="d-flex align-items-center mt-3">
          <img src="{{ asset('images/testi3.jpg') }}" width="48" class="rounded-circle me-2">
          <div>
            <div class="fw-bold">Andi Bayu</div>
            <small class="text-muted">Student</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-light pt-4 pb-3">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h6>Platform jual-beli Telkom University</h6>
        <ul class="list-unstyled small">
          <li><a href="#" class="text-light">About Us</a></li>
          <li><a href="#" class="text-light">Category</a></li>
          <li><a href="#" class="text-light">Favorite</a></li>
          <li><a href="#" class="text-light">Account</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6>Customer Service</h6>
        <p class="small">Help & Support • Returns • Shipping Info • Privacy Policy</p>
        <p class="small">CS: +62 812 3456 7890 • cs@telcopedia.id</p>
      </div>
      <div class="col-md-4 text-end">
        <h6>Follow Us</h6>
        <a class="text-light me-2" href="#"><i class="fab fa-instagram fa-lg"></i></a>
        <a class="text-light me-2" href="#"><i class="fab fa-facebook fa-lg"></i></a>
        <a class="text-light" href="#"><i class="fab fa-twitter fa-lg"></i></a>
      </div>
    </div>

    <div class="text-center mt-3 small">
      &copy; 2025 Telcopedia • Built by Students
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Category filter (client-side)
  document.querySelectorAll('.category-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.category-btn').forEach(b=>b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.cat;
      document.querySelectorAll('.product-item').forEach(card => {
        if (cat === 'All' || card.dataset.category === cat) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  // default show All
  document.querySelectorAll('.category-btn').forEach(b=>{
    if (b.dataset.cat === 'All') b.classList.add('active');
  });
</script>
</body>
</html>
