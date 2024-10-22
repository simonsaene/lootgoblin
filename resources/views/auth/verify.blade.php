<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'lootgoblin') }} - Verify Your Email</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <p class="text-muted">{{ __('Before proceeding, please check your email for a verification link.') }}</p>
                    <p class="text-muted">{{ __('If you did not receive the email') }}, 
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                        </form>
                    </p>

                    <hr>

                    <form method="POST" action="{{ route('verification.verify') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="verification_token">{{ __('Enter your verification token') }}</label>
                            <input type="text" name="verification_token" id="verification_token" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Verify') }}</button>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p>{{ __('Already verified?') }}</p>
                        <a href="{{ route('login') }}" class="btn btn-link">{{ __('Login') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-..." crossorigin="anonymous"></script>
</body>
</html>
