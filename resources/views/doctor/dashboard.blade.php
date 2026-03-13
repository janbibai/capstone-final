<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Doctor Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2.5 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Doctor Dashboard</h1>
                        <p class="text-xs text-gray-500">Queue & Patient Records</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('doctor.medical-records') }}"
                        class="text-gray-600 hover:text-blue-600 font-medium text-sm">
                        Medical Records
                    </a>
                    @auth
                        <div class="hidden md:flex items-center space-x-3 text-sm">
                            <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span
                                    class="text-blue-700 font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Today's Queue</h2>
                    <p class="text-gray-500 mt-1">
                        Appointments for <span
                            class="font-semibold">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    </p>
                </div>
                <form method="GET" action="{{ route('doctor.dashboard') }}" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Date:</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    <button type="submit"
                        class="bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 transition">Go</button>
                </form>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300">
                    {{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                {{-- Today's Appointments --}}
                <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden border-l-4 border-l-blue-500">
                    <div class="absolute top-3 right-3 bg-blue-50 rounded-lg p-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Today's Appointments</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ $appointments->count() }}</p>
                </div>

                @php
                    $statusCards = [
                        'not started' => [
                            'label' => 'Not Started',
                            'borderColor' => 'border-l-gray-400',
                            'iconBg' => 'bg-gray-100',
                            'iconColor' => 'text-gray-400',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                        ],
                        'started' => [
                            'label' => 'Started',
                            'borderColor' => 'border-l-indigo-500',
                            'iconBg' => 'bg-indigo-50',
                            'iconColor' => 'text-indigo-500',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                        ],
                        'completed' => [
                            'label' => 'Completed',
                            'borderColor' => 'border-l-green-500',
                            'iconBg' => 'bg-green-50',
                            'iconColor' => 'text-green-500',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                        ],
                        'cancelled' => [
                            'label' => 'Cancelled',
                            'borderColor' => 'border-l-red-500',
                            'iconBg' => 'bg-red-50',
                            'iconColor' => 'text-red-500',
                            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                        ],
                    ];
                @endphp

                @foreach ($statusCards as $statusKey => $card)
                    <div class="relative bg-white rounded-xl shadow-sm border border-gray-100 p-5 overflow-hidden border-l-4 {{ $card['borderColor'] }}">
                        <div class="absolute top-3 right-3 {{ $card['iconBg'] }} rounded-lg p-2">
                            <svg class="w-5 h-5 {{ $card['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $card['icon'] !!}
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">{{ $card['label'] }}</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $appointments->where('status', $statusKey)->count() }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- Queue Table --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Enhanced Header --}}
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-white to-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-100 rounded-lg p-2.5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Patients in Queue</h3>
                            <p class="text-xs text-gray-400">Review and add diagnosis</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-sm font-semibold px-3 py-1.5 rounded-full border border-blue-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $appointments->count() }} Total
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-blue-100">
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                        Queue #
                                    </div>
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Time
                                    </div>
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Patient
                                    </div>
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        Service
                                    </div>
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Status
                                    </div>
                                </th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-blue-800 uppercase tracking-wider">
                                    <div class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                        </svg>
                                        Actions
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($appointments as $appointment)
                                @php
                                    $status = $appointment->status;
                                    $rowBorder = match ($status) {
                                        'started'   => 'border-l-4 border-l-indigo-400',
                                        'completed'  => 'border-l-4 border-l-green-400',
                                        'cancelled'  => 'border-l-4 border-l-red-400',
                                        default      => 'border-l-4 border-l-gray-300',
                                    };
                                    $badgeClasses = match ($status) {
                                        'started'   => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                                        'completed'  => 'bg-green-50 text-green-700 border border-green-200',
                                        'cancelled'  => 'bg-red-50 text-red-700 border border-red-200',
                                        default      => 'bg-gray-50 text-gray-600 border border-gray-200',
                                    };
                                    $badgeIcon = match ($status) {
                                        'started'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                        'completed'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                        'cancelled'  => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                        default      => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />',
                                    };
                                @endphp
                                <tr class="{{ $rowBorder }} hover:bg-gray-50/80 transition-colors duration-150">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 font-mono text-sm font-semibold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                            </svg>
                                            Q-{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 text-gray-600">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($appointment->patient)
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                    <span class="text-blue-700 font-bold text-xs">
                                                        {{ strtoupper(substr($appointment->patient->first_name, 0, 1)) }}{{ strtoupper(substr($appointment->patient->last_name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-800 text-sm">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 text-gray-400 italic text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                </svg>
                                                No patient
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 text-gray-600 text-sm">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                            {{ optional($appointment->service)->name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClasses }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $badgeIcon !!}
                                            </svg>
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($appointment->patient && $appointment->status === 'started')
                                            <a href="{{ route('doctor.patients.add-record', ['patient' => $appointment->patient, 'appointment_id' => $appointment->id]) }}"
                                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-xs font-semibold px-3.5 py-1.5 rounded-lg hover:bg-blue-700 hover:shadow-md transition-all duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Add Diagnosis
                                            </a>
                                        @elseif($appointment->patient)
                                            <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Available when "started"
                                            </span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-4">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            <p class="text-gray-500 font-medium">No appointments scheduled</p>
                                            <p class="text-gray-400 text-sm mt-1">There are no appointments for this date.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
