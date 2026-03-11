<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lead Generation Software' }}</title>

    <!-- Fonts and CDN for Tailwind CSS & FontAwesome -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .hero-bg {
            background: linear-gradient(135deg, #4C6EF5 0%, #6C63FF 100%);
        }
        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
        }
        .section-subtitle {
            font-size: 1.125rem;
            color: #6b7280;
        }
    </style>
</head>
<body class="bg-white antialiased">

    <!-- Header with Logo and Login -->
    <header class="bg-white shadow-md sticky top-0 z-50 border-b border-black">
        <div class="max-w-6xl mx-auto flex justify-between items-center py-4 px-6">
            <img src={{ asset('/img/logo.png') }} alt="LeadGen" class="h-16">
            <div class="text-lg">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-semibold">Login</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content Section -->
    <main>
        @yield('content')
    </main>

    <!-- Footer Section -->
    <footer class="py-6 bg-gray-800 text-white text-center border-t border-black">
        <div class="max-w-6xl mx-auto">
            <p class="text-sm mb-4">&copy; 2026 LeadGen. All Rights Reserved.</p>
            <div class="flex justify-center space-x-6">
                <a href="https://www.facebook.com" class="text-blue-500 hover:text-blue-700"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="https://www.twitter.com" class="text-blue-400 hover:text-blue-600"><i class="fab fa-twitter fa-lg"></i></a>
                <a href="https://www.linkedin.com" class="text-blue-700 hover:text-blue-900"><i class="fab fa-linkedin-in fa-lg"></i></a>
            </div>
        </div>
    </footer>

</body>
</html>