<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Reset Password</h2>
                  <!-- Display error messages -->
                    @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                <form method="post" action="{{ route('forgot_password') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="font-weight-bold">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
                        @error('username')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="ext_password" class="font-weight-bold">Existing Password</label>
                        <input type="password" id="ext_password" name="ext_password" class="form-control" placeholder="Enter existing password" required>
                        @error('ext_password')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="new_password" class="font-weight-bold">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                        @error('new_password')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
                <div class="text-center mt-3">
                    <a href="{{ route('showLogin') }}" class="btn btn-link">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
