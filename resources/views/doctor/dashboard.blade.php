<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Doctor Dashboard</title>
    @vite('resources/css/app.css')
    <!-- Optional: Add Inter font for a more modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 selection:bg-blue-100 selection:text-blue-900">
    <!-- Doctor Dashboard Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Title Section -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-xl shadow-sm shadow-blue-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight">Doctor Dashboard</h1>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Queue & Patient Records</p>
                    </div>
                </div>

                <!-- User Info & Navigation -->
                <div class="flex items-center space-x-5">
                    <a href="{{ route('doctor.medical-records') }}"
                        class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Medical Records
                    </a>
                    
                    @auth
                        <div class="hidden md:flex items-center space-x-3 text-sm pl-5 border-l border-slate-200">
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">Dr. {{ auth()->user()->name }}</p>
                                {{-- <p class="text-[11px] text-slate-500 uppercase tracking-wider">Physician</p> --}}
                            </div>
                            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                <span class="text-blue-700 font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('staff.logout') }}" class="inline pl-2">
                            @csrf
                            <button type="submit"
                                class="text-slate-500 hover:text-rose-600 hover:bg-rose-50 p-2 rounded-lg transition-all flex items-center gap-2 group">
                                <span class="hidden sm:inline text-sm font-medium">Logout</span>
                                <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Date Filter -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Today's Queue</h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Appointments for 
                        <span class="font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    </p>
                </div>
                <form method="GET" action="{{ route('doctor.dashboard') }}" class="flex items-center gap-3">
                    <div class="relative">
                        <input type="date" name="date" value="{{ $date }}"
                            class="pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none shadow-sm">
                    </div>
                    <button type="submit"
                        class="bg-slate-900 text-white text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all focus:ring-2 focus:ring-slate-900/20 focus:outline-none">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 transition-colors bg-emerald-100/50 hover:bg-emerald-200/50 p-1.5 rounded-lg focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm relative">
                    <button type="button" onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-rose-500 hover:text-rose-700 transition-colors bg-rose-100/50 hover:bg-rose-200/50 p-1.5 rounded-lg focus:outline-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <ul class="list-disc pl-5 pr-8 space-y-1 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Total -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity text-blue-600">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium mb-1 flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-blue-500"></div> Total Patients
                    </span>
                    <span class="text-slate-900 text-3xl font-extrabold mt-1 block">{{ $stats['total'] }}</span>
                </div>
                <!-- Waiting -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity text-amber-500">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium mb-1 flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-amber-500"></div> Waiting
                    </span>
                    <span class="text-slate-900 text-3xl font-extrabold mt-1 block">{{ $stats['waiting'] }}</span>
                </div>
                <!-- Completed -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity text-emerald-500">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium mb-1 flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Completed
                    </span>
                    <span class="text-slate-900 text-3xl font-extrabold mt-1 block">{{ $stats['completed'] }}</span>
                </div>
                <!-- Cancelled -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity text-rose-500">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-slate-500 text-sm font-medium mb-1 flex items-center gap-1.5">
                        <div class="w-2 h-2 rounded-full bg-rose-500"></div> Cancelled
                    </span>
                    <span class="text-slate-900 text-3xl font-extrabold mt-1 block">{{ $stats['cancelled'] }}</span>
                </div>
            </div>

            <!-- Queue Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Patients in Queue</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Diagnose and manage patient appointments</p>
                    </div>
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100 text-xs font-semibold">
                        {{ $appointments->total() }} Scheduled
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Queue #</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Vitals</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm font-semibold text-slate-700 bg-slate-100 px-2 py-1 rounded-md">
                                            Q-{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">
                                        {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($appointment->patient)
                                            <div class="font-semibold text-slate-900">
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 font-medium">
                                        {{ optional($appointment->service)->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $status = $appointment->status;
                                            $badgeClasses = match ($status) {
                                                'started' => 'bg-blue-50 text-blue-700 ring-1 ring-blue-600/20',
                                                'completed' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
                                                'cancelled' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20',
                                                default => 'bg-slate-100 text-slate-700 ring-1 ring-slate-600/10',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold {{ $badgeClasses }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 min-w-[160px]">
                                        @if($appointment->weight || $appointment->height || $appointment->blood_pressure || $appointment->temperature)
                                            <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-xs text-slate-600">
                                                @if($appointment->weight) <div><span class="font-semibold text-slate-400">W:</span> {{ $appointment->weight }}kg</div> @endif
                                                @if($appointment->height) <div><span class="font-semibold text-slate-400">H:</span> {{ $appointment->height }}cm</div> @endif
                                                @if($appointment->blood_pressure) <div><span class="font-semibold text-slate-400">BP:</span> {{ $appointment->blood_pressure }}</div> @endif
                                                @if($appointment->temperature) <div><span class="font-semibold text-slate-400">T:</span> {{ $appointment->temperature }}°C</div> @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic bg-slate-50 px-2 py-1 rounded border border-slate-100">No vitals recorded</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($appointment->patient && $appointment->status === 'started')
                                            <a href="{{ route('doctor.patients.add-record', ['patient' => $appointment->patient, 'appointment_id' => $appointment->id]) }}"
                                                class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-blue-700 shadow-sm focus:ring-2 focus:ring-blue-500/20 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                Add Diagnosis
                                            </a>
                                        @elseif($appointment->patient && $appointment->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 text-emerald-700 text-xs font-semibold px-3 py-2 bg-emerald-50 rounded-lg border border-emerald-200 shadow-sm">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Diagnosis Completed
                                            </span>
                                        @elseif($appointment->patient)
                                            <div class="flex flex-col">
                                                <span class="text-xs text-slate-400 italic">Available when status is</span>
                                                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wide">"Started"</span>
                                            </div>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-slate-500 text-sm font-medium">No appointments scheduled for this date.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($appointments->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $appointments->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>

</html>