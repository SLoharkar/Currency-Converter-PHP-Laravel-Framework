<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
            
            <!-- Display error messages -->
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <!-- Display success messages -->
            @if (Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
            @endif

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Login</h2>

            <!-- User Login Form -->
            <form id="user-login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="user-username" class="font-weight-bold">Username</label>
                    <input type="text" id="user-username" name="username" value="{{$last_username}}" class="form-control" placeholder="Enter your username" required>
                    @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="user-password" class="font-weight-bold">Password</label>
                    <input type="password" id="user-password" name="password" class="form-control" placeholder="Enter your password" required>
                    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="form-check">
                    <input type="checkbox" id="user-remember_me" name="remember" class="form-check-input">
                    <label for="user-remember_me" class="form-check-label">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-block mt-3">Login</button>
            </form>
            <div class="text-center mt-3">
                <a href="{{route('showRegister')}}" class="btn btn-secondary">Register New User</a>
                <a href="{{route('showForgotPassword')}}" class="btn btn-link mt-2">Forgot Password?</a>
            </div>
            <div class="text-center mt-3">
                <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Home</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
