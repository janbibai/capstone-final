<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $currentRecord ? 'Edit Current Diagnosis' : 'Add Medical Record' }} — {{ $patient->full_name }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('doctor.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">{{ $currentRecord ? 'Edit Current Diagnosis' : 'Add Medical Record' }}</h1>
                        <p class="text-xs text-gray-500">{{ $patient->full_name }}</p>
                    </div>
                </div>
                <a href="{{ route('doctor.medical-records', ['patient_id' => $patient->id]) }}"
                   class="text-gray-600 hover:text-blue-600 font-medium text-sm">View records</a>
            </div>
        </div>
    </header>

    <main class="min-h-screen py-10 px-4">
        <div class="max-w-2xl mx-auto">
            @if($errors->any())
                <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-700 border border-red-300">
                    <ul class="list-disc pl-5 space-y-1 text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                @isset($appointment)
                    <div class="mb-4 flex items-center justify-between text-sm">
                        <div class="flex-1">
                            <p class="font-medium text-gray-700">
                                Queue #{{ str_pad($appointment->queue_number, 3, '0', STR_PAD_LEFT) }}
                                · {{ \Carbon\Carbon::parse($appointment->schedule_time)->format('h:i A') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Status:
                                <span class="inline-flex px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>
                        </div>
                        @if($appointment->weight || $appointment->height || $appointment->blood_pressure || $appointment->temperature)
                        <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 flex gap-4">
                            @if($appointment->weight) <div><span class="font-semibold text-gray-700 mb-1 block">Weight</span>{{ $appointment->weight }} kg</div> @endif
                            @if($appointment->height) <div><span class="font-semibold text-gray-700 mb-1 block">Height</span>{{ $appointment->height }} cm</div> @endif
                            @if($appointment->blood_pressure) <div><span class="font-semibold text-gray-700 mb-1 block">BP</span>{{ $appointment->blood_pressure }}</div> @endif
                            @if($appointment->temperature) <div><span class="font-semibold text-gray-700 mb-1 block">Temp</span>{{ $appointment->temperature }} °C</div> @endif
                        </div>
                        @endif
                    </div>
                @endisset
                @if($currentRecord)
                    <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200">
                        <p class="text-sm font-semibold text-blue-900">Current diagnosis</p>
                        <p class="text-sm text-blue-800 mt-1">
                            <span class="font-medium">{{ optional($currentRecord->diagnosis)->name ?? '—' }}</span>
                        </p>
                        <p class="text-xs text-blue-700 mt-2">
                            Created: {{ $currentRecord->created_on ? $currentRecord->created_on->format('M d, Y H:i') : '—' }}
                            @if($currentRecord->updated_on)
                                · Last updated: {{ $currentRecord->updated_on->format('M d, Y H:i') }}
                            @endif
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('doctor.medical-records.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    @if($currentRecord)
                        <input type="hidden" name="record_id" value="{{ $currentRecord->id }}">
                    @endif
                    @isset($appointment)
                        <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                    @endisset

                    <div>
                        <label for="diagnosis_id" class="block text-sm font-medium text-gray-700 mb-1">Select existing diagnosis (optional)</label>
                        <select id="diagnosis_id" name="diagnosis_id"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="">— Choose existing or type new below —</option>
                            @foreach($diagnoses as $d)
                                <option value="{{ $d->id }}"
                                    @selected(old('diagnosis_id', $currentRecord?->diagnosis_id) == $d->id)
                                >{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="diagnosis_name" class="block text-sm font-medium text-gray-700 mb-1">Or type new diagnosis name</label>
                        <input type="text" id="diagnosis_name" name="diagnosis_name" value="{{ old('diagnosis_name') }}"
                               placeholder="e.g. Upper respiratory infection"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Leave blank if you selected an existing diagnosis above.</p>
                    </div>

                    <div>
                        <label for="details" class="block text-sm font-medium text-gray-700 mb-1">Details *</label>
                        <textarea id="details" name="details" rows="4" required
                                  class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                                  placeholder="Clinical notes, findings, treatment...">{{ old('details', $currentRecord?->details) }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-blue-700 transition">
                            {{ $currentRecord ? 'Update medical record' : 'Save medical record' }}
                        </button>
                        <a href="{{ route('doctor.dashboard') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-semibold hover:bg-gray-200 transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const diagnosisSelect = document.getElementById('diagnosis_id');
        const diagnosisName = document.getElementById('diagnosis_name');

        function syncDiagnosisInputs() {
            if (diagnosisSelect.value) {
                diagnosisName.value = '';
                diagnosisName.disabled = true;
                diagnosisName.classList.add('bg-gray-100');
            } else {
                diagnosisName.disabled = false;
                diagnosisName.classList.remove('bg-gray-100');
            }
        }

        diagnosisSelect.addEventListener('change', syncDiagnosisInputs);
        syncDiagnosisInputs();
    </script>
</body>
</html>
