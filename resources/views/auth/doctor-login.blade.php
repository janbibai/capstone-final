@extends('layouts.app')

@section('title', 'Doctor Login')
@section('hideFooter', true)
@section('hideHeader', true)

@section('content')
{{-- Full-page loading overlay --}}
<div id="login-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-white/80 backdrop-blur-sm hidden transition-opacity duration-300">
    <div class="flex flex-col items-center space-y-4">
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 rounded-full border-4 border-blue-100"></div>
            <div class="absolute inset-0 rounded-full border-4 border-transparent border-t-blue-600 animate-spin"></div>
        </div>
        <p class="text-sm font-semibold text-gray-600 tracking-wide">Signing you in…</p>
    </div>
</div>

<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center justify-center mb-2">
            <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-blue-600 text-2xl">stethoscope</span>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
            Doctor Login
        </h2>

        @if (session('success'))
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->has('throttle'))
            <div id="throttle-alert" class="mb-4 p-3 rounded-lg bg-amber-50 text-amber-800 border border-amber-300 text-sm font-medium">
                Too many login attempts. Please try again in <span id="throttle-countdown" class="font-bold">{{ $errors->first('throttle') }}</span> seconds.
            </div>
        @endif

        @if ($errors->any() && !$errors->has('throttle'))
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('doctor.login.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <div class="relative">
                    <input type="password"
                           id="password-input"
                           name="password"
                           required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 pr-11 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <button type="button" id="toggle-password"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition"
                            tabindex="-1">
                        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="remember"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-600">Remember me</span>
                </label>
            </div>

            <button id="login-btn" type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition flex items-center justify-center gap-2">
                <svg id="login-spinner" class="hidden w-5 h-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span id="login-btn-text">Login</span>
            </button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-blue-600 transition inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to home
            </a>
        </p>

    </div>
</div>

<script>
    // Password visibility toggle
    document.getElementById('toggle-password').addEventListener('click', function() {
        const input = document.getElementById('password-input');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        eyeIcon.classList.toggle('hidden', isPassword);
        eyeOffIcon.classList.toggle('hidden', !isPassword);
    });

    // Login loading state
    document.getElementById('login-form').addEventListener('submit', function() {
        const btn = document.getElementById('login-btn');
        const btnText = document.getElementById('login-btn-text');
        const spinner = document.getElementById('login-spinner');
        const overlay = document.getElementById('login-overlay');

        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        btnText.textContent = 'Signing in…';
        spinner.classList.remove('hidden');

        setTimeout(() => overlay.classList.remove('hidden'), 600);
    });

    // Throttle countdown
    (function() {
        const el = document.getElementById('throttle-countdown');
        if (!el) return;
        let seconds = parseInt(el.textContent);
        const btn = document.getElementById('login-btn');
        const btnText = document.getElementById('login-btn-text');
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btnText.textContent = 'Wait ' + seconds + 's';

        const interval = setInterval(() => {
            seconds--;
            el.textContent = seconds;
            btnText.textContent = 'Wait ' + seconds + 's';
            if (seconds <= 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btnText.textContent = 'Login';
                const alert = document.getElementById('throttle-alert');
                if (alert) alert.style.display = 'none';
            }
        }, 1000);
    })();
</script>
@endsection
