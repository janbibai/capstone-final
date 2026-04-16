<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Staff Dashboard</title>
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 selection:bg-emerald-100 selection:text-emerald-900">
    <!-- Staff Dashboard Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Title Section -->
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 p-2 rounded-xl shadow-sm shadow-emerald-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight">Staff Dashboard</h1>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Queue Management</p>
                    </div>
                </div>

                <!-- User Info & Logout -->
                <div class="flex items-center space-x-4">
                    @if (auth()->check() && strtolower(trim(auth()->user()->staff->position ?? '')) === 'admin')
                        <a href="{{ route('rhu.dashboard') }}"
                            class="text-slate-500 hover:text-emerald-600 font-medium text-sm transition-colors">
                            RHU Dashboard
                        </a>
                    @endif
                    @auth
                        <div class="hidden md:flex items-center space-x-3 text-sm pl-4 border-l border-slate-200">
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                                @if (auth()->user()->staff)
                                    <p class="text-xs text-slate-500">{{ auth()->user()->staff->position }}</p>
                                @endif
                            </div>
                            <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center ring-2 ring-white shadow-sm">
                                <span class="text-emerald-700 font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="text-slate-500 hover:text-slate-900 hover:bg-slate-100 p-2 rounded-lg transition-all flex items-center gap-2 group">
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

    <!-- Main Content -->
    <main class="min-h-screen py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Page Header & Actions -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Overview</h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Queue and appointments for
                        <span class="font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                    </p>
                </div>
                <form method="GET" action="{{ route('staff.dashboard') }}" class="flex items-center gap-3">
                    <div class="relative">
                        <input type="date" name="date" value="{{ $date }}"
                            class="pl-3 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all outline-none shadow-sm">
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
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <!-- Total Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    </div>
                    <p class="text-sm font-medium text-slate-500">Total Today</p>
                    <p class="mt-2 text-3xl font-extrabold text-slate-900">{{ $totalCount }}</p>
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
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                        <p class="mt-2 text-3xl font-extrabold text-slate-900">
                            {{ $statusCounts[$statusKey] ?? 0 }}
                        </p>
                    </div>
                @endforeach
            </div>

            <!-- Queue Table Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Today's Queue</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Manage patient flow and statuses</p>
                    </div>
                    <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-semibold">
                        {{ $appointments->total() }} Patients
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Vitals & Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($appointments as $appointment)
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
                                            @if ($appointment->patient->valid_id_path)
                                                <a href="{{ asset('storage/' . $appointment->patient->valid_id_path) }}" 
                                                   target="_blank"
                                                   class="inline-flex items-center text-[11px] text-emerald-600 hover:text-emerald-800 font-semibold mt-1 bg-emerald-50 px-2 py-0.5 rounded transition-colors">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View ID
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic">No patient</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ optional($appointment->service)->name ?? '-' }}
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
                                    <td class="px-6 py-4 relative">
                                        {{-- Patient Details Display & Edit Button --}}
                                        @if($appointment->weight || $appointment->height || $appointment->blood_pressure || $appointment->temperature)
                                            <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-xs text-slate-600 mb-2 min-w-[140px]">
                                                @if($appointment->weight) <div><span class="font-semibold text-slate-400">W:</span> {{ $appointment->weight }}kg</div> @endif
                                                @if($appointment->height) <div><span class="font-semibold text-slate-400">H:</span> {{ $appointment->height }}cm</div> @endif
                                                @if($appointment->blood_pressure) <div><span class="font-semibold text-slate-400">BP:</span> {{ $appointment->blood_pressure }}</div> @endif
                                                @if($appointment->temperature) <div><span class="font-semibold text-slate-400">T:</span> {{ $appointment->temperature }}°C</div> @endif
                                            </div>
                                            <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.toggle('hidden')" 
                                                class="text-[11px] text-emerald-600 hover:text-emerald-700 font-semibold uppercase tracking-wider">
                                                Edit Vitals
                                            </button>
                                        @else
                                            <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.toggle('hidden')" 
                                                class="inline-flex items-center text-xs text-emerald-600 hover:text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 transition-colors">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                Add Vitals
                                            </button>
                                        @endif
                                        
                                        {{-- Form for updating details (Modern Popover) --}}
                                        <form id="details-form-{{ $appointment->id }}" method="POST" action="{{ route('staff.appointments.updateDetails', $appointment) }}" 
                                            class="hidden mt-2 bg-white border border-slate-200 rounded-xl p-4 shadow-xl absolute z-50 w-[260px] left-6 top-full ring-1 ring-slate-900/5">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex justify-between items-center mb-3">
                                                <h4 class="text-sm font-bold text-slate-800">Patient Vitals</h4>
                                                <button type="button" onclick="document.getElementById('details-form-{{ $appointment->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                            <div class="space-y-3 text-xs">
                                                <div class="grid grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-slate-500 font-medium mb-1">Weight (kg)</label>
                                                        <input type="number" step="0.01" name="weight" value="{{ old('weight', $appointment->weight) }}" 
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                                                    </div>
                                                    <div>
                                                        <label class="block text-slate-500 font-medium mb-1">Height (cm)</label>
                                                        <input type="number" step="0.01" name="height" value="{{ old('height', $appointment->height) }}" 
                                                            class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-slate-500 font-medium mb-1">Blood Pressure</label>
                                                    <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $appointment->blood_pressure) }}" placeholder="120/80" 
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                                                </div>
                                                <div>
                                                    <label class="block text-slate-500 font-medium mb-1">Temperature (°C)</label>
                                                    <input type="number" step="0.1" name="temperature" value="{{ old('temperature', $appointment->temperature) }}" 
                                                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                                                </div>
                                                <div class="pt-2 flex gap-2">
                                                    <button type="submit" class="flex-1 bg-emerald-600 text-white font-medium px-3 py-2 rounded-lg hover:bg-emerald-700 transition-colors">Save Vitals</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($appointment->status === 'completed')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-50 text-slate-500 border border-slate-200">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                completed by Doctor
                                            </span>
                                        @else
                                            <form method="POST" action="{{ route('staff.appointments.updateStatus', $appointment) }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="bg-slate-50 border border-slate-200 text-slate-700 rounded-lg px-3 py-1.5 text-xs font-medium focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all cursor-pointer">
                                                    <option value="not started" @selected($appointment->status === 'not started')>Not started</option>
                                                    <option value="started" @selected($appointment->status === 'started')>Started</option>
                                                    <option value="cancelled" @selected($appointment->status === 'cancelled')>Cancelled</option>
                                                </select>
                                                <button type="submit" class="bg-slate-900 text-white text-xs font-semibold px-4 py-1.5 rounded-lg hover:bg-slate-800 transition-all focus:ring-2 focus:ring-slate-900/20 shadow-sm">
                                                    Update
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
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