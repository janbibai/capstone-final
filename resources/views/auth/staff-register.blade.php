@extends('layouts.app')

@section('title', 'Staff Registration')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
            Staff Registration
        </h2>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('staff.register.submit') }}" class="space-y-4">
            @csrf

            {{-- Full Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name
                </label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       autofocus
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email
                </label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number
                </label>
                <input type="text"
                       name="phone"
                       value="{{ old('phone') }}"
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                       placeholder="Optional">
            </div>

            {{-- Position --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Position
                </label>
                <select name="position"
                        required
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
                    <option value="" disabled {{ old('position') ? '' : 'selected' }}>Select position</option>
                    <option value="Staff" {{ old('position') === 'Staff' ? 'selected' : '' }}>Staff</option>
                    <option value="Doctor" {{ old('position') === 'Doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="Pharmacy" {{ old('position') === 'Pharmacy' ? 'selected' : '' }}>Pharmacy</option>
                </select>
            </div>

            {{-- Department --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Department
                </label>
                <select name="department_id"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
                    <option value="" {{ old('department_id') ? '' : 'selected' }}>None</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password
                </label>
                <input type="password"
                       name="password"
                       required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Confirm Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none">
            </div>

            <button type="submit"
                    class="w-full bg-green-600 text-white py-2.5 rounded-xl font-semibold hover:bg-green-700 transition">
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a href="{{ route('staff.login') }}" class="text-green-600 font-semibold hover:underline">
                Login here
            </a>
        </p>
    </div>
</div>
@endsection
