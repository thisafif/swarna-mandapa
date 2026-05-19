<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Swarna Mandapa</title>
    
    {{-- Google Fonts: Cormorant Garamond + DM Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'DM Sans', sans-serif;
        }

        body {
            /* Fallback background */
            background-color: #2c2416;
            
            /* Apply background image */
            background-image: url('{{ asset('images/bg-login.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 3.5rem 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            text-align: center;
            margin: 1.5rem;
        }

        .brand-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #1a150d;
            margin-bottom: 0.6rem;
            letter-spacing: 0.02em;
        }

        .brand-subtitle {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: #1a150d;
            margin-bottom: 2.5rem;
            letter-spacing: 0.02em;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 400;
            color: #111;
            margin-bottom: 0.4rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem;
            background-color: #E2E2E2;
            border: none;
            border-radius: 6px;
            font-size: 0.95rem;
            color: #333;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 0 0 2px #A67C37;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            margin-top: 1rem;
            text-align: left;
        }

        .form-check-input {
            width: 14px;
            height: 14px;
            accent-color: #A67C37;
            cursor: pointer;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        .form-check-label {
            font-size: 0.78rem;
            color: #444;
            cursor: pointer;
        }

        .btn-login {
            display: block;
            width: 100%;
            padding: 0.9rem;
            background-color: #AA822A; 
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            font-family: 'Cormorant Garamond', serif;
            letter-spacing: 0.04em;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-login:hover {
            background-color: #C09A45;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 2.5rem 1.7rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1 class="brand-title">Swarna Mandapa</h1>
        <h2 class="brand-subtitle">Login to Your Account</h2>

        <!-- Note: Update the action URL later when auth is set up -->
        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            
            @if($errors->any())
                <div style="background-color: #fdf2f2; color: #DC3545; padding: 0.8rem; border-radius: 6px; margin-bottom: 1.5rem; font-size: 0.85rem; text-align: left; border: 1px solid #f5c6cb;">
                    <i class="bi bi-exclamation-circle-fill" style="margin-right: 0.3rem;"></i> {{ $errors->first() }}
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Alamat E-mail</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Remember me</label>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>

</body>
</html>
