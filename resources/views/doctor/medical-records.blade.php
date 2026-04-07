<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medical Records</title>
    @vite('resources/css/app.css')
    <!-- Optional: Inter font for a crisp, modern UI -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 selection:bg-blue-100 selection:text-blue-900">
    <!-- Premium Glassmorphism Header -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Breadcrumb Navigation -->
                <a href="{{ route('doctor.dashboard') }}" class="flex items-center space-x-3 group hover:opacity-90 transition-opacity">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-2 rounded-xl shadow-sm shadow-blue-200 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            Medical Records
                        </h1>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Patient History & Diagnostics</p>
                    </div>
                </a>

                <!-- User Actions -->
                <div class="flex items-center space-x-5">
                    <a href="{{ route('doctor.dashboard') }}"
                        class="text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                    @auth
                        <form method="POST" action="{{ route('staff.logout') }}" class="inline pl-5 border-l border-slate-200">
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
            
            <!-- Alerts -->
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center justify-between shadow-sm animate-in fade-in slide-in-from-top-4">
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
                <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm relative animate-in fade-in slide-in-from-top-4">
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

            <!-- Main Records Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col">
                
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Records Directory</h2>
                        <div class="text-sm text-slate-500 mt-1 flex items-center flex-wrap gap-2">
                            Showing records for:
                            <span class="font-semibold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-100">
                                @if ($date === 'all')
                                    All time
                                @else
                                    {{ \Carbon\Carbon::parse($date ?? now()->toDateString())->format('F d, Y') }}
                                @endif
                            </span>
                            @if ($patientId && $records->first() && $records->first()->patient)
                                <span class="text-slate-300">•</span>
                                <span class="font-medium text-slate-700 flex items-center gap-1.5 bg-slate-100 px-2.5 py-0.5 rounded-md">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $records->first()->patient->full_name }}
                                </span>
                            @elseif($patientId)
                                <span class="text-slate-300">•</span>
                                <span class="font-medium text-slate-700">Selected patient</span>
                            @endif
                        </div>
                    </div>

                    @if ($patientId)
                        <a href="{{ route('doctor.medical-records', ['date' => $date ?? now()->toDateString(), 'search' => $search ?? request('search')]) }}"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 hover:border-blue-200 hover:bg-blue-50 px-4 py-2 rounded-xl transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Clear Patient Filter
                        </a>
                    @endif
                </div>

                <!-- Dedicated Filter Ribbon -->
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
                    <form method="GET" action="{{ route('doctor.medical-records') }}" id="filterForm"
                        class="flex flex-col md:flex-row md:items-end gap-4 text-sm">
                        
                        <!-- Search Box -->
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search Patient</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <input type="text" name="search" value="{{ $search ?? request('search') }}"
                                    placeholder="Enter patient name..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm placeholder:text-slate-400 text-slate-700 font-medium">
                            </div>
                        </div>

                        <!-- Date Filter -->
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filter Date</label>
                            <div class="flex items-center gap-4">
                                <input type="date" name="date" id="dateInput" value="{{ $date !== 'all' ? ($date ?? now()->toDateString()) : now()->toDateString() }}"
                                    class="px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all shadow-sm text-slate-700 font-medium {{ $date === 'all' ? 'opacity-50 bg-slate-100 cursor-not-allowed text-slate-400' : '' }}" 
                                    {{ $date === 'all' ? 'disabled' : '' }}>
                                
                                <label for="showAll" class="flex items-center gap-2 cursor-pointer select-none group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" id="showAll" {{ $date === 'all' ? 'checked' : '' }}
                                            class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 focus:ring-offset-0 cursor-pointer transition-all shadow-sm">
                                    </div>
                                    <span class="font-medium text-slate-600 group-hover:text-slate-900 transition-colors">All Records</span>
                                </label>
                            </div>
                        </div>

                        <!-- Hidden Inputs -->
                        <input type="hidden" name="date" id="dateHidden" value="{{ $date ?? now()->toDateString() }}" disabled>
                        @if ($patientId)
                            <input type="hidden" name="patient_id" value="{{ $patientId }}">
                        @endif

                        <!-- Submit Button -->
                        <button type="submit"
                            class="bg-slate-900 text-white font-medium px-6 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all focus:ring-2 focus:ring-slate-900/20 outline-none h-[42px] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                            Apply Filters
                        </button>
                    </form>

                    <!-- Javascript for styling the checkbox logic -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const showAll = document.getElementById('showAll');
                            const dateInput = document.getElementById('dateInput');
                            const dateHidden = document.getElementById('dateHidden');

                            function toggleDate() {
                                if (showAll.checked) {
                                    dateInput.disabled = true;
                                    // Add disabled styling
                                    dateInput.classList.add('opacity-50', 'bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
                                    dateInput.classList.remove('bg-white');
                                    dateHidden.disabled = false;
                                    dateHidden.value = 'all';
                                } else {
                                    dateInput.disabled = false;
                                    // Remove disabled styling
                                    dateInput.classList.remove('opacity-50', 'bg-slate-100', 'cursor-not-allowed', 'text-slate-400');
                                    dateInput.classList.add('bg-white');
                                    dateHidden.disabled = true;
                                }
                            }

                            showAll.addEventListener('change', toggleDate);
                        });
                    </script>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-white border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Diagnosis</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Created By</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Timeline</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($records as $record)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('doctor.medical-records', ['patient_id' => $record->patient_id, 'date' => $date ?? now()->toDateString()]) }}"
                                            class="font-bold text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                            {{ $record->patient ? $record->patient->full_name : '—' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                            {{ $record->diagnosis ? $record->diagnosis->name : '—' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 font-medium max-w-xs truncate" title="{{ $record->details }}">
                                        {{ Str::limit($record->details, 50) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">
                                        {{ $record->creator ? $record->creator->name : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <div class="text-sm font-semibold text-slate-700 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $record->created_on ? $record->created_on->format('M d, Y') : '—' }}
                                            </div>
                                            @if ($record->updated_on && $record->updated_on != $record->created_on)
                                                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                                                    Updated: {{ $record->updated_on->format('M d') }} by {{ $record->updater ? $record->updater->name : '—' }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($record->patient)
                                            <a href="{{ route('doctor.patients.add-record', ['patient' => $record->patient, 'record_id' => $record->id]) }}"
                                                class="inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 shadow-sm transition-all focus:ring-2 focus:ring-blue-500/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Update
                                            </a>
                                        @else
                                            <span class="text-slate-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="mx-auto w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-slate-50/50">
                                            <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900">No Records Found</h3>
                                        <p class="text-slate-500 text-sm mt-1">Try adjusting your date or patient filters.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if ($records->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80">
                        {{ $records->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>

</html>