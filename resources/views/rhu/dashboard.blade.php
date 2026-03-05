<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RHU Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
</head>

<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-600 p-2.5 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">RHU Dashboard</h1>
                        <p class="text-xs text-gray-500">Department Statistics</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="hidden md:flex items-center space-x-3 text-sm">
                            <p class="font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span
                                    class="text-indigo-700 font-semibold text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
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
            <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Department Statistics</h2>
                    <p class="text-gray-500 mt-1">Overview of diagnosis frequency across all departments</p>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex items-center space-x-2">
                    <a href="{{ route('rhu.dashboard', ['filter' => 'today']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ $filter === 'today' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                        Today
                    </a>
                    <a href="{{ route('rhu.dashboard', ['filter' => 'week']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ $filter === 'week' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                        This Week
                    </a>
                    <a href="{{ route('rhu.dashboard', ['filter' => 'month']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ $filter === 'month' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                        This Month
                    </a>
                    <a href="{{ route('rhu.dashboard', ['filter' => 'year']) }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition
                            {{ $filter === 'year' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50' }}">
                        This Year
                    </a>
                </div>
            </div>

            {{-- Appointment Analytics Charts --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Appointment Analytics</h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Chart 1: Appointments Per Month --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Appointments Per Month</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="appointmentsPerMonthChart"></canvas>
                        </div>
                    </div>

                    {{-- Chart 2: Patients Per Department --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Patients Per Department</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="patientsPerDepartmentChart"></canvas>
                        </div>
                    </div>

                    {{-- Chart 3: Top Diagnoses This Month --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-sm font-semibold text-gray-600 mb-4">Top Diagnoses This Month</h4>
                        <div style="position: relative; height: 260px;">
                            <canvas id="topDiagnosesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Most Common Diseases Overall --}}
            @if ($topDiseases->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                        <h3 class="text-lg font-bold text-indigo-800">Most Common Diseases</h3>
                        <p class="text-xs text-indigo-500 mt-1">Top diagnoses across all departments</p>
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
                                                class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                                @if ($index === 0) bg-amber-100 text-amber-600 font-bold
                                                @elseif($index === 1) bg-gray-200 text-gray-600 font-bold
                                                @elseif($index === 2) bg-orange-100 text-orange-600 font-bold
                                                @else bg-gray-50 text-gray-400 font-medium @endif text-xs">
                                                #{{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 font-medium text-gray-900">{{ $disease->diagnosis_name }}
                                        </td>
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
            @endif

            {{-- Per-Department Statistics --}}
            @if ($groupedStatistics->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                    <div
                        class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No Statistics Available</h3>
                    <p class="mt-1 text-sm text-gray-500">There are no medical records with diagnoses for the selected
                        period.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($groupedStatistics as $departmentName => $diagnoses)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                                <h3 class="text-lg font-bold text-gray-800">{{ $departmentName }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $diagnoses->sum('diagnosis_count') }} total
                                    diagnoses</p>
                            </div>

                            <div class="flex-1 overflow-y-auto p-0" style="max-height: 400px;">
                                <ul class="divide-y divide-gray-100">
                                    @foreach ($diagnoses as $stat)
                                        <li
                                            class="px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between group">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                                    @if ($loop->index === 0) bg-amber-100 text-amber-600 font-bold
                                                    @elseif($loop->index === 1) bg-gray-200 text-gray-600 font-bold
                                                    @elseif($loop->index === 2) bg-orange-100 text-orange-600 font-bold
                                                    @else bg-gray-50 text-gray-400 font-medium @endif text-xs">
                                                    #{{ $loop->index + 1 }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $stat->diagnosis_name }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                                    {{ $stat->diagnosis_count }}
                                                    {{ $stat->diagnosis_count == 1 ? 'case' : 'cases' }}
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <script>
        const chartColors = {
            indigo: 'rgba(79, 70, 229, 0.8)',
            indigoBorder: 'rgba(79, 70, 229, 1)',
            blue: 'rgba(59, 130, 246, 0.8)',
            blueBorder: 'rgba(59, 130, 246, 1)',
            teal: 'rgba(20, 184, 166, 0.8)',
            tealBorder: 'rgba(20, 184, 166, 1)',
            amber: 'rgba(245, 158, 11, 0.8)',
            amberBorder: 'rgba(245, 158, 11, 1)',
            rose: 'rgba(244, 63, 94, 0.8)',
            roseBorder: 'rgba(244, 63, 94, 1)',
            violet: 'rgba(139, 92, 246, 0.8)',
            violetBorder: 'rgba(139, 92, 246, 1)',
        };

        const bgPalette = [
            chartColors.indigo, chartColors.blue, chartColors.teal,
            chartColors.amber, chartColors.rose, chartColors.violet,
            'rgba(16, 185, 129, 0.8)', 'rgba(236, 72, 153, 0.8)',
            'rgba(99, 102, 241, 0.8)', 'rgba(234, 179, 8, 0.8)',
        ];
        const borderPalette = [
            chartColors.indigoBorder, chartColors.blueBorder, chartColors.tealBorder,
            chartColors.amberBorder, chartColors.roseBorder, chartColors.violetBorder,
            'rgba(16, 185, 129, 1)', 'rgba(236, 72, 153, 1)',
            'rgba(99, 102, 241, 1)', 'rgba(234, 179, 8, 1)',
        ];

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
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
                    },
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    },
                },
            },
        };

        // 1) Appointments Per Month
        const apmData = @json($appointmentsPerMonth);
        new Chart(document.getElementById('appointmentsPerMonthChart'), {
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
                    backgroundColor: chartColors.indigo,
                    borderColor: chartColors.indigoBorder,
                    borderWidth: 1,
                    borderRadius: 6,
                }],
            },
            options: defaultOptions,
        });

        // 2) Patients Per Department
        const ppdData = @json($patientsPerDepartment);
        new Chart(document.getElementById('patientsPerDepartmentChart'), {
            type: 'bar',
            data: {
                labels: ppdData.map(r => r.department_name),
                datasets: [{
                    label: 'Patients',
                    data: ppdData.map(r => r.patient_count),
                    backgroundColor: ppdData.map((_, i) => bgPalette[i % bgPalette.length]),
                    borderColor: ppdData.map((_, i) => borderPalette[i % borderPalette.length]),
                    borderWidth: 1,
                    borderRadius: 6,
                }],
            },
            options: defaultOptions,
        });

        // 3) Top Diagnoses This Month
        const tdData = @json($topDiagnosesThisMonth);
        new Chart(document.getElementById('topDiagnosesChart'), {
            type: 'bar',
            data: {
                labels: tdData.map(r => r.diagnosis_name),
                datasets: [{
                    label: 'Cases',
                    data: tdData.map(r => r.total_count),
                    backgroundColor: tdData.map((_, i) => bgPalette[i % bgPalette.length]),
                    borderColor: tdData.map((_, i) => borderPalette[i % borderPalette.length]),
                    borderWidth: 1,
                    borderRadius: 6,
                }],
            },
            options: {
                ...defaultOptions,
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
                        },
                    },
                    y: {
                        ticks: {
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });
    </script>
</body>

</html>
