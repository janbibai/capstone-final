@extends('welcome')

@section('title', 'Book Appointment')

@section('content')

<div class="relative min-h-screen py-12 px-4 sm:px-6 lg:px-8 font-montserrat">
    {{-- Background Image --}}
    <div class="fixed inset-0 -z-10">
        <img src="{{ asset('images/pic.jpg') }}" alt="" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-[2px]"></div>
    </div>

    <div class="max-w-4xl mx-auto">

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl font-montserrat">
                Patient Registration
            </h2>
            <p class="mt-4 text-lg text-slate-300 max-w-2xl mx-auto">
                Please fill in your details below to register and schedule your visit.
            </p>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 rounded-xl bg-emerald-50 text-emerald-800 border-l-4 border-emerald-500 shadow-sm transition-all" role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-emerald-900">Appointment Booked Successfully!</h3>
                            <p class="text-emerald-700 text-sm mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="this.parentElement.parentElement.style.display='none'" 
                            class="text-emerald-600 hover:text-emerald-900 ml-4 transition-colors" 
                            aria-label="Dismiss success message">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 p-4 rounded-xl bg-red-50 text-red-800 border-l-4 border-red-500 shadow-sm" role="alert">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-red-900 mb-2">Please correct the following errors:</h3>
                        <ul class="list-disc pl-5 space-y-1 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.style.display='none'" 
                            class="text-red-600 hover:text-red-900 transition-colors" aria-label="Dismiss error message">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- ===== TAB SWITCHER ===== --}}
        <div class="mb-6 flex rounded-2xl overflow-hidden shadow-sm ring-1 ring-slate-200 bg-white" role="tablist" aria-label="Patient type">
            <button type="button" id="tab-new" role="tab" aria-selected="true" aria-controls="panel-new"
                    class="flex-1 py-3.5 text-sm font-semibold transition-all focus:outline-none tab-active">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    New Patient
                </span>
            </button>
            <button type="button" id="tab-returning" role="tab" aria-selected="false" aria-controls="panel-returning"
                    class="flex-1 py-3.5 text-sm font-semibold transition-all focus:outline-none tab-inactive border-l border-slate-200">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Returning Patient
                </span>
            </button>
        </div>

        <style>
            .tab-active  { background: #059669; color: #fff; }
            .tab-inactive { background: #fff; color: #475569; }
            .tab-inactive:hover { background: #f0fdf4; color: #065f46; }
        </style>

        {{-- ===================================================== --}}
        {{-- PANEL 1 — NEW PATIENT (existing form, unchanged)       --}}
        {{-- ===================================================== --}}
        <div id="panel-new" role="tabpanel" aria-labelledby="tab-new">

            <form method="POST" action="{{ route('appointment.storePatient') }}" id="appointment-form" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-6 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                        <h3 class="text-lg font-semibold text-slate-800">Personal Information</h3>
                        <p class="text-sm text-slate-500 mt-1">Provide your basic identity details.</p>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            <div>
                                <label for="first_name" class="block text-sm font-semibold text-slate-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required maxlength="30" aria-describedby="first_name-error"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('first_name') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                       placeholder="First Name">
                                @error('first_name')
                                    <p id="first_name-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="middle_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" maxlength="30"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                       placeholder="Middle Name">
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required maxlength="30" aria-describedby="last_name-error"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('last_name') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                       placeholder="Last Name">
                                @error('last_name')
                                    <p id="last_name-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="date_of_birth" class="block text-sm font-semibold text-slate-700 mb-1.5">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" required aria-describedby="date_of_birth-error date_of_birth-help"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('date_of_birth') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                @error('date_of_birth')
                                    <p id="date_of_birth-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="gender" class="block text-sm font-semibold text-slate-700 mb-1.5">Gender <span class="text-red-500">*</span></label>
                                <select id="gender" name="gender" required aria-describedby="gender-error"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none @error('gender') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                    <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p id="gender-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Valid Government ID <span class="text-red-500">*</span></label>
                            <div id="id-upload-area" 
                                 class="relative border-2 border-dashed border-slate-300 bg-slate-50 rounded-2xl p-8 text-center hover:bg-slate-100 hover:border-emerald-400 transition-all cursor-pointer @error('valid_id') border-red-300 bg-red-50 @enderror"
                                 onclick="document.getElementById('valid_id').click()">
                                
                                <div id="upload-prompt" class="space-y-3">
                                    <div class="w-12 h-12 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100">
                                        <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                    </div>
                                    <div class="text-sm text-slate-600">
                                        <span class="font-semibold text-emerald-600 hover:text-emerald-500">Click to upload</span> or drag and drop
                                    </div>
                                    <p class="text-xs text-slate-500">JPEG, PNG up to 2MB</p>
                                </div>

                                <div id="id-preview-container" class="hidden">
                                    <img id="id-preview" src="" alt="ID Preview" class="mx-auto max-h-40 rounded-lg shadow-sm ring-1 ring-slate-200">
                                    <div id="id-file-info" class="mt-3 text-sm font-medium text-slate-700"></div>
                                    <button type="button" id="remove-id-btn" 
                                            class="mt-2 text-sm text-red-500 hover:text-red-700 font-medium inline-flex items-center transition-colors"
                                            onclick="event.stopPropagation(); removeIdFile()">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Remove File
                                    </button>
                                </div>
                            </div>
                            <input type="file" id="valid_id" name="valid_id" accept="image/jpeg,image/png" class="hidden" aria-describedby="valid_id-help valid_id-error">
                            @error('valid_id')
                                <p id="valid_id-error" class="text-red-500 text-xs mt-2 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-6 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                        <h3 class="text-lg font-semibold text-slate-800">Contact Details</h3>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone Number</label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" pattern="09[0-9]{9}|[0-9]{11}" maxlength="13" aria-describedby="phone-help phone-error"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('phone') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                       placeholder="09XXXXXXXXX">
                                <p id="phone-help" class="text-xs text-slate-500 mt-1.5">Format: 09XXXXXXXXX (11 digits)</p>
                                @error('phone')
                                    <p id="phone-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email Address (OPTIONAL)</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" maxlength="30" aria-describedby="email-error"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror"
                                       placeholder="example@email.com">
                                @error('email')
                                    <p id="email-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="barangay" class="block text-sm font-semibold text-slate-700 mb-1.5">Barangay</label>
                                <select id="barangay" name="barangay"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none">
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Poblacion</option>
                                </select>
                            </div>
                            <div>
                                <label for="purok" class="block text-sm font-semibold text-slate-700 mb-1.5">Purok</label>
                                <select id="purok" name="purok"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none">
                                    <option value="" disabled {{ old('purok') ? '' : 'selected' }}>Select Purok</option>
                                    <option value="1" {{ old('purok') == '1' ? 'selected' : '' }}>Purok 1</option>
                                    <option value="2" {{ old('purok') == '2' ? 'selected' : '' }}>Purok 2</option>
                                    <option value="3" {{ old('purok') == '3' ? 'selected' : '' }}>Purok 3</option>
                                    <option value="4" {{ old('purok') == '4' ? 'selected' : '' }}>Purok 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-8 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                        <h3 class="text-lg font-semibold text-slate-800">Service Details</h3>
                        <p class="text-sm text-slate-500 mt-1">Select your service and preferred schedule.</p>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div>
                            <label for="service_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Service Type <span class="text-red-500">*</span></label>
                            <select id="service_id" name="service_id" required aria-describedby="service_id-error service_id-help"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none @error('service_id') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                <option value="" disabled {{ old('service_id') ? '' : 'selected' }}>-- Choose a Service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ ucfirst($service->name) }} — {{ $service->description }} ({{ $service->estimated_time }} min)
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <p id="service_id-error" class="text-red-500 text-xs mt-2 font-medium" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="schedule" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Date <span class="text-red-500">*</span></label>
                                <input type="date" id="schedule" name="schedule" value="{{ old('schedule') }}" min="{{ date('Y-m-d') }}" required aria-describedby="schedule-error schedule-help"
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('schedule') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                <p id="schedule-help" class="text-xs text-slate-500 mt-1.5">Select today or a future date</p>
                                @error('schedule')
                                    <p id="schedule-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="schedule_time" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Time <span class="text-red-500">*</span></label>
                                <select id="schedule_time" name="schedule_time" required aria-describedby="schedule_time-error schedule_time-help"
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none @error('schedule_time') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                    <option value="" disabled {{ old('schedule_time') ? '' : 'selected' }}>-- Choose a Time --</option>
                                    @php
                                        $start = strtotime('08:00');
                                        $end = strtotime('17:00');
                                        $oldTime = old('schedule_time');
                                    @endphp
                                    @for ($i = $start; $i <= $end; $i += 900)
                                        @php
                                            if ($i >= strtotime('12:00') && $i < strtotime('13:00')) { continue; }
                                            $timeValue = date('H:i', $i);
                                            $timeLabel = date('h:i A', $i);
                                        @endphp
                                        <option value="{{ $timeValue }}" {{ $oldTime == $timeValue ? 'selected' : '' }}>
                                            {{ $timeLabel }}
                                        </option>
                                    @endfor
                                </select>
                                <p id="schedule_time-help" class="text-xs text-slate-500 mt-1.5">Business hours: 8:00 AM - 5:00 PM</p>
                                @error('schedule_time')
                                    <p id="schedule_time-error" class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submit-btn"
                            class="w-full sm:w-auto inline-flex items-center justify-center bg-emerald-600 text-white px-8 py-3.5 rounded-xl shadow-sm hover:bg-emerald-700 hover:shadow transition-all font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-70 disabled:cursor-not-allowed min-w-[200px]">
                        <span id="submit-text">Register</span>
                        <span id="submit-spinner" class="hidden flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>

            </form>
        </div>{{-- end #panel-new --}}

        {{-- ===================================================== --}}
        {{-- PANEL 2 — RETURNING PATIENT                           --}}
        {{-- ===================================================== --}}
        <div id="panel-returning" role="tabpanel" aria-labelledby="tab-returning" class="hidden">

            {{-- Step 1 — Lookup form --}}
            <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-6 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                    <h3 class="text-lg font-semibold text-slate-800">Find Your Record</h3>
                    <p class="text-sm text-slate-500 mt-1">Enter your name and date of birth to look up your existing record.</p>
                </div>
                <div class="p-6 md:p-8 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="lookup_first_name" class="block text-sm font-semibold text-slate-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                            <input type="text" id="lookup_first_name" maxlength="30" placeholder="First Name"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                        </div>
                        <div>
                            <label for="lookup_last_name" class="block text-sm font-semibold text-slate-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                            <input type="text" id="lookup_last_name" maxlength="30" placeholder="Last Name"
                                   class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                        </div>
                    </div>
                    <div>
                        <label for="lookup_dob" class="block text-sm font-semibold text-slate-700 mb-1.5">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" id="lookup_dob" max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                    </div>

                    {{-- Lookup feedback --}}
                    <div id="lookup-not-found" class="hidden rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800 flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 flex-shrink-0 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>No record found. Please double-check your details, or use the <strong>New Patient</strong> tab to register.</span>
                    </div>

                    <div id="lookup-error-msg" class="hidden rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800"></div>

                    <button type="button" id="lookup-btn"
                            class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg id="lookup-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <svg id="lookup-spinner" class="w-4 h-4 animate-spin hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Find My Record
                    </button>
                </div>
            </div>

            {{-- Step 2 — Patient found, show appointment form --}}
            <div id="returning-appointment-section" class="hidden">

                {{-- Patient card --}}
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-6 py-4 mb-6 flex items-center gap-4">
                    <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-emerald-600 font-semibold uppercase tracking-wide">Record Found</p>
                        <p id="returning-patient-name" class="text-base font-bold text-emerald-900"></p>
                    </div>
                    <button type="button" id="reset-lookup-btn"
                            class="ml-auto text-xs text-slate-500 hover:text-slate-700 underline focus:outline-none">
                        Not you? Search again
                    </button>
                </div>

                {{-- Appointment form for returning patient --}}
                <form method="POST" action="{{ route('appointment.storeExisting') }}" id="returning-form">
                @csrf
                    <input type="hidden" id="returning_patient_id" name="patient_id" value="{{ old('patient_id') }}">
                    <input type="hidden" id="returning_patient_name" name="patient_name" value="{{ old('patient_name') }}">


                    <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-8 overflow-hidden">
                        <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                            <h3 class="text-lg font-semibold text-slate-800">Appointment Details</h3>
                            <p class="text-sm text-slate-500 mt-1">Select your service and preferred schedule.</p>
                        </div>
                        <div class="p-6 md:p-8 space-y-6">
                            <div>
                                <label for="ret_service_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Service Type <span class="text-red-500">*</span></label>
                                <select id="ret_service_id" name="service_id" required
                                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none">
                                    <option value="" disabled selected>-- Choose a Service --</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">
                                            {{ ucfirst($service->name) }} — {{ $service->description }} ({{ $service->estimated_time }} min)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label for="ret_schedule" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Date <span class="text-red-500">*</span></label>
                                    <input type="date" id="ret_schedule" name="schedule" min="{{ date('Y-m-d') }}" required
                                           value="{{ old('schedule') }}"
                                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 @error('schedule') border-red-300 @enderror">
                                    <p class="text-xs text-slate-500 mt-1.5">Select today or a future date</p>
                                    @error('schedule')
                                        <p class="text-red-500 text-xs mt-1.5 font-medium" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="ret_schedule_time" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Time <span class="text-red-500">*</span></label>
                                    <select id="ret_schedule_time" name="schedule_time" required
                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none">
                                        <option value="" disabled selected>-- Choose a Time --</option>
                                        @php
                                            $start2 = strtotime('08:00');
                                            $end2   = strtotime('17:00');
                                        @endphp
                                        @for ($j = $start2; $j <= $end2; $j += 900)
                                            @php
                                                if ($j >= strtotime('12:00') && $j < strtotime('13:00')) { continue; }
                                                $tv2 = date('H:i', $j);
                                                $tl2 = date('h:i A', $j);
                                            @endphp
                                            <option value="{{ $tv2 }}">{{ $tl2 }}</option>
                                        @endfor
                                    </select>
                                    <p class="text-xs text-slate-500 mt-1.5">Business hours: 8:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" id="ret-submit-btn"
                                class="w-full sm:w-auto inline-flex items-center justify-center bg-emerald-600 text-white px-8 py-3.5 rounded-xl shadow-sm hover:bg-emerald-700 hover:shadow transition-all font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-70 disabled:cursor-not-allowed min-w-[200px]">
                            <span id="ret-submit-text">Book Appointment</span>
                            <span id="ret-submit-spinner" class="hidden flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>{{-- end #panel-returning --}}

    </div>
</div>

<script>
    // ===================================================================
    // TAB SWITCHER
    // ===================================================================
    const tabNew       = document.getElementById('tab-new');
    const tabReturning = document.getElementById('tab-returning');
    const panelNew     = document.getElementById('panel-new');
    const panelRet     = document.getElementById('panel-returning');

    function activateTab(active, inactive, showPanel, hidePanel) {
        active.setAttribute('aria-selected', 'true');
        inactive.setAttribute('aria-selected', 'false');
        active.classList.replace('tab-inactive', 'tab-active');
        inactive.classList.replace('tab-active', 'tab-inactive');
        showPanel.classList.remove('hidden');
        hidePanel.classList.add('hidden');
    }

    tabNew.addEventListener('click', () => {
        activateTab(tabNew, tabReturning, panelNew, panelRet);
        resetLookup();
    });

    tabReturning.addEventListener('click', () => {
        activateTab(tabReturning, tabNew, panelRet, panelNew);
    });

    // Auto-switch to Returning Patient tab if the page loaded with errors
    // from the returning-patient form (identified by having a patient_id in old input)
    @if($errors->any() && old('patient_id'))
        activateTab(tabReturning, tabNew, panelRet, panelNew);
        // Re-populate the lookup result section with the old patient data
        document.getElementById('returning_patient_id').value = '{{ old('patient_id') }}';
        document.getElementById('returning-patient-name').textContent = '{{ old('patient_name', 'Patient') }}';
        document.getElementById('returning-appointment-section').classList.remove('hidden');
    @endif

    // ===================================================================
    // RETURNING PATIENT — LOOKUP
    // ===================================================================
    const lookupBtn         = document.getElementById('lookup-btn');
    const lookupIcon        = document.getElementById('lookup-icon');
    const lookupSpinner     = document.getElementById('lookup-spinner');
    const lookupNotFound    = document.getElementById('lookup-not-found');
    const lookupErrorMsg    = document.getElementById('lookup-error-msg');
    const retSection        = document.getElementById('returning-appointment-section');
    const retPatientName    = document.getElementById('returning-patient-name');
    const retPatientId      = document.getElementById('returning_patient_id');
    const resetLookupBtn    = document.getElementById('reset-lookup-btn');

    lookupBtn.addEventListener('click', async () => {
        const firstName = document.getElementById('lookup_first_name').value.trim();
        const lastName  = document.getElementById('lookup_last_name').value.trim();
        const dob       = document.getElementById('lookup_dob').value;

        // Basic client-side validation
        lookupNotFound.classList.add('hidden');
        lookupErrorMsg.classList.add('hidden');

        if (!firstName || !lastName || !dob) {
            lookupErrorMsg.textContent = 'Please fill in all three fields before searching.';
            lookupErrorMsg.classList.remove('hidden');
            return;
        }

        // Show spinner
        lookupBtn.disabled = true;
        lookupIcon.classList.add('hidden');
        lookupSpinner.classList.remove('hidden');

        try {
            const params = new URLSearchParams({ first_name: firstName, last_name: lastName, date_of_birth: dob });
            const response = await fetch(`/appointments/lookup-patient?${params}`);
            const data = await response.json();

            if (response.ok && data.found) {
                // Patient found — show appointment section
                retPatientName.textContent = data.name;
                retPatientId.value = data.id;
                document.getElementById('returning_patient_name').value = data.name;
                retSection.classList.remove('hidden');
                // Scroll into view gently
                retSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                // Not found
                retSection.classList.add('hidden');
                lookupNotFound.classList.remove('hidden');
            }
        } catch (err) {
            lookupErrorMsg.textContent = 'Something went wrong. Please try again.';
            lookupErrorMsg.classList.remove('hidden');
        } finally {
            lookupBtn.disabled = false;
            lookupIcon.classList.remove('hidden');
            lookupSpinner.classList.add('hidden');
        }
    });

    function resetLookup() {
        document.getElementById('lookup_first_name').value = '';
        document.getElementById('lookup_last_name').value  = '';
        document.getElementById('lookup_dob').value        = '';
        retSection.classList.add('hidden');
        lookupNotFound.classList.add('hidden');
        lookupErrorMsg.classList.add('hidden');
        retPatientId.value     = '';
        retPatientName.textContent = '';
        // Reset returning form selects
        document.getElementById('ret_service_id').value      = '';
        document.getElementById('ret_schedule').value        = '';
        document.getElementById('ret_schedule_time').value   = '';
        resetRetTimeOptions();
    }

    resetLookupBtn.addEventListener('click', resetLookup);

    // ===================================================================
    // RETURNING PATIENT — BOOKED / PAST TIMES (mirrors new-patient logic)
    // ===================================================================
    const retDateInput = document.getElementById('ret_schedule');
    const retTimeSelect = document.getElementById('ret_schedule_time');

    function resetRetTimeOptions() {
        Array.from(retTimeSelect.options).forEach(o => {
            if (!o.value) return;
            o.disabled = false;
            o.text = o.text.replace(' (Booked)', '').replace(' (Passed)', '');
        });
    }

    function isRetDateToday() {
        const v = retDateInput.value;
        if (!v) return false;
        const t = new Date();
        const todayStr = t.getFullYear() + '-' +
            String(t.getMonth() + 1).padStart(2, '0') + '-' +
            String(t.getDate()).padStart(2, '0');
        return v === todayStr;
    }

    function isTimePassed(val) {
        const now = new Date();
        const [h, m] = val.split(':').map(Number);
        return (h < now.getHours()) || (h === now.getHours() && m <= now.getMinutes());
    }

    function updateRetTimeOptions(booked) {
        resetRetTimeOptions();
        const isToday = isRetDateToday();
        Array.from(retTimeSelect.options).forEach(o => {
            if (!o.value) return;
            if (isToday && isTimePassed(o.value)) {
                o.disabled = true;
                if (!o.text.includes('(Passed)')) o.text += ' (Passed)';
                if (o.selected) retTimeSelect.value = '';
                return;
            }
            if (booked.includes(o.value)) {
                o.disabled = true;
                if (!o.text.includes('(Booked)')) o.text += ' (Booked)';
                if (o.selected) retTimeSelect.value = '';
            }
        });
    }

    async function fetchRetBookedTimes(date) {
        if (!date) { resetRetTimeOptions(); return; }
        let booked = [];
        try {
            const res = await fetch(`/appointments/booked-times?date=${date}`);
            booked = await res.json();
        } catch (e) { console.error(e); }
        updateRetTimeOptions(booked);
    }

    retDateInput.addEventListener('change', () => fetchRetBookedTimes(retDateInput.value));

    // ===================================================================
    // RETURNING PATIENT — FORM SUBMISSION SPINNER
    // ===================================================================
    document.getElementById('returning-form').addEventListener('submit', function () {
        const btn = document.getElementById('ret-submit-btn');
        btn.disabled = true;
        document.getElementById('ret-submit-text').classList.add('hidden');
        document.getElementById('ret-submit-spinner').classList.remove('hidden');
    });

    // ===================================================================
    // NEW PATIENT — existing JS (unchanged)
    // ===================================================================
    document.getElementById('appointment-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
    });

    const form = document.getElementById('appointment-form');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (!this.validity.valid && this.value !== '') {
                this.classList.add('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/10');
                this.classList.remove('border-slate-200', 'focus:border-emerald-500', 'focus:ring-emerald-500/10');
            } else {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/10');
                this.classList.add('border-slate-200', 'focus:border-emerald-500', 'focus:ring-emerald-500/10');
            }
        });
        input.addEventListener('input', function() {
            if (this.validity.valid) {
                this.classList.remove('border-red-300', 'focus:border-red-500', 'focus:ring-red-500/10');
                this.classList.add('border-slate-200', 'focus:border-emerald-500', 'focus:ring-emerald-500/10');
            }
        });
    });

    inputs.forEach(input => {
        if(input.type !== 'file') {
            const savedValue = localStorage.getItem(`appointment_${input.name}`);
            if (savedValue && !input.value) input.value = savedValue;
            input.addEventListener('change', function() {
                localStorage.setItem(`appointment_${input.name}`, this.value);
            });
        }
    });

    form.addEventListener('submit', function() {
        inputs.forEach(input => {
            if(input.type !== 'file') localStorage.removeItem(`appointment_${input.name}`);
        });
    });

    const scheduleDateInput = document.getElementById('schedule');
    const scheduleTimeSelect = document.getElementById('schedule_time');

    async function fetchBookedTimes(date) {
        if (!date) { resetTimeOptions(); return; }
        let bookedTimes = [];
        try {
            const response = await fetch(`/appointments/booked-times?date=${date}`);
            bookedTimes = await response.json();
        } catch (error) { console.error('Error fetching booked times:', error); }
        updateTimeOptions(bookedTimes);
    }

    function isSelectedDateToday() {
        const selectedDate = scheduleDateInput.value;
        if (!selectedDate) return false;
        const today = new Date();
        const todayStr = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');
        return selectedDate === todayStr;
    }

    function isTimePast(timeValue) {
        const now = new Date();
        const [hours, minutes] = timeValue.split(':').map(Number);
        return (hours < now.getHours()) || (hours === now.getHours() && minutes <= now.getMinutes());
    }

    function resetTimeOptions() {
        Array.from(scheduleTimeSelect.options).forEach(option => {
            if (option.value === '') return;
            option.disabled = false;
            option.text = option.text.replace(' (Booked)', '').replace(' (Passed)', '');
        });
    }

    function updateTimeOptions(bookedTimes) {
        resetTimeOptions();
        const isToday = isSelectedDateToday();
        Array.from(scheduleTimeSelect.options).forEach(option => {
            if (!option.value) return;
            if (isToday && isTimePast(option.value)) {
                option.disabled = true;
                if (!option.text.includes('(Passed)')) option.text += ' (Passed)';
                if (option.selected) scheduleTimeSelect.value = '';
                return;
            }
            if (bookedTimes.includes(option.value)) {
                option.disabled = true;
                if (!option.text.includes('(Booked)')) option.text += ' (Booked)';
                if (option.selected) scheduleTimeSelect.value = '';
            }
        });
    }

    scheduleDateInput.addEventListener('change', function() { fetchBookedTimes(this.value); });

    setTimeout(() => {
        if (scheduleDateInput.value) fetchBookedTimes(scheduleDateInput.value);
    }, 100);

    // === Valid ID Upload Preview ===
    const validIdInput      = document.getElementById('valid_id');
    const uploadArea        = document.getElementById('id-upload-area');
    const uploadPrompt      = document.getElementById('upload-prompt');
    const previewContainer  = document.getElementById('id-preview-container');
    const previewImg        = document.getElementById('id-preview');
    const fileInfo          = document.getElementById('id-file-info');

    validIdInput.addEventListener('change', function() { handleIdFile(this.files[0]); });

    function handleIdFile(file) {
        if (!file) return;
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) { alert('Please upload a JPEG or PNG image.'); validIdInput.value = ''; return; }
        if (file.size > 2 * 1024 * 1024) { alert('File size must not exceed 2MB.'); validIdInput.value = ''; return; }
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            uploadArea.classList.remove('border-slate-300', 'border-dashed', 'bg-slate-50', 'hover:bg-slate-100');
            uploadArea.classList.add('border-emerald-400', 'border-solid', 'bg-white');
            fileInfo.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        };
        reader.readAsDataURL(file);
    }

    function removeIdFile() {
        validIdInput.value = '';
        previewImg.src = '';
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        uploadArea.classList.add('border-slate-300', 'border-dashed', 'bg-slate-50', 'hover:bg-slate-100');
        uploadArea.classList.remove('border-emerald-400', 'border-solid', 'bg-white');
        fileInfo.textContent = '';
    }

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-emerald-400', 'bg-emerald-50/50');
    });
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-emerald-400', 'bg-emerald-50/50');
    });
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-emerald-400', 'bg-emerald-50/50');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            validIdInput.files = dataTransfer.files;
            handleIdFile(file);
        }
    });
</script>

@endsection