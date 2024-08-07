<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Converter</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
            <div class="jumbotron text-center">
                <h1 class="display-4">Welcome to Currency Converter!</h1>
                <p class="lead">Easily convert currencies with our simple and intuitive tool.</p>
                <hr class="my-4">
                <p>Whether you're traveling, shopping online, or tracking investments, our currency converter makes it easy to stay up-to-date with the latest exchange rates.</p>

                @if (Session::has('error'))
                <div class="text-center">
                    <a class="btn btn-warning btn-lg mt-3" href="{{ route('showLogin') }}" role="button">Admin Login</a>
                </div>
                @else
                <a class="btn btn-primary btn-lg" href="{{ route('login') }}" role="button">Get Started</a>
                @endif


            </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
