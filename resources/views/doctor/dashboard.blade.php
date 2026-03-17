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

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Patients in Queue</h3>
                    <span class="text-sm text-gray-500">Total: {{ $appointments->count() }}</span>
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
                            @forelse($appointments as $appointment)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-3 font-mono">
                                        Q-{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}</td>
                                    <td class="px-4 py-3">
                                        @if ($appointment->patient)
                                            {{ $appointment->patient->first_name }}
                                            {{ $appointment->patient->last_name }}
                                        @else
                                            <span class="text-gray-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ optional($appointment->service)->name ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                            @if ($appointment->status === 'started') bg-blue-100 text-blue-700
                                            @elseif($appointment->status === 'completed') bg-green-100 text-green-700
                                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($appointment->weight || $appointment->height || $appointment->blood_pressure || $appointment->temperature)
                                            <div class="text-xs text-gray-600 space-y-0.5 min-w-[100px]">
                                                @if($appointment->weight) <div><span class="font-medium text-gray-700">W:</span> {{ $appointment->weight }} kg</div> @endif
                                                @if($appointment->height) <div><span class="font-medium text-gray-700">H:</span> {{ $appointment->height }} cm</div> @endif
                                                @if($appointment->blood_pressure) <div><span class="font-medium text-gray-700">BP:</span> {{ $appointment->blood_pressure }}</div> @endif
                                                @if($appointment->temperature) <div><span class="font-medium text-gray-700">T:</span> {{ $appointment->temperature }} °C</div> @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($appointment->patient && $appointment->status === 'started')
                                            <a href="{{ route('doctor.patients.add-record', ['patient' => $appointment->patient, 'appointment_id' => $appointment->id]) }}"
                                                class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                                                Add diagnosis
                                            </a>
                                        @elseif($appointment->patient)
                                            <span class="text-xs text-gray-400">Diagnosis available when status is
                                                "started"</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No appointments for
                                        this date.</td>
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
