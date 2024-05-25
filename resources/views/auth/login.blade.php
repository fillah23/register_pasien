<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <img class="wave" src="{{ asset('images/wave.png') }}">
    <div class="container">
        <div class="img">
            <img src="{{ asset('images/bg.svg') }}">
        </div>
        <div class="login-content">
            <form id="loginForm" action="{{ route('login') }}" method="POST">
              @csrf
                <img src="{{ asset('images/avatar.svg') }}">
                <h2 class="title">Welcome</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Email</h5>
                        <input type="text" class="input" name="email" id="email">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" class="input" name="password" id="password">
                    </div>
                </div>
                <input type="submit" class="btn" value="{{ __('Login') }}" name="login">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let email = document.getElementById('email').value;
            let password = document.getElementById('password').value;
            if (email === '' || password === '') {
                event.preventDefault();
                Swal.fire({
                    position: 'center',
                    icon: 'warning',
                    title: 'Email or Password cannot be empty',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });

        @error('email')
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: '{{ $message }}',
            showConfirmButton: false,
            timer: 2000
        });
        @enderror

        @error('password')
        Swal.fire({
            position: 'center',
            icon: 'warning',
            title: '{{ $message }}',
            showConfirmButton: false,
            timer: 2000
        });
        @enderror
    </script>
</body>
</html>
