<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <!-- Bootstrap 4 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('currency.converter_form','currency.converter') ? 'active' : '' }}" href="{{ route('currency.converter_form', $role) }}">Currency Converter</a>
                </li>
                @if($role == 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.user_management_form','admin.user_update_form') ? 'active' : '' }}" href="{{ route('admin.user_management_form') }}">User Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('ip.management') ? 'active' : '' }}" href="{{ route('ip.management') }}">IP Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('currencies.export') ? 'active' : '' }}" href="{{ route('currencies.export') }}">Currencies Export</a>
                </li>
                @endif
                <li class="nav-item nav-link">{{ $username }}
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-logout" href="{{ route('logout') }}">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    @if (Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    @if (Session::has('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif

    <div class="container my-4">
        @yield('content')
        @yield('scripts')

    </div>

</body>
</html>
