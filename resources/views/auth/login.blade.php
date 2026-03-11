<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Lead Generation Software' }}</title>

    <!-- Fonts and CDN for Tailwind CSS & FontAwesome -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet"> <!-- Correct CSS file -->

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .login-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
        }
        .login-card:hover {
            transform: scale(1.05);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Main Content Section (Only for login page) -->
    <main class="min-h-screen flex items-center justify-center py-12">
        <div class="login-card max-w-md mx-auto">
            <div class="text-center mb-6">
                <img src="{{ asset('/img/logo.png') }}" alt="LeadGen" class="h-16 mx-auto mb-6">
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email or Username -->
                <div class="mb-6">
                    <x-input-label for="login" :value="__('Email or Username')" class="text-lg font-semibold" />
                    <x-text-input id="login"
                                  class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300"
                                  type="text"
                                  name="login"
                                  :value="old('login')"
                                  required
                                  autofocus />
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <x-input-label for="password" :value="__('Password')" class="text-lg font-semibold" />
                    <x-text-input id="password" 
                                  class="block mt-2 w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-300"
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mb-6">
                    <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ml-2">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <!-- Forgot Password Link -->
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-blue-600 hover:text-blue-800 transition duration-200" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <!-- Login Button -->
                    <x-primary-button class="ml-3 bg-indigo-600 text-white py-2 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>