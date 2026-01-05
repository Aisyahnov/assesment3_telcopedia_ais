<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="card shadow p-4" style="width: 380px;">

        <h3 class="text-center mb-4">Login</h3>

        {{-- Error message --}}
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Success message --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login.do') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">NAME</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" class="form-control" required>
            </div>

            <!-- TOMBOL MERAH -->
            <button type="submit" class="btn btn-danger w-100">Login</button>

            <p class="text-center mt-3">
                Belum punya akun? <a href="{{ route('register.show') }}">Register</a>
            </p>
        </form>

    </div>
</div>

</body>
</html>
