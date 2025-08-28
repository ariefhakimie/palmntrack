<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Estate Inventory')</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      display: flex;
      flex-direction: column;
      background-color: #f8f9fa;
    }

    .topbar {
      background-color: #fff;
      color: black;
      padding: 1rem 2rem;
      border: 1px solid #ccc;
      border-radius: 50px;
      width: 90%;
      max-width: 1200px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      margin: 20px auto 0 auto;
      z-index: 1000;
    }

    .main-content {
      flex-grow: 1;
      padding: 2rem;
      margin-top: 100px;
      background-color: #f8f9fa;
      overflow-y: auto;
    }

    footer {
      text-align: center;
      padding: 10px;
      background-color: #fff;
      border-top: 1px solid #ddd;
    }

    .nav-link {
      color: black !important;
      font-weight: 500;
    }

    .nav-link:hover {
      color: #28a745 !important;
    }

    .btn-outline-dark {
      font-weight: 500;
    }
  </style>

  @stack('styles')
</head>
<body>

  <!-- Topbar Navbar -->
  <nav class="topbar navbar navbar-expand-lg">
    <div class="container-fluid d-flex justify-content-between align-items-center">

      <!-- Left side: Brand and toggle -->
      <div class="d-flex align-items-center">
        <a class="navbar-brand fw-bold text-dark me-2" href="{{ url('/dashboard') }}">
          PALM N TRACK
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <!-- Right side: Links -->
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
        <ul class="navbar-nav d-flex flex-row gap-3 align-items-center">
          @isset($sections)
            @foreach ($sections as $section)
              <li class="nav-item">
                <a class="nav-link" href="{{ $section['route'] }}">
                  {{ $section['title'] }}
                </a>
              </li>
            @endforeach
          @endisset

          <!-- Navigation Links -->
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/dashboard') }}">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('commodities.fsindex') }}">Commodities</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('machinery.fsindex') }}">Machinery</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('equipment.fsindex') }}">Equipment</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('usagerecords.index') }}">Usage Records</a>
          </li>

          <!-- Only show to Admin -->
          @auth
            @if (auth()->user()->role === 'admin')
              <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.users.index') }}">Manage Users</a>
              </li>
            @endif
          @endauth

          <!-- Auth Buttons -->
          @guest
            <li class="nav-item">
              <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-sign-in-alt me-1"></i> Login
              </a>
            </li>
          @endguest

          @auth
            <li class="nav-item">
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-dark">
                  <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
              </form>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    @yield('content')
  </div>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
