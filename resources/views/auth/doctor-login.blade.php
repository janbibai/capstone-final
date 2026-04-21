@extends('layouts.app')

@section('title', 'Doctor Login')

@section('content')
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

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.login.submit') }}" class="space-y-4">
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
                <input type="password"
                       name="password"
                       required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div class="flex items-center justify-between text-sm">
                <label class="inline-flex items-center">
                    <input type="checkbox"
                           name="remember"
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="ml-2 text-gray-600">Remember me</span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">
                Login
            </button>
        </form>
    </div>
</div>
@endsection
