<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RHU Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Sidebar active link */
        .sidebar-link.active {
            background-color: rgba(99, 102, 241, 0.12);
            color: #4f46e5;
            font-weight: 600;
        }

        .sidebar-link.active svg {
            color: #4f46e5;
        }

        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            pointer-events: none;
        }
        .toast {
            pointer-events: auto;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s ease;
        }
        .toast.toast-visible {
            transform: translateX(0);
            opacity: 1;
        }
        .toast.toast-exit {
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.3s ease, opacity 0.25s ease;
        }
        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 0 0.75rem 0.75rem;
            animation: toast-timer 5s linear forwards;
        }
        @keyframes toast-timer {
            from { width: 100%; }
            to   { width: 0%; }
        }

        /* Row highlight animation */
        @keyframes row-highlight {
            0%   { background-color: rgba(74, 222, 128, 0.25); }
            50%  { background-color: rgba(74, 222, 128, 0.10); }
            100% { background-color: transparent; }
        }
        .staff-row-highlight {
            animation: row-highlight 3s ease-out forwards;
        }

        /* Button loading spinner */
        .btn-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="bg-gray-50">

    {{-- TOP HEADER --}}
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="flex justify-between items-center h-16 px-6">
            <div class="flex items-center space-x-3">
                <div class="bg-indigo-600 p-2.5 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-gray-800">RHU Admin Dashboard</h1>
                    <p class="text-xs text-gray-500">Rural Health Unit Management Overview</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                {{-- Filter Buttons --}}
                <div class="hidden sm:flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                    @foreach (['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                        <a href="{{ route('rhu.dashboard', ['filter' => $key]) }}"
                            class="filter-link px-3 py-1.5 rounded-md text-xs font-semibold transition
                                {{ $filter === $key ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-500 hover:text-gray-800' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>

                @auth
                    <div class="hidden md:flex items-center space-x-2 text-sm">
                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span
                                class="text-indigo-700 font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                    </div>
                    <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium transition flex items-center space-x-1">
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
    </header>

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside
            class="w-56 bg-white border-r border-gray-200 sticky top-16 h-[calc(100vh-4rem)] flex flex-col py-6 px-3 shrink-0">
            <nav class="space-y-1">
                {{-- Group: Overview --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-1">Overview</p>
                <a href="#overview" onclick="showSection('overview')" id="link-overview"
                    class="sidebar-link active flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard Overview</span>
                </a>

                {{-- Group: Analytics --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-4 mb-1">Analytics</p>
                <a href="#analytics" onclick="showSection('analytics')" id="link-analytics"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Appointment Analytics</span>
                </a>

                {{-- Group: Disease Statistics --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-4 mb-1">Statistics</p>
                <a href="#diseases" onclick="showSection('diseases')" id="link-diseases"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Disease Statistics</span>
                </a>
                <a href="#departments" onclick="showSection('departments')" id="link-departments"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>By Department</span>
                </a>
                <a href="#dispensing" onclick="showSection('dispensing')" id="link-dispensing"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span>Medicine Dispensing</span>
                </a>

                {{-- Group: Management --}}
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-4 mb-1">Management</p>
                <a href="#staff-approvals" onclick="showSection('staff-approvals')" id="link-staff-approvals"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Staff Management</span>
                    @if($pendingStaffCount > 0)
                        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $pendingStaffCount }}</span>
                    @endif
                </a>
                <a href="#medicine-inventory" onclick="showSection('medicine-inventory')" id="link-medicine-inventory"
                    class="sidebar-link flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition cursor-pointer">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span>Medicine Inventory</span>
                </a>
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 py-8 px-6 overflow-y-auto">

            {{-- Toast Notification Container --}}
            <div id="toast-container" class="toast-container"></div>

            @if (session('success'))
                <template id="flash-success-data" data-message="{{ session('success') }}"></template>
            @endif
            @if ($errors->any())
                <template id="flash-error-data" data-message="{{ $errors->first() }}"></template>
            @endif

            {{-- ══════════════════════════════════════════ --}}
            {{-- SECTION: Dashboard Overview               --}}
            {{-- ══════════════════════════════════════════ --}}
            <section id="section-overview" class="section-content">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                    <p class="text-gray-500 text-sm mt-1">Key metrics at a glance</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                    {{-- Total Patients --}}
                    <div onclick="showSection('departments')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Total Patients</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($totalPatients) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Registered in system</p>
                        </div>
                    </div>

                    {{-- Appointments Today --}}
                    <div onclick="showSection('analytics')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Appointments Today</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($appointmentsToday) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Scheduled for today</p>
                        </div>
                    </div>

                    {{-- Completed Today --}}
                    <div onclick="showSection('analytics')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Completed Today</p>
                            <p class="text-3xl font-bold text-green-700">{{ number_format($completedToday) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Finished appointments</p>
                        </div>
                    </div>

                    {{-- Pending Today --}}
                    <div onclick="showSection('analytics')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Pending Today</p>
                            <p class="text-3xl font-bold text-amber-700">{{ number_format($pendingToday) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Not yet started</p>
                        </div>
                    </div>

                    {{-- Active Departments --}}
                    <div onclick="showSection('departments')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Active Departments</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($activeDepartments) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Health centers</p>
                        </div>
                    </div>

                    {{-- Diagnoses Recorded --}}
                    <div onclick="showSection('diseases')" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4 cursor-pointer hover:border-indigo-200 hover:shadow-md hover:-translate-y-1 transition-all duration-300 transform">
                        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Diagnoses Recorded</p>
                            <p class="text-3xl font-bold text-gray-800">{{ number_format($diagnosesRecorded) }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">In selected period</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ═══ Lazy-loaded sections — content fetched via AJAX ═══ --}}
            @php
                $lazySections = [
                    'analytics' => 'Appointment Analytics',
                    'diseases' => 'Disease Statistics',
                    'departments' => 'By Department',
                    'dispensing' => 'Medicine Dispensing',
                    'staff-approvals' => 'Staff Management',
                    'medicine-inventory' => 'Medicine Inventory',
                ];
            @endphp

            @foreach ($lazySections as $sectionKey => $sectionLabel)
                <section id="section-{{ $sectionKey }}" class="section-content hidden mt-2">
                    <div class="flex flex-col items-center justify-center py-24 text-center" id="loader-{{ $sectionKey }}">
                        <svg class="animate-spin h-8 w-8 text-indigo-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Loading {{ $sectionLabel }}…</p>
                    </div>
                </section>
            @endforeach



            {{-- ═══ Create Staff Modal ═══ --}}
            <div id="create-staff-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('create-staff-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Create Staff Account</h3>
                        <button type="button" onclick="document.getElementById('create-staff-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="create-staff-form" method="POST" action="{{ route('rhu.staff.create') }}" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" name="email" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                                <input type="password" name="password" required minlength="8"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                                <input type="password" name="password_confirmation" required minlength="8"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                                <select name="position" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="Staff">Staff</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Pharmacy">Pharmacy</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <select name="department_id"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="">-- None --</option>
                                    @foreach($departments ?? collect() as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('create-staff-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition" id="create-staff-cancel-btn">
                                Cancel
                            </button>
                            <button type="submit" id="create-staff-submit-btn"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm inline-flex items-center space-x-2">
                                <span id="create-staff-btn-text">Create Account</span>
                                <span id="create-staff-btn-spinner" class="btn-spinner hidden"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ Edit Staff Modal ═══ --}}
            <div id="edit-staff-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('edit-staff-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Edit Staff: <span id="edit-staff-name" class="text-indigo-600"></span></h3>
                        <button type="button" onclick="document.getElementById('edit-staff-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="edit-staff-form" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Position *</label>
                                <select name="position" id="edit-staff-position" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="Staff">Staff</option>
                                    <option value="Doctor">Doctor</option>
                                    <option value="Pharmacy">Pharmacy</option>
                                </select>
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                                <select name="department_id" id="edit-staff-department"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="">-- None --</option>
                                    @foreach($departments ?? collect() as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="edit-staff-phone"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('edit-staff-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ Add Medicine Modal ═══ --}}
            <div id="add-medicine-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('add-medicine-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Add New Medicine</h3>
                        <button type="button" onclick="document.getElementById('add-medicine-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('rhu.medicines.store') }}" class="p-6 space-y-4">
                        @csrf
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medicine Name *</label>
                                <input type="text" name="name" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Generic Name</label>
                                <input type="text" name="generic_name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <input type="text" name="category" placeholder="e.g. Analgesic, Antibiotic"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                <input type="date" name="expiry_date"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                <input type="number" name="quantity" min="0" value="0" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                                <select name="unit" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="tablets">Tablets</option>
                                    <option value="capsules">Capsules</option>
                                    <option value="bottles">Bottles</option>
                                    <option value="vials">Vials</option>
                                    <option value="sachets">Sachets</option>
                                    <option value="tubes">Tubes</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="boxes">Boxes</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="2"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none resize-none"
                                    placeholder="Optional notes about this medicine"></textarea>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('add-medicine-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                Add Medicine
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ Edit Medicine Modal ═══ --}}
            <div id="edit-medicine-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('edit-medicine-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Edit Medicine</h3>
                        <button type="button" onclick="document.getElementById('edit-medicine-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="edit-medicine-form" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Medicine Name *</label>
                                <input type="text" name="name" id="edit-name" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Generic Name</label>
                                <input type="text" name="generic_name" id="edit-generic_name"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <input type="text" name="category" id="edit-category"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit *</label>
                                <select name="unit" id="edit-unit" required
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-white">
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="tablets">Tablets</option>
                                    <option value="capsules">Capsules</option>
                                    <option value="bottles">Bottles</option>
                                    <option value="vials">Vials</option>
                                    <option value="sachets">Sachets</option>
                                    <option value="tubes">Tubes</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="boxes">Boxes</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" id="edit-description" rows="2"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none resize-none"></textarea>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-gray-400 bg-gray-50 rounded-lg p-3">💡 Quantity and expiry dates are managed through stock batches. Use the "Add Stock" button to add new batches.</p>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('edit-medicine-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ Add Stock Modal ═══ --}}
            <div id="add-stock-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('add-stock-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Add Stock</h3>
                        <button type="button" onclick="document.getElementById('add-stock-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="add-stock-form" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div class="bg-indigo-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 font-medium">Medicine</p>
                            <p id="add-stock-medicine-name" class="text-sm font-bold text-gray-800 mt-0.5"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantity and Unit to Add *</label>
                            <div class="flex items-center space-x-2">
                                <input type="number" name="add_quantity" id="add-stock-quantity" min="1" value="1" required
                                    class="w-2/3 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                                <select name="unit" id="add-stock-unit" required
                                    class="w-1/3 border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none bg-white">
                                    <option value="pcs">Pieces (pcs)</option>
                                    <option value="tablets">Tablets</option>
                                    <option value="capsules">Capsules</option>
                                    <option value="bottles">Bottles</option>
                                    <option value="vials">Vials</option>
                                    <option value="sachets">Sachets</option>
                                    <option value="tubes">Tubes</option>
                                    <option value="ml">Milliliters (ml)</option>
                                    <option value="boxes">Boxes</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Batch Expiry Date</label>
                            <input type="date" name="expiry_date" id="add-stock-expiry"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-400 focus:outline-none">
                            <p class="text-xs text-gray-400 mt-1">Set the expiry date for this new batch</p>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('add-stock-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-green-600 hover:bg-green-700 transition shadow-sm">
                                Add Stock
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ═══ Edit Batch Expiry Modal ═══ --}}
            <div id="edit-batch-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('edit-batch-modal').classList.add('hidden')"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Edit Batch Expiry</h3>
                        <button type="button" onclick="document.getElementById('edit-batch-modal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <form id="edit-batch-form" method="POST" class="p-6 space-y-4">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                            <input type="date" name="expiry_date" id="edit-batch-expiry"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <p class="text-xs text-gray-400 mt-1">Leave blank for no expiry</p>
                        </div>
                        <div class="flex justify-end space-x-3 pt-2">
                            <button type="button" onclick="document.getElementById('edit-batch-modal').classList.add('hidden')"
                                class="px-4 py-2 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 transition shadow-sm">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    {{-- ── Chart.js ─────────────────────────────────────────────── --}}
    <script>
        // ── Configuration ────────────────────────────────────
        const sections = ['overview', 'analytics', 'diseases', 'departments', 'dispensing', 'staff-approvals', 'medicine-inventory'];
        const currentFilter = @json($filter);
        const sectionBaseUrl = @json(route('rhu.dashboard.section', ['section' => '__SECTION__']));
        const loadedSections = new Set(['overview']); // overview is already rendered server-side
        const staffCreated = {{ session('staff_created') ? 'true' : 'false' }};

        // ── Sidebar navigation with lazy-loading ─────────────
        function showSection(id) {
            sections.forEach(s => {
                document.getElementById('section-' + s).classList.toggle('hidden', s !== id);
                const link = document.getElementById('link-' + s);
                link.classList.toggle('active', s === id);
            });
            history.replaceState(null, '', '#' + id);
            updateFilterLinks(id);

            // Lazy-load section if not already loaded
            if (!loadedSections.has(id)) {
                loadSection(id);
            }
        }

        function loadSection(id) {
            const url = sectionBaseUrl.replace('__SECTION__', id)
                + '?filter=' + encodeURIComponent(currentFilter)
                + (id === 'staff-approvals' && staffCreated ? '&highlight=1' : '');

            fetch(url)
                .then(res => {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.text();
                })
                .then(html => {
                    const section = document.getElementById('section-' + id);
                    section.innerHTML = html;
                    loadedSections.add(id);

                    // Post-load hooks
                    if (id === 'analytics') initAnalyticsCharts();
                    if (id === 'staff-approvals' && staffCreated) {
                        setTimeout(() => {
                            const row = section.querySelector('.staff-row-highlight');
                            if (row) row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300);
                    }
                })
                .catch(err => {
                    const section = document.getElementById('section-' + id);
                    section.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-24 text-center">
                            <svg class="w-10 h-10 text-red-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <p class="text-sm text-gray-500 font-medium">Failed to load section</p>
                            <button onclick="loadSection('${id}')" class="mt-3 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-lg hover:bg-indigo-700 transition">
                                Retry
                            </button>
                        </div>`;
                });
        }

        // Keep filter-button hrefs in sync with the active section
        function updateFilterLinks(sectionId) {
            document.querySelectorAll('.filter-link').forEach(link => {
                const url = new URL(link.href);
                url.hash = sectionId;
                link.href = url.toString();
            });
        }

        // Handle hash on load
        const hash = location.hash.replace('#', '') || 'overview';
        if (sections.includes(hash)) showSection(hash);

        // ── Chart helpers (used by initAnalyticsCharts) ──────
        const bgPalette = [
            'rgba(79,70,229,0.8)', 'rgba(59,130,246,0.8)', 'rgba(20,184,166,0.8)',
            'rgba(245,158,11,0.8)', 'rgba(244,63,94,0.8)', 'rgba(139,92,246,0.8)',
            'rgba(16,185,129,0.8)', 'rgba(236,72,153,0.8)', 'rgba(99,102,241,0.8)', 'rgba(234,179,8,0.8)',
        ];
        const borderPalette = bgPalette.map(c => c.replace('0.8)', '1)'));

        const baseOpts = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 11 } }, grid: { display: false } },
            },
        };

        const charts = {};

        function initAnalyticsCharts() {
            const dataEl = document.getElementById('analytics-chart-data');
            if (!dataEl) return;
            const data = JSON.parse(dataEl.textContent);

            // 1) Appointments per month
            charts.apm = new Chart(document.getElementById('appointmentsPerMonthChart'), {
                type: 'bar',
                data: {
                    labels: data.appointmentsPerMonth.map(r => {
                        const [y, m] = r.month.split('-');
                        return new Date(y, m - 1).toLocaleString('default', { month: 'short', year: '2-digit' });
                    }),
                    datasets: [{
                        label: 'Appointments',
                        data: data.appointmentsPerMonth.map(r => r.total),
                        backgroundColor: 'rgba(79,70,229,0.8)',
                        borderColor: 'rgba(79,70,229,1)',
                        borderWidth: 1, borderRadius: 5
                    }],
                },
                options: baseOpts,
            });

            // 2) Patients per department
            const ppdData = data.patientsPerDepartment;
            charts.ppd = new Chart(document.getElementById('patientsPerDepartmentChart'), {
                type: 'bar',
                data: {
                    labels: ppdData.map(r => r.department_name),
                    datasets: [{
                        label: 'Patients',
                        data: ppdData.map(r => r.patient_count),
                        backgroundColor: ppdData.map((_, i) => bgPalette[i % bgPalette.length]),
                        borderColor: ppdData.map((_, i) => borderPalette[i % borderPalette.length]),
                        borderWidth: 1, borderRadius: 5
                    }],
                },
                options: baseOpts,
            });

            // 3) Top diagnoses this month (horizontal)
            const tdData = data.topDiagnosesThisMonth;
            charts.td = new Chart(document.getElementById('topDiagnosesChart'), {
                type: 'bar',
                data: {
                    labels: tdData.map(r => r.diagnosis_name),
                    datasets: [{
                        label: 'Cases',
                        data: tdData.map(r => r.total_count),
                        backgroundColor: tdData.map((_, i) => bgPalette[i % bgPalette.length]),
                        borderColor: tdData.map((_, i) => borderPalette[i % borderPalette.length]),
                        borderWidth: 1, borderRadius: 5
                    }],
                },
                options: {
                    ...baseOpts,
                    indexAxis: 'y',
                    scales: {
                        x: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                        y: { ticks: { font: { size: 11 } }, grid: { display: false } }
                    }
                },
            });
        }

        // ── Diagnosis → Patient drill-down ────────────────────
        const patientCache = {};

        function togglePatients(uid, diagnosisName, departmentName) {
            const panel = document.getElementById('panel-' + uid);
            const btnText = document.getElementById('btn-text-' + uid);
            const loader = document.getElementById('loader-' + uid);
            const content = document.getElementById('content-' + uid);

            if (!panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                btnText.textContent = 'View';
                return;
            }

            panel.classList.remove('hidden');
            btnText.textContent = 'Hide';

            if (patientCache[uid]) return;

            loader.classList.remove('hidden');
            content.innerHTML = '';

            const params = new URLSearchParams({
                diagnosis_name: diagnosisName,
                department_name: departmentName,
                filter: currentFilter
            });

            fetch(`{{ route('rhu.diagnosisPatients') }}?${params}`)
                .then(res => res.json())
                .then(patients => {
                    loader.classList.add('hidden');
                    patientCache[uid] = true;

                    if (patients.length === 0) {
                        content.innerHTML = '<p class="text-xs text-gray-400 py-2">No patients found.</p>';
                        return;
                    }

                    let html = `
                        <table class="w-full text-xs text-left mt-1">
                            <thead class="text-gray-500 uppercase bg-white/60">
                                <tr>
                                    <th class="px-3 py-2">#</th>
                                    <th class="px-3 py-2">Patient Name</th>
                                    <th class="px-3 py-2">Gender</th>
                                    <th class="px-3 py-2">Date of Birth</th>
                                    <th class="px-3 py-2">Record Date</th>
                                </tr>
                            </thead>
                            <tbody>`;

                    patients.forEach((p, i) => {
                        const dob = p.date_of_birth ? new Date(p.date_of_birth).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
                        const recDate = p.created_on ? new Date(p.created_on).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
                        html += `
                            <tr class="border-t border-indigo-100/60 hover:bg-white/50">
                                <td class="px-3 py-2 text-gray-400">${i + 1}</td>
                                <td class="px-3 py-2 font-medium text-gray-800">${p.last_name}, ${p.first_name}</td>
                                <td class="px-3 py-2 capitalize text-gray-600">${p.gender || '—'}</td>
                                <td class="px-3 py-2 text-gray-600">${dob}</td>
                                <td class="px-3 py-2 text-gray-600">${recDate}</td>
                            </tr>`;
                    });

                    html += '</tbody></table>';
                    content.innerHTML = html;
                })
                .catch(() => {
                    loader.classList.add('hidden');
                    content.innerHTML = '<p class="text-xs text-red-500 py-2">Failed to load patients.</p>';
                });
        }

        // ── Medicine Inventory modal helpers ──────────────────
        function openEditModal(id, medicine) {
            const form = document.getElementById('edit-medicine-form');
            form.action = `/rhu/medicines/${id}`;
            document.getElementById('edit-name').value = medicine.name || '';
            document.getElementById('edit-generic_name').value = medicine.generic_name || '';
            document.getElementById('edit-category').value = medicine.category || '';
            document.getElementById('edit-unit').value = medicine.unit || 'pcs';
            document.getElementById('edit-description').value = medicine.description || '';
            document.getElementById('edit-medicine-modal').classList.remove('hidden');
        }

        function openAddStockModal(id, name, unit) {
            const form = document.getElementById('add-stock-form');
            form.action = `/rhu/medicines/${id}/add-stock`;
            document.getElementById('add-stock-medicine-name').textContent = name;
            const unitSelect = document.getElementById('add-stock-unit');
            const unitOption = Array.from(unitSelect.options).find(opt => opt.value === unit);
            unitSelect.value = unitOption ? unit : 'pcs';
            document.getElementById('add-stock-quantity').value = 1;
            document.getElementById('add-stock-expiry').value = '';
            document.getElementById('add-stock-modal').classList.remove('hidden');
        }

        function toggleBatches(rowId) {
            const row = document.getElementById(rowId);
            if (row) row.classList.toggle('hidden');
        }

        function openEditBatchModal(batchId, currentExpiry) {
            const form = document.getElementById('edit-batch-form');
            form.action = `/rhu/medicine-batches/${batchId}/expiry`;
            document.getElementById('edit-batch-expiry').value = currentExpiry;
            document.getElementById('edit-batch-modal').classList.remove('hidden');
        }

        // ── Staff Management JS ──────────────────────────────
        function openEditStaffModal(staffId, position, departmentId, phone, name) {
            const form = document.getElementById('edit-staff-form');
            form.action = `/rhu/staff/${staffId}`;
            document.getElementById('edit-staff-name').textContent = name;
            document.getElementById('edit-staff-position').value = position;
            document.getElementById('edit-staff-department').value = departmentId || '';
            document.getElementById('edit-staff-phone').value = phone || '';
            document.getElementById('edit-staff-modal').classList.remove('hidden');
        }

        // ── Toast Notification System ────────────────────────
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            const isSuccess = type === 'success';

            toast.className = 'toast relative flex items-start space-x-3 px-5 py-4 rounded-xl shadow-lg border min-w-[340px] max-w-md '
                + (isSuccess ? 'bg-white border-green-200' : 'bg-white border-red-200');

            toast.innerHTML = `
                <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${ isSuccess ? 'bg-green-100' : 'bg-red-100' }">
                    ${ isSuccess
                        ? '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                        : '<svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    }
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold ${ isSuccess ? 'text-green-800' : 'text-red-800' }">${ isSuccess ? 'Success' : 'Error' }</p>
                    <p class="text-sm ${ isSuccess ? 'text-green-600' : 'text-red-600' } mt-0.5 leading-snug">${message}</p>
                </div>
                <button onclick="dismissToast(this.parentElement)" class="shrink-0 text-gray-400 hover:text-gray-600 transition p-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="toast-progress ${ isSuccess ? 'bg-green-400' : 'bg-red-400' }"></div>
            `;

            container.appendChild(toast);
            requestAnimationFrame(() => {
                requestAnimationFrame(() => toast.classList.add('toast-visible'));
            });
            const timer = setTimeout(() => dismissToast(toast), 5000);
            toast._timer = timer;
        }

        function dismissToast(toast) {
            if (!toast || toast._dismissed) return;
            toast._dismissed = true;
            clearTimeout(toast._timer);
            toast.classList.remove('toast-visible');
            toast.classList.add('toast-exit');
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
            setTimeout(() => toast.remove(), 400);
        }

        // ── Fire toasts from server flash data ───────────────
        (function() {
            const successEl = document.getElementById('flash-success-data');
            const errorEl   = document.getElementById('flash-error-data');
            if (successEl) showToast(successEl.dataset.message, 'success');
            if (errorEl)   showToast(errorEl.dataset.message, 'error');
        })();

        // ── Create Staff Form: Loading State ─────────────────
        (function() {
            const form = document.getElementById('create-staff-form');
            if (!form) return;
            form.addEventListener('submit', function() {
                const btn       = document.getElementById('create-staff-submit-btn');
                const btnText   = document.getElementById('create-staff-btn-text');
                const spinner   = document.getElementById('create-staff-btn-spinner');
                const cancelBtn = document.getElementById('create-staff-cancel-btn');
                btn.disabled = true;
                btn.classList.add('opacity-75', 'cursor-not-allowed');
                btnText.textContent = 'Creating…';
                spinner.classList.remove('hidden');
                cancelBtn.disabled = true;
                cancelBtn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        })();
    </script>
</body>

</html>
