<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2 class="text-center mb-4">Register</h2>
            
            <!-- Display error messages -->
            @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            <form method="post" action="{{ route('register') }}">
                @csrf <!-- CSRF token for security -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                    @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">Register</button>
            </form>
            <div class="text-center mt-3">
                <a href="{{ route('showLogin') }}" class="btn btn-secondary">Login</a>
                <a href="{{route('showForgotPassword')}}" class="btn btn-link mt-2">Forgot Password?</a>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
