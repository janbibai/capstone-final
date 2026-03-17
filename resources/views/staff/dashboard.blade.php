<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff Dashboard</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-50">
    <!-- Staff Dashboard Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Title Section -->
                <div class="flex items-center space-x-3">
                    <div class="bg-green-600 p-2.5 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">Staff Dashboard</h1>
                        <p class="text-xs text-gray-500">Queue Management System</p>
                    </div>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4">
                    @if (auth()->check() && strtolower(trim(auth()->user()->staff->position ?? '')) === 'admin')
                        <a href="{{ route('rhu.dashboard') }}"
                            class="text-gray-600 hover:text-green-600 font-medium text-sm transition">
                            RHU Dashboard
                        </a>
                    @endif
                    @auth
                        <div class="hidden md:flex items-center space-x-3 text-sm">
                            <div class="text-right">
                                <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                @if (auth()->user()->staff)
                                    <p class="text-xs text-gray-500">{{ auth()->user()->staff->position }}</p>
                                @endif
                            </div>
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <span class="text-green-700 font-semibold text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
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

    <!-- Main Content -->
    <main class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Staff Dashboard</h2>
                    <p class="text-gray-500 mt-1">
                        Queue and appointments for
                        <span class="font-semibold">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    </p>
                </div>
                <form method="GET" action="{{ route('staff.dashboard') }}" class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Select date:</label>
                    <input type="date" name="date" value="{{ $date }}"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                    <button type="submit"
                        class="bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Go
                    </button>
                </form>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300">
                    {{ session('success') }}
                </div>
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

            <div class="grid md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <p class="text-sm text-gray-500">Today&apos;s appointments</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800">{{ $totalCount }}</p>
                </div>

                @php
                    $statusLabels = [
                        'not started' => 'Not started',
                        'started' => 'Started',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ];
                @endphp

                @foreach ($statusLabels as $statusKey => $label)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                        <p class="text-sm text-gray-500">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-bold text-gray-800">
                            {{ $statusCounts[$statusKey] ?? 0 }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Today&apos;s Queue</h3>
                    <span class="text-sm text-gray-500">Total: {{ $appointments->total() }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Queue #</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Time</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Patient</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Service</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Details</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($appointments as $appointment)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono text-sm">
                                        Q-{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($appointment->patient)
                                            {{ $appointment->patient->first_name }}
                                            {{ $appointment->patient->last_name }}
                                        @else
                                            <span class="text-gray-400 italic">No patient</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ optional($appointment->service)->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $status = $appointment->status;
                                            $badgeClasses = match ($status) {
                                                'started' => 'bg-blue-100 text-blue-700',
                                                'completed' => 'bg-green-100 text-green-700',
                                                'cancelled' => 'bg-red-100 text-red-700',
                                                default => 'bg-gray-100 text-gray-700',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $badgeClasses }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 relative">
                                        {{-- Patient Details Display & Edit Button --}}
                                        @if($appointment->weight || $appointment->height || $appointment->blood_pressure || $appointment->temperature)
                                            <div class="text-xs text-gray-600 mb-1 space-y-0.5 min-w-[100px]">
                                                @if($appointment->weight) <div><span class="font-medium">W:</span> {{ $appointment->weight }} kg</div> @endif
                                                @if($appointment->height) <div><span class="font-medium">H:</span> {{ $appointment->height }} cm</div> @endif
                                                @if($appointment->blood_pressure) <div><span class="font-medium">BP:</span> {{ $appointment->blood_pressure }}</div> @endif
                                                @if($appointment->temperature) <div><span class="font-medium">T:</span> {{ $appointment->temperature }} °C</div> @endif
                                            </div>
                                            <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.toggle('hidden')" class="text-xs text-green-600 hover:text-green-800 font-medium">Edit Details</button>
                                        @else
                                            <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.toggle('hidden')" class="text-xs text-green-600 hover:text-green-800 font-medium">+ Add Details</button>
                                        @endif
                                        
                                        {{-- Form for updating details (hidden by default) --}}
                                        <form id="details-form-{{ $appointment->id }}" method="POST" action="{{ route('staff.appointments.updateDetails', $appointment) }}" class="hidden mt-2 bg-white border border-gray-200 rounded-lg p-3 shadow-md absolute z-10 w-[220px] left-0 top-full">
                                            @csrf
                                            @method('PATCH')
                                            <div class="space-y-2 text-xs">
                                                <div>
                                                    <label class="block text-gray-600 mb-0.5">Weight (kg)</label>
                                                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $appointment->weight) }}" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-1 focus:ring-green-400 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-600 mb-0.5">Height (cm)</label>
                                                    <input type="number" step="0.01" name="height" value="{{ old('height', $appointment->height) }}" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-1 focus:ring-green-400 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-600 mb-0.5">Blood Pressure</label>
                                                    <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $appointment->blood_pressure) }}" placeholder="120/80" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-1 focus:ring-green-400 focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-gray-600 mb-0.5">Temperature (°C)</label>
                                                    <input type="number" step="0.1" name="temperature" value="{{ old('temperature', $appointment->temperature) }}" class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-1 focus:ring-green-400 focus:outline-none">
                                                </div>
                                                <div class="pt-1 flex gap-2">
                                                    <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded hover:bg-green-700 transition">Save</button>
                                                    <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.add('hidden')" class="bg-gray-100 text-gray-700 px-3 py-1.5 rounded hover:bg-gray-200 transition border border-gray-200">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($appointment->status === 'completed')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-200">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Completed by Doctor
                                            </span>
                                        @else
                                            <form method="POST"
                                                action="{{ route('staff.appointments.updateStatus', $appointment) }}"
                                                class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status"
                                                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs focus:ring-1 focus:ring-green-400 focus:outline-none">
                                                    <option value="not started" @selected($appointment->status === 'not started')>Not started
                                                    </option>
                                                    <option value="started" @selected($appointment->status === 'started')>Started</option>
                                                    <option value="cancelled" @selected($appointment->status === 'cancelled')>Cancelled
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-lg hover:bg-green-700 transition">
                                                    Update
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                        No appointments scheduled for this date.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
