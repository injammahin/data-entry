<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lead Generation Software | Login' }}</title>

    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <style>
        :root {
            --bg-1: #081122;
            --bg-2: #0f172a;
            --bg-3: #111827;
            --primary-1: #4f46e5;
            --primary-2: #7c3aed;
            --primary-3: #06b6d4;
            --text-soft: rgba(255, 255, 255, 0.72);
            --border-soft: rgba(255, 255, 255, 0.14);
            --shadow-main: 0 30px 80px rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            margin: 0;
            color: #fff;
            background:
                radial-gradient(circle at 10% 20%, rgba(6, 182, 212, 0.18), transparent 20%),
                radial-gradient(circle at 85% 15%, rgba(124, 58, 237, 0.22), transparent 22%),
                radial-gradient(circle at 50% 85%, rgba(79, 70, 229, 0.18), transparent 25%),
                linear-gradient(135deg, var(--bg-1) 0%, var(--bg-2) 45%, var(--bg-3) 100%);
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), transparent 95%);
            pointer-events: none;
            z-index: 0;
        }

        .bg-blob {
            position: fixed;
            border-radius: 999px;
            filter: blur(20px);
            opacity: 0.55;
            z-index: 0;
            animation: floatBlob 9s ease-in-out infinite;
            pointer-events: none;
        }

        .blob-1 {
            width: 260px;
            height: 260px;
            top: 80px;
            left: -70px;
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.9), rgba(59, 130, 246, 0.45));
        }

        .blob-2 {
            width: 300px;
            height: 300px;
            top: 60px;
            right: -90px;
            background: linear-gradient(135deg, rgba(168, 85, 247, 0.85), rgba(236, 72, 153, 0.4));
            animation-delay: 1.2s;
        }

        .blob-3 {
            width: 220px;
            height: 220px;
            bottom: 60px;
            left: 18%;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.75), rgba(6, 182, 212, 0.35));
            animation-delay: 2s;
        }

        .page-wrap {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            padding: 24px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            width: 100%;
            max-width: 460px;
            padding: 36px 28px 30px;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.09);
            border: 1px solid rgba(255, 255, 255, 0.13);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            animation: fadeUp .8s ease;
        }

        .logo-top {
            width: 82px;
            height: 82px;
            margin: 0 auto 18px;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.10);
        }

        .logo-top img {
            max-width: 48px;
            max-height: 48px;
            object-fit: contain;
        }

        .form-head {
            text-align: center;
            margin-bottom: 28px;
        }

        .form-head h2 {
            margin: 0;
            font-size: 32px;
            font-weight: 900;
            line-height: 1.1;
            color: #fff;
        }

        .form-head p {
            margin: 10px 0 0;
            color: rgba(255, 255, 255, 0.72);
            font-size: 15px;
            line-height: 1.7;
        }

        .status-message,
        .error-box {
            border-radius: 16px;
            padding: 14px 16px;
            margin-bottom: 18px;
            font-size: 14px;
            line-height: 1.6;
        }

        .status-message {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #bbf7d0;
        }

        .error-box {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(248, 113, 113, 0.26);
            color: #fecaca;
        }

        .input-group {
            margin-bottom: 18px;
        }

        .input-label {
            display: block;
            margin-bottom: 9px;
            color: rgba(255, 255, 255, 0.92);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.01em;
        }

        .input-shell {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.55);
            font-size: 15px;
            pointer-events: none;
        }

        .modern-input {
            width: 100%;
            min-height: 56px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.07);
            color: #fff;
            padding: 14px 48px 14px 46px;
            outline: none;
            transition: all 0.28s ease;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
        }

        .modern-input::placeholder {
            color: rgba(255, 255, 255, 0.42);
        }

        .modern-input:focus {
            border-color: rgba(99, 102, 241, 0.55);
            background: rgba(255, 255, 255, 0.09);
            box-shadow:
                0 0 0 4px rgba(99, 102, 241, 0.15),
                0 14px 34px rgba(79, 70, 229, 0.14);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: none;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.78);
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .toggle-password:hover {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
        }

        .field-error {
            margin-top: 8px;
            font-size: 13px;
            color: #fca5a5;
            font-weight: 700;
        }

        .options-row {
            margin-top: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .remember-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.76);
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .remember-wrap input {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .forgot-link {
            color: #c4b5fd;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .forgot-link:hover {
            color: #ffffff;
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            min-height: 58px;
            border: none;
            border-radius: 18px;
            font-size: 16px;
            font-weight: 900;
            color: #fff;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background: linear-gradient(90deg, #06b6d4 0%, #4f46e5 45%, #7c3aed 100%);
            box-shadow:
                0 18px 40px rgba(79, 70, 229, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.18);
            transition: all 0.35s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow:
                0 22px 48px rgba(79, 70, 229, 0.34),
                inset 0 1px 0 rgba(255, 255, 255, 0.20);
        }

        .login-btn::after {
            content: "";
            position: absolute;
            top: 0;
            left: -120%;
            width: 70%;
            height: 100%;
            background: linear-gradient(100deg,
                    transparent 0%,
                    rgba(255, 255, 255, 0.18) 45%,
                    rgba(255, 255, 255, 0.40) 50%,
                    rgba(255, 255, 255, 0.16) 55%,
                    transparent 100%);
            transform: skewX(-20deg);
            transition: left 0.9s ease;
        }

        .login-btn:hover::after {
            left: 160%;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(22px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes floatBlob {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-18px) translateX(8px);
            }
        }

        @media (max-width: 640px) {
            .page-wrap {
                padding: 16px 12px;
            }

            .form-card {
                padding: 30px 20px 24px;
                border-radius: 24px;
            }

            .form-head h2 {
                font-size: 28px;
            }

            .options-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .modern-input {
                min-height: 54px;
            }

            .login-btn {
                min-height: 54px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>

<body>
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>

    <main class="page-wrap">
        <div class="form-card">
            <div class="logo-top">
                <img src="{{ asset('/img/logo.png') }}" alt="LeadGen Logo">
            </div>

            <div class="form-head">
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>

            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-box">
                    Please check your login information and try again.
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <label for="login" class="input-label">Email or Username</label>
                    <div class="input-shell">
                        <span class="input-icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus
                            autocomplete="username" class="modern-input" placeholder="Enter your email or username">
                    </div>
                    @error('login')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="input-group">
                    <label for="password" class="input-label">Password</label>
                    <div class="input-shell">
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="modern-input" placeholder="Enter your password">
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="field-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="options-row">
                    <label for="remember_me" class="remember-wrap">
                        <input id="remember_me" type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="login-btn">
                    <span>Log In</span>
                </button>
            </form>
        </div>
    </main>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                this.innerHTML = isPassword
                    ? '<i class="fas fa-eye-slash"></i>'
                    : '<i class="fas fa-eye"></i>';
            });
        }
    </script>
</body>

</html>