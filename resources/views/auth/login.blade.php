<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- LOGIN STYLE -->
    <link href="assets/css/login.css" rel="stylesheet" />
    <title>Login</title>
</head>

<body>
    <div class="login-container">
        <div class="login-form">
            <h2 class="text-center">Login</h2>

            <!-- Login formasi -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" required
                        placeholder="Enter your login:">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required
                        placeholder="Enter your password">
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Login</button>

                <!-- Parolni unutdingmi? -->
                <div class="text-center mt-3">
                    <a href="/">Forgot Your Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
