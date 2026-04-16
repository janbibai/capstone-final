<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pharmacy Dashboard</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-btn.active {
            color: #0f766e;
            border-color: #0f766e;
            background-color: #f0fdfa;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 selection:bg-teal-100 selection:text-teal-900">

    {{-- ─── HEADER ─────────────────────────────────────────────── --}}
    <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 sticky top-0 z-40 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3 group">
                    <div class="bg-gradient-to-br from-teal-500 to-teal-600 p-2 rounded-xl shadow-sm shadow-teal-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900 tracking-tight">Pharmacy Dashboard</h1>
                        <p class="text-[11px] font-medium text-slate-500 uppercase tracking-wider">Medicine Dispensary &amp; Inventory</p>
                    </div>
                </div>

                <div class="flex items-center space-x-5">
                    @auth
                        <div class="hidden sm:flex items-center gap-2 text-sm">
                            <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <span class="text-teal-700 font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <span class="font-medium text-slate-700">{{ auth()->user()->name }}</span>
                        </div>
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

            {{-- ALERTS --}}
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
                <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm">
                    <ul class="list-disc pl-5 space-y-1 text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ─── KPI SUMMARY CARDS ──────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                {{-- Total Medicines --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-teal-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Total Medicines</p>
                        <p class="text-3xl font-bold text-slate-800">{{ number_format($totalMedicines) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">In inventory</p>
                    </div>
                </div>

                {{-- Low Stock --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Low Stock</p>
                        <p class="text-3xl font-bold text-amber-700">{{ number_format($lowStock) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">≤ 10 units remaining</p>
                    </div>
                </div>

                {{-- Out of Stock --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Out of Stock</p>
                        <p class="text-3xl font-bold text-rose-700">{{ number_format($outOfStock) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">0 units</p>
                    </div>
                </div>

                {{-- Today's Prescriptions --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-medium">Today's Prescriptions</p>
                        <p class="text-3xl font-bold text-blue-700">{{ number_format($todayPrescriptionCount) }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Medications prescribed</p>
                    </div>
                </div>
            </div>

            {{-- ─── TAB NAVIGATION ─────────────────────────────── --}}
            <div class="flex items-center gap-2 border-b border-slate-200">
                <button type="button" onclick="switchTab('inventory')" id="tab-inventory"
                    class="tab-btn active px-5 py-3 text-sm font-semibold border-b-2 border-transparent transition-all rounded-t-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Medicine Inventory
                </button>
                <button type="button" onclick="switchTab('prescriptions')" id="tab-prescriptions"
                    class="tab-btn px-5 py-3 text-sm font-semibold text-slate-500 border-b-2 border-transparent transition-all rounded-t-lg flex items-center gap-2 hover:text-slate-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Prescriptions
                </button>
            </div>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB: MEDICINE INVENTORY                            --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section id="panel-inventory">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    {{-- Search --}}
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                        <div class="relative max-w-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" id="medicineSearch" placeholder="Search medicines..."
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all shadow-sm placeholder:text-slate-400 text-slate-700 font-medium text-sm">
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm" id="medicineTable">
                            <thead class="bg-white border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">#</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Medicine Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Generic Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Unit</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Expiry Date</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($medicines as $index => $medicine)
                                    @php
                                        $isExpired = $medicine->expiry_date && $medicine->expiry_date->isPast();
                                        $isExpiringSoon = $medicine->expiry_date && !$isExpired && $medicine->expiry_date->diffInDays(now()) <= 30;
                                        $isOutOfStock = $medicine->quantity === 0;
                                        $isLowStock = $medicine->quantity > 0 && $medicine->quantity <= 10;
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors medicine-row">
                                        <td class="px-6 py-4 text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-semibold text-slate-900">{{ $medicine->name }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{ $medicine->generic_name ?? '—' }}</td>
                                        <td class="px-6 py-4">
                                            @if($medicine->category)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200">{{ $medicine->category }}</span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold {{ $isOutOfStock ? 'text-rose-600' : ($isLowStock ? 'text-amber-600' : 'text-slate-800') }}">
                                            {{ number_format($medicine->quantity) }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $medicine->unit }}</td>
                                        <td class="px-6 py-4 text-slate-600 text-xs">
                                            @if($medicine->expiry_date)
                                                <span class="{{ $isExpired ? 'text-rose-600 font-semibold' : ($isExpiringSoon ? 'text-amber-600 font-semibold' : '') }}">
                                                    {{ $medicine->expiry_date->format('M d, Y') }}
                                                </span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($isExpired)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Expired</span>
                                            @elseif($isOutOfStock)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-700">Out of Stock</span>
                                            @elseif($isLowStock)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Low Stock</span>
                                            @elseif($isExpiringSoon)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">Expiring Soon</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">In Stock</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if(!$isOutOfStock && !$isExpired)
                                                <button type="button"
                                                    onclick="openDispenseModal({{ $medicine->id }}, '{{ addslashes($medicine->name) }}', {{ $medicine->quantity }}, '{{ $medicine->unit }}')"
                                                    class="inline-flex items-center gap-1.5 bg-teal-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-700 shadow-sm transition-all focus:ring-2 focus:ring-teal-500/20">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                    Dispense
                                                </button>
                                            @else
                                                <span class="text-slate-300 text-xs">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-16 text-center">
                                            <div class="mx-auto w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-slate-50/50">
                                                <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-900">No Medicines Found</h3>
                                            <p class="text-slate-500 text-sm mt-1">The medicine inventory is empty.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- ═══════════════════════════════════════════════════ --}}
            {{-- TAB: PRESCRIPTIONS                                 --}}
            {{-- ═══════════════════════════════════════════════════ --}}
            <section id="panel-prescriptions" class="hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    {{-- Filter Bar --}}
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                        <form method="GET" action="{{ route('pharmacy.dashboard') }}" class="flex flex-col md:flex-row md:items-end gap-4 text-sm">
                            <div class="flex-1">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search Patient</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Enter patient name..."
                                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all shadow-sm placeholder:text-slate-400 text-slate-700 font-medium">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date</label>
                                <input type="date" name="date" value="{{ $date }}"
                                    class="px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all shadow-sm text-slate-700 font-medium">
                            </div>
                            <input type="hidden" name="tab" value="prescriptions">
                            <button type="submit"
                                class="bg-slate-900 text-white font-medium px-6 py-2.5 rounded-xl hover:bg-slate-800 shadow-sm transition-all focus:ring-2 focus:ring-slate-900/20 outline-none h-[42px] flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Apply Filters
                            </button>
                        </form>
                    </div>

                    {{-- Info banner --}}
                    <div class="px-6 py-3 bg-teal-50/50 border-b border-teal-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-medium text-teal-800">
                            Showing prescriptions for:
                            <span class="font-bold">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                            @if($search)
                                <span class="text-teal-600"> · Patient: "{{ $search }}"</span>
                            @endif
                        </span>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-white border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Patient</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Diagnosis</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Medication</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Dosage</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Frequency</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Instructions</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prescribing Doctor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse($prescriptions as $rx)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-slate-900">
                                                {{ $rx->medicalRecord && $rx->medicalRecord->patient ? $rx->medicalRecord->patient->full_name : '—' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $rx->medicalRecord && $rx->medicalRecord->diagnosis ? $rx->medicalRecord->diagnosis->name : '—' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-teal-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                                <span class="font-semibold text-teal-700">{{ $rx->medication_name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $rx->dosage ?? '—' }}</td>
                                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $rx->frequency ?? '—' }}</td>
                                        <td class="px-6 py-4 text-slate-600 font-medium">{{ $rx->duration ?? '—' }}</td>
                                        <td class="px-6 py-4 text-slate-600 max-w-xs truncate" title="{{ $rx->instructions }}">{{ $rx->instructions ?? '—' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium">
                                            {{ $rx->medicalRecord && $rx->medicalRecord->creator ? $rx->medicalRecord->creator->name : '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-16 text-center">
                                            <div class="mx-auto w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 ring-8 ring-slate-50/50">
                                                <svg class="h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-sm font-bold text-slate-900">No Prescriptions Found</h3>
                                            <p class="text-slate-500 text-sm mt-1">No prescriptions were recorded for this date.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($prescriptions->hasPages())
                        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/80">
                            {{ $prescriptions->links() }}
                        </div>
                    @endif
                </div>
            </section>

        </div>
    </main>

    {{-- ═══ Dispense Medicine Modal ═══ --}}
    <dialog id="dispenseModal" class="p-0 bg-transparent backdrop:bg-slate-900/50 open:animate-in open:fade-in open:zoom-in-95 rounded-2xl shadow-xl w-full max-w-md m-auto">
        <div class="bg-white rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Dispense Medicine</h3>
                    <p class="text-xs font-medium text-slate-500 mt-0.5" id="dispenseModalSubtitle"></p>
                </div>
                <button type="button" onclick="document.getElementById('dispenseModal').close()" class="text-slate-400 hover:text-slate-600 bg-slate-50 hover:bg-slate-100 p-2 rounded-xl transition-all focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="dispenseForm" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <div>
                            <p class="font-bold text-teal-900" id="dispenseModalName"></p>
                            <p class="text-sm text-teal-700">Available: <span class="font-bold" id="dispenseModalStock"></span></p>
                        </div>
                    </div>

                    <label class="block text-sm font-semibold text-slate-700 mb-2">Quantity to Dispense</label>
                    <input type="number" name="quantity" id="dispenseQtyInput" min="1" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 outline-none transition-all shadow-sm"
                        placeholder="Enter quantity...">
                    <p class="text-xs text-slate-400 mt-2">This will deduct the entered amount from the medicine inventory.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('dispenseModal').close()"
                        class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-teal-600 text-white hover:bg-teal-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm focus:ring-2 focus:ring-teal-500/20 inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Confirm Dispense
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        // ─── Dispense Modal ───────────────────────────────────────
        function openDispenseModal(id, name, stock, unit) {
            document.getElementById('dispenseForm').action = '/pharmacy/medicines/' + id + '/dispense';
            document.getElementById('dispenseModalName').textContent = name;
            document.getElementById('dispenseModalSubtitle').textContent = name;
            document.getElementById('dispenseModalStock').textContent = stock + ' ' + unit;
            document.getElementById('dispenseQtyInput').max = stock;
            document.getElementById('dispenseQtyInput').value = '';
            document.getElementById('dispenseQtyInput').placeholder = 'Max: ' + stock;
            document.getElementById('dispenseModal').showModal();
        }

        // ─── Tab Switching ───────────────────────────────────────
        function switchTab(tab) {
            // Hide all panels
            document.getElementById('panel-inventory').classList.add('hidden');
            document.getElementById('panel-prescriptions').classList.add('hidden');

            // Deactivate all tabs
            document.getElementById('tab-inventory').classList.remove('active');
            document.getElementById('tab-prescriptions').classList.remove('active');
            document.getElementById('tab-inventory').classList.add('text-slate-500');
            document.getElementById('tab-prescriptions').classList.add('text-slate-500');

            // Activate selected
            document.getElementById('panel-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('tab-' + tab).classList.remove('text-slate-500');
        }

        // ─── Client-side medicine search ─────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('medicineSearch');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.medicine-row').forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(query) ? '' : 'none';
                    });
                });
            }

            // Auto-switch to prescriptions tab if ?tab=prescriptions in URL
            const params = new URLSearchParams(window.location.search);
            if (params.get('tab') === 'prescriptions') {
                switchTab('prescriptions');
            }
        });
    </script>

</body>
</html>
