<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RHU Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
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
                    <p class="text-xs text-gray-500">Rural Health Unit — Management Overview</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                {{-- Filter Buttons --}}
                <div class="hidden sm:flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                    @foreach (['today' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'year' => 'This Year'] as $key => $label)
                        <a href="{{ route('rhu.dashboard', ['filter' => $key]) }}"
                            class="px-3 py-1.5 rounded-md text-xs font-semibold transition
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
            </nav>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 py-8 px-6 overflow-y-auto">

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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center space-x-4">
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

            {{-- ══════════════════════════════════════════ --}}
            {{-- SECTION: Appointment Analytics (Charts)   --}}
            {{-- ══════════════════════════════════════════ --}}
            <section id="section-analytics" class="section-content hidden mt-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Appointment Analytics</h2>
                    <p class="text-gray-500 text-sm mt-1">Visual breakdown of appointments, patients, and diagnoses</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Appointments Per Month</h4>
                        <div style="position:relative;height:280px;">
                            <canvas id="appointmentsPerMonthChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Patients Per Department</h4>
                        <div style="position:relative;height:280px;">
                            <canvas id="patientsPerDepartmentChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Top Diagnoses This Month</h4>
                        <div style="position:relative;height:280px;">
                            <canvas id="topDiagnosesChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ══════════════════════════════════════════ --}}
            {{-- SECTION: Disease Statistics               --}}
            {{-- ══════════════════════════════════════════ --}}
            <section id="section-diseases" class="section-content hidden mt-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Disease Statistics</h2>
                    <p class="text-gray-500 text-sm mt-1">Most common diagnoses across all departments</p>
                </div>

                @if ($topDiseases->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                            <h3 class="text-base font-bold text-indigo-800">Most Common Diseases</h3>
                            <p class="text-xs text-indigo-500 mt-0.5">Top diagnoses across all departments — selected
                                period</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3">Rank</th>
                                        <th class="px-6 py-3">Disease / Diagnosis</th>
                                        <th class="px-6 py-3 text-right">Total Cases</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topDiseases as $index => $disease)
                                        <tr class="border-t border-gray-100 hover:bg-gray-50">
                                            <td class="px-6 py-3">
                                                <div
                                                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs
                                                    @if ($index === 0) bg-amber-100 text-amber-600 font-bold
                                                    @elseif ($index === 1) bg-gray-200 text-gray-600 font-bold
                                                    @elseif ($index === 2) bg-orange-100 text-orange-600 font-bold
                                                    @else bg-gray-50 text-gray-400 @endif">
                                                    #{{ $index + 1 }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-3 font-medium text-gray-900">
                                                {{ $disease->diagnosis_name }}</td>
                                            <td class="px-6 py-3 text-right">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                    {{ $disease->total_count }}
                                                    {{ $disease->total_count == 1 ? 'case' : 'cases' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
                        No diagnoses recorded for the selected period.
                    </div>
                @endif
            </section>

            {{-- ══════════════════════════════════════════ --}}
            {{-- SECTION: By Department                    --}}
            {{-- ══════════════════════════════════════════ --}}
            <section id="section-departments" class="section-content hidden mt-2">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">By Department</h2>
                    <p class="text-gray-500 text-sm mt-1">Diagnosis breakdown per health center</p>
                </div>

                @if ($groupedStatistics->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
                        No records with diagnoses for the selected period.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($groupedStatistics as $departmentName => $diagnoses)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                                    <h3 class="text-base font-bold text-gray-800 capitalize">{{ $departmentName }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $diagnoses->sum('diagnosis_count') }}
                                        total diagnoses</p>
                                </div>
                                <div class="overflow-y-auto" style="max-height:380px;">
                                    <ul class="divide-y divide-gray-100">
                                        @foreach ($diagnoses as $stat)
                                            @php $uid = md5($departmentName . $stat->diagnosis_name); @endphp
                                            <li class="hover:bg-gray-50 transition">
                                                <div class="px-6 py-3 flex items-center justify-between">
                                                    <div class="flex items-center space-x-3">
                                                        <div
                                                            class="w-7 h-7 rounded-full flex items-center justify-center text-xs
                                                            @if ($loop->index === 0) bg-amber-100 text-amber-600 font-bold
                                                            @elseif ($loop->index === 1) bg-gray-200 text-gray-600 font-bold
                                                            @elseif ($loop->index === 2) bg-orange-100 text-orange-600 font-bold
                                                            @else bg-gray-50 text-gray-400 @endif">
                                                            #{{ $loop->index + 1 }}
                                                        </div>
                                                        <p class="text-sm text-gray-800">{{ $stat->diagnosis_name }}
                                                        </p>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                            {{ $stat->diagnosis_count }}
                                                            {{ $stat->diagnosis_count == 1 ? 'case' : 'cases' }}
                                                        </span>
                                                        <button type="button"
                                                            onclick="togglePatients('{{ $uid }}', '{{ addslashes($stat->diagnosis_name) }}', '{{ addslashes($departmentName) }}')"
                                                            id="btn-{{ $uid }}"
                                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-600 text-white hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1">
                                                            <svg class="w-3.5 h-3.5 mr-1" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            <span id="btn-text-{{ $uid }}">View</span>
                                                        </button>
                                                    </div>
                                                </div>
                                                {{-- Collapsible patient panel --}}
                                                <div id="panel-{{ $uid }}"
                                                    class="hidden bg-indigo-50/40 border-t border-indigo-100 px-6 py-3">
                                                    <div id="loader-{{ $uid }}"
                                                        class="flex items-center justify-center py-4 text-xs text-gray-400">
                                                        <svg class="animate-spin h-4 w-4 mr-2 text-indigo-500"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                                        </svg>
                                                        Loading patients…
                                                    </div>
                                                    <div id="content-{{ $uid }}"></div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

        </main>
    </div>

    {{-- ── Chart.js ─────────────────────────────────────────────── --}}
    <script>
        // ── Sidebar navigation ────────────────────────────────────
        const sections = ['overview', 'analytics', 'diseases', 'departments'];

        function showSection(id) {
            sections.forEach(s => {
                document.getElementById('section-' + s).classList.toggle('hidden', s !== id);
                const link = document.getElementById('link-' + s);
                link.classList.toggle('active', s === id);
            });
            // Re-render charts when analytics tab is shown
            if (id === 'analytics') {
                Object.values(charts).forEach(c => c.resize());
            }
        }

        // Handle hash on load
        const hash = location.hash.replace('#', '') || 'overview';
        if (sections.includes(hash)) showSection(hash);

        // ── Diagnosis → Patient drill-down ───────────────────────────
        const currentFilter = @json($filter);
        const patientCache = {}; // uid → already fetched

        function togglePatients(uid, diagnosisName, departmentName) {
            const panel = document.getElementById('panel-' + uid);
            const btnText = document.getElementById('btn-text-' + uid);
            const loader = document.getElementById('loader-' + uid);
            const content = document.getElementById('content-' + uid);

            // If panel is visible → just hide it
            if (!panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                btnText.textContent = 'View';
                return;
            }

            // Show panel
            panel.classList.remove('hidden');
            btnText.textContent = 'Hide';

            // If already fetched, skip AJAX
            if (patientCache[uid]) return;

            // Fetch patients
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
                        const dob = p.date_of_birth ?
                            new Date(p.date_of_birth).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }) :
                            '—';
                        const recDate = p.created_on ?
                            new Date(p.created_on).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }) :
                            '—';
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

        // ── Chart helpers ─────────────────────────────────────────
        const bgPalette = [
            'rgba(79,70,229,0.8)', 'rgba(59,130,246,0.8)', 'rgba(20,184,166,0.8)',
            'rgba(245,158,11,0.8)', 'rgba(244,63,94,0.8)', 'rgba(139,92,246,0.8)',
            'rgba(16,185,129,0.8)', 'rgba(236,72,153,0.8)', 'rgba(99,102,241,0.8)', 'rgba(234,179,8,0.8)',
        ];
        const borderPalette = bgPalette.map(c => c.replace('0.8)', '1)'));

        const baseOpts = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0,0,0,0.04)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                },
            },
        };

        const charts = {};

        // 1) Appointments per month
        const apmData = @json($appointmentsPerMonth);
        charts.apm = new Chart(document.getElementById('appointmentsPerMonthChart'), {
            type: 'bar',
            data: {
                labels: apmData.map(r => {
                    const [y, m] = r.month.split('-');
                    return new Date(y, m - 1).toLocaleString('default', {
                        month: 'short',
                        year: '2-digit'
                    });
                }),
                datasets: [{
                    label: 'Appointments',
                    data: apmData.map(r => r.total),
                    backgroundColor: 'rgba(79,70,229,0.8)',
                    borderColor: 'rgba(79,70,229,1)',
                    borderWidth: 1,
                    borderRadius: 5
                }],
            },
            options: baseOpts,
        });

        // 2) Patients per department
        const ppdData = @json($patientsPerDepartment);
        charts.ppd = new Chart(document.getElementById('patientsPerDepartmentChart'), {
            type: 'bar',
            data: {
                labels: ppdData.map(r => r.department_name),
                datasets: [{
                    label: 'Patients',
                    data: ppdData.map(r => r.patient_count),
                    backgroundColor: ppdData.map((_, i) => bgPalette[i % bgPalette.length]),
                    borderColor: ppdData.map((_, i) => borderPalette[i % borderPalette.length]),
                    borderWidth: 1,
                    borderRadius: 5
                }],
            },
            options: baseOpts,
        });

        // 3) Top diagnoses this month (horizontal)
        const tdData = @json($topDiagnosesThisMonth);
        charts.td = new Chart(document.getElementById('topDiagnosesChart'), {
            type: 'bar',
            data: {
                labels: tdData.map(r => r.diagnosis_name),
                datasets: [{
                    label: 'Cases',
                    data: tdData.map(r => r.total_count),
                    backgroundColor: tdData.map((_, i) => bgPalette[i % bgPalette.length]),
                    borderColor: tdData.map((_, i) => borderPalette[i % borderPalette.length]),
                    borderWidth: 1,
                    borderRadius: 5
                }],
            },
            options: {
                ...baseOpts,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        }
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            },
        });
    </script>
</body>

</html>
