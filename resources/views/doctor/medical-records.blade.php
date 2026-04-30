<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medical Records</title>
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Montserrat', 'Inter', sans-serif; }
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
                                            <div class="flex items-center gap-2">
                                                <button onclick="viewRecord(this)" 
                                                    data-patient="{{ $record->patient->full_name }}"
                                                    data-diagnosis="{{ $record->diagnosis ? $record->diagnosis->name : 'None' }}"
                                                    data-details="{{ $record->details }}"
                                                    data-date="{{ $record->created_on ? $record->created_on->format('M d, Y') : '—' }}"
                                                    data-doctor="{{ $record->creator ? $record->creator->name : '—' }}"
                                                    data-prescriptions="{{ json_encode($record->prescriptions->map(function($p) {
                                                        return [
                                                            'name' => $p->medication_name,
                                                            'generic_name' => $p->generic_name,
                                                            'type' => $p->type,
                                                            'dosage' => $p->dosage,
                                                            'frequency' => $p->frequency,
                                                            'duration' => $p->duration,
                                                            'quantity' => $p->quantity,
                                                            'instructions' => $p->instructions
                                                        ];
                                                    })) }}"
                                                    class="inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 shadow-sm transition-all focus:ring-2 focus:ring-emerald-500/20">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                    View
                                                </button>
                                                <a href="{{ route('doctor.patients.add-record', ['patient' => $record->patient, 'new' => 1]) }}"
                                                    class="inline-flex items-center gap-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 shadow-sm transition-all focus:ring-2 focus:ring-blue-500/20">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                                    Add Follow-up
                                                </a>
                                            </div>
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

    <!-- View Record Modal -->
    <dialog id="viewRecordModal" class="p-0 bg-transparent backdrop:bg-slate-900/50 open:animate-in open:fade-in open:zoom-in-95 rounded-2xl shadow-xl w-full max-w-2xl m-auto">
        <div class="bg-white rounded-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white/80 backdrop-blur-sm z-10">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Medical Record Details</h3>
                    <p class="text-xs font-medium text-slate-500 mt-0.5" id="modalPatientName"></p>
                </div>
                <button type="button" onclick="document.getElementById('viewRecordModal').close()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 p-2 rounded-xl transition-all focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6 overflow-y-auto w-full flex-1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Date</span>
                        <div class="font-medium text-slate-800" id="modalDate"></div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Diagnosis</span>
                        <div class="font-medium text-slate-800" id="modalDiagnosis"></div>
                    </div>
                </div>

                <div class="mb-6">
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Clinical Details</span>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-sm text-slate-700 whitespace-pre-wrap" id="modalDetails"></div>
                </div>

                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Prescriptions</span>
                    <div id="modalPrescriptions" class="space-y-3">
                        <!-- Prescriptions injected via JS -->
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('viewRecordModal').close()" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:text-slate-900 px-5 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm focus:ring-2 focus:ring-slate-200 outline-none">
                    Close
                </button>
                <button type="button" onclick="printPrescription()" class="inline-flex items-center gap-2 bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm focus:ring-2 focus:ring-blue-500/20 outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Prescription
                </button>
            </div>
        </div>
    </dialog>

    <script>
        // Store current modal data for printing
        let currentRecord = {};

        function viewRecord(btn) {
            const patient = btn.getAttribute('data-patient');
            const diagnosis = btn.getAttribute('data-diagnosis');
            const details = btn.getAttribute('data-details');
            const date = btn.getAttribute('data-date');
            const doctor = btn.getAttribute('data-doctor');
            
            let prescriptions = [];
            try {
                prescriptions = JSON.parse(btn.getAttribute('data-prescriptions') || '[]');
            } catch(e) {}

            // Store for print
            currentRecord = { patient, diagnosis, details, date, doctor, prescriptions };

            document.getElementById('modalPatientName').textContent = patient;
            document.getElementById('modalDiagnosis').textContent = diagnosis;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalDetails').textContent = details || 'No details provided.';
            
            const rxContainer = document.getElementById('modalPrescriptions');
            rxContainer.innerHTML = '';
            
            if (prescriptions.length === 0) {
                rxContainer.innerHTML = '<div class="text-sm italic text-slate-500 p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">No prescriptions recorded.</div>';
            } else {
                prescriptions.forEach(rx => {
                    const row = document.createElement('div');
                    row.className = 'bg-white border border-slate-200 rounded-xl p-4 shadow-sm';
                    row.innerHTML = `
                        <div class="font-bold text-blue-700 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            ${rx.name}
                        </div>
                        ${rx.generic_name ? `<div class="text-xs text-slate-500 mb-2 italic">Generic: ${rx.generic_name}</div>` : ''}
                        <div class="grid grid-cols-2 gap-2 text-sm text-slate-600">
                            ${rx.type ? `<div><span class="font-medium text-slate-500">Type:</span> ${rx.type}</div>` : ''}
                            ${rx.dosage ? `<div><span class="font-medium text-slate-500">Dosage:</span> ${rx.dosage}</div>` : ''}
                            ${rx.frequency ? `<div><span class="font-medium text-slate-500">Frequency:</span> ${rx.frequency}</div>` : ''}
                            ${rx.duration ? `<div><span class="font-medium text-slate-500">Duration:</span> ${rx.duration}</div>` : ''}
                            ${rx.quantity ? `<div><span class="font-medium text-slate-500">Quantity:</span> ${rx.quantity}</div>` : ''}
                        </div>
                        ${rx.instructions ? `<div class="mt-2 text-sm text-slate-600"><span class="font-medium text-slate-500">Instructions:</span> ${rx.instructions}</div>` : ''}
                    `;
                    rxContainer.appendChild(row);
                });
            }

            document.getElementById('viewRecordModal').showModal();
        }

        function printPrescription() {
            const { patient, diagnosis, details, date, doctor, prescriptions } = currentRecord;

            let rxRows = '';
            if (prescriptions && prescriptions.length > 0) {
                prescriptions.forEach((rx, i) => {
                    const medNameDisplay = rx.name + (rx.generic_name ? `<br><span style="font-size:10px; color:#64748b; font-weight:normal;">(${rx.generic_name})</span>` : '');
                    rxRows += `
                        <tr>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${i + 1}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0; font-weight: 600;">${medNameDisplay}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${rx.type || '—'}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${rx.dosage || '—'}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${rx.frequency || '—'}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${rx.duration || '—'}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0; font-weight: bold;">${rx.quantity || '—'}</td>
                            <td style="padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">${rx.instructions || '—'}</td>
                        </tr>`;
                });
            } else {
                rxRows = '<tr><td colspan="8" style="padding: 16px; text-align: center; color: #94a3b8; font-style: italic;">No prescriptions recorded.</td></tr>';
            }

            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Prescription - ${patient}</title>
                    <style>
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        body {
                            font-family: 'Montserrat', 'Inter', Arial, sans-serif;
                            color: #1e293b;
                            padding: 40px;
                            max-width: 800px;
                            margin: 0 auto;
                        }
                        .header {
                            text-align: center;
                            border-bottom: 2px solid #1e293b;
                            padding-bottom: 16px;
                            margin-bottom: 24px;
                        }
                        .header h1 {
                            font-size: 22px;
                            font-weight: 700;
                            letter-spacing: -0.5px;
                            margin-bottom: 4px;
                        }
                        .header p {
                            font-size: 12px;
                            color: #64748b;
                        }
                        .rx-symbol {
                            font-size: 28px;
                            font-weight: 700;
                            font-style: italic;
                            color: #1e40af;
                            margin-bottom: 16px;
                        }
                        .info-grid {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 12px;
                            margin-bottom: 24px;
                        }
                        .info-item label {
                            display: block;
                            font-size: 10px;
                            font-weight: 700;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            color: #64748b;
                            margin-bottom: 2px;
                        }
                        .info-item span {
                            font-size: 14px;
                            font-weight: 500;
                        }
                        .section-title {
                            font-size: 11px;
                            font-weight: 700;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            color: #64748b;
                            margin-bottom: 8px;
                        }
                        .details-box {
                            background: #f8fafc;
                            border: 1px solid #e2e8f0;
                            border-radius: 8px;
                            padding: 12px 16px;
                            font-size: 13px;
                            margin-bottom: 24px;
                            white-space: pre-wrap;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 13px;
                            margin-bottom: 40px;
                        }
                        thead th {
                            background: #f1f5f9;
                            padding: 10px 12px;
                            text-align: left;
                            font-size: 11px;
                            font-weight: 700;
                            text-transform: uppercase;
                            letter-spacing: 0.5px;
                            color: #475569;
                            border-bottom: 2px solid #cbd5e1;
                        }
                        .signature-area {
                            margin-top: 60px;
                            display: flex;
                            justify-content: flex-end;
                        }
                        .signature-block {
                            text-align: center;
                            width: 250px;
                        }
                        .signature-line {
                            border-top: 1px solid #1e293b;
                            margin-top: 60px;
                            padding-top: 8px;
                        }
                        .signature-block .doctor-name {
                            font-size: 14px;
                            font-weight: 600;
                        }
                        .signature-block .doctor-label {
                            font-size: 11px;
                            color: #64748b;
                        }
                        @media print {
                            body { padding: 20px; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h1>Medical Prescription</h1>
                        <p>Rural Health Unit</p>
                    </div>

                    <div class="rx-symbol">℞</div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Patient Name</label>
                            <span>${patient}</span>
                        </div>
                        <div class="info-item">
                            <label>Date</label>
                            <span>${date}</span>
                        </div>
                        <div class="info-item">
                            <label>Diagnosis</label>
                            <span>${diagnosis}</span>
                        </div>
                        <div class="info-item">
                            <label>Attending Physician</label>
                            <span>${doctor}</span>
                        </div>
                    </div>

                    ${details ? `
                        <div class="section-title">Clinical Details</div>
                        <div class="details-box">${details}</div>
                    ` : ''}

                    <div class="section-title">Prescribed Medications</div>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Medication</th>
                                <th>Type</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Qty</th>
                                <th>Instructions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${rxRows}
                        </tbody>
                    </table>

                    <div class="signature-area">
                        <div class="signature-block">
                            <div class="signature-line">
                                <div class="doctor-name">${doctor}</div>
                                <div class="doctor-label">Attending Physician</div>
                            </div>
                        </div>
                    </div>
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank', 'width=800,height=900');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</body>

</html>