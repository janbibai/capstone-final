<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medical Records</title>
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
                        <h1 class="text-lg font-bold text-gray-800">Medical Records</h1>
                        <p class="text-xs text-gray-500">View all patient records</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('doctor.dashboard') }}"
                        class="text-gray-600 hover:text-blue-600 font-medium text-sm">Dashboard</a>
                    @auth
                        <form method="POST" action="{{ route('staff.logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Logout</button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700 border border-green-300">
                    {{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Records</h2>
                        <p class="text-xs text-gray-500 mt-1">
                            Records for:
                            <span class="font-semibold">
                                @if ($date === 'all')
                                    All dates
                                @else
                                    {{ \Carbon\Carbon::parse($date ?? now()->toDateString())->format('F d, Y') }}
                                @endif
                            </span>
                            @if ($patientId && $records->first() && $records->first()->patient)
                                — for {{ $records->first()->patient->full_name }}
                            @elseif($patientId)
                                — for selected patient
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('doctor.medical-records') }}" id="filterForm"
                            class="flex flex-col md:flex-row md:items-center gap-2 text-sm">
                            <div class="flex items-center gap-2">
                                <label class="text-gray-600">Date:</label>
                                <input type="date" name="date" id="dateInput" value="{{ $date !== 'all' ? ($date ?? now()->toDateString()) : now()->toDateString() }}"
                                    class="border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-400 focus:outline-none {{ $date === 'all' ? 'opacity-50' : '' }}" {{ $date === 'all' ? 'disabled' : '' }}>
                            </div>
                            <div class="flex items-center gap-2">
                                <label for="showAll" class="text-gray-600 cursor-pointer select-none flex items-center gap-1.5">
                                    <input type="checkbox" id="showAll" {{ $date === 'all' ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-400 cursor-pointer">
                                    All Records
                                </label>
                            </div>
                            <input type="hidden" name="date" id="dateHidden" value="{{ $date ?? now()->toDateString() }}" disabled>
                            <div class="flex items-center gap-2">
                                <label class="text-gray-600">Patient:</label>
                                <input type="text" name="search" value="{{ $search ?? request('search') }}"
                                    placeholder="Search by patient name"
                                    class="border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-blue-400 focus:outline-none w-full md:w-56">
                            </div>
                            @if ($patientId)
                                <input type="hidden" name="patient_id" value="{{ $patientId }}">
                            @endif
                            <button type="submit"
                                class="bg-blue-600 text-white px-3 py-1.5 rounded-lg font-semibold hover:bg-blue-700 transition">
                                Filter
                            </button>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const showAll = document.getElementById('showAll');
                                const dateInput = document.getElementById('dateInput');
                                const dateHidden = document.getElementById('dateHidden');

                                function toggleDate() {
                                    if (showAll.checked) {
                                        dateInput.disabled = true;
                                        dateInput.classList.add('opacity-50');
                                        dateHidden.disabled = false;
                                        dateHidden.value = 'all';
                                    } else {
                                        dateInput.disabled = false;
                                        dateInput.classList.remove('opacity-50');
                                        dateHidden.disabled = true;
                                    }
                                }

                                showAll.addEventListener('change', toggleDate);
                            });
                        </script>
                        @if ($patientId)
                            <a href="{{ route('doctor.medical-records', ['date' => $date ?? now()->toDateString(), 'search' => $search ?? request('search')]) }}"
                                class="text-sm text-blue-600 hover:underline">
                                Show all patients
                            </a>
                        @endif
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Patient</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Diagnosis</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Details</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Created by</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Last updated</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('doctor.medical-records', ['patient_id' => $record->patient_id, 'date' => $date ?? now()->toDateString()]) }}"
                                            class="text-blue-600 hover:underline">
                                            {{ $record->patient ? $record->patient->full_name : '—' }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $record->diagnosis ? $record->diagnosis->name : '—' }}
                                    </td>
                                    <td class="px-4 py-3 max-w-xs truncate" title="{{ $record->details }}">
                                        {{ Str::limit($record->details, 50) }}</td>
                                    <td class="px-4 py-3">{{ $record->creator ? $record->creator->name : '—' }}</td>
                                    <td class="px-4 py-3">
                                        {{ $record->created_on ? $record->created_on->format('M d, Y H:i') : '—' }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($record->updated_on)
                                            <div class="text-xs text-gray-700">
                                                {{ $record->updated_on->format('M d, Y H:i') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $record->updater ? $record->updater->name : '—' }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($record->patient)
                                            <a href="{{ route('doctor.patients.add-record', ['patient' => $record->patient, 'record_id' => $record->id]) }}"
                                                class="inline-flex items-center gap-1 bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-blue-700 transition">
                                                update diagnosis
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No medical records
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($records->hasPages())
                    <div class="px-6 py-3 border-t border-gray-100">
                        {{ $records->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>
</body>

</html>
