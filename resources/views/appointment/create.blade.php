@extends('welcome')

@section('title', 'Book Appointment')
@section('hideHeader', true)
@section('hideFooter', true)

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

        {{-- ===== RETURNING PATIENT BANNER ===== --}}
        <div class="mb-6 rounded-2xl overflow-hidden shadow-sm ring-1 ring-emerald-200 bg-emerald-50 p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-emerald-900 font-bold text-base">Already a patient?</h3>
                    <p class="text-emerald-700 text-sm mt-0.5">Skip the forms and look up your name.</p>
                </div>
            </div>
            <button type="button" onclick="openReturningModal()"
                    class="whitespace-nowrap inline-flex items-center gap-2 bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 flex-shrink-0">
                Find My Record
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>

        {{-- ===================================================== --}}
        {{-- PATIENT REGISTRATION FORM                             --}}
        {{-- ===================================================== --}}
        <div id="panel-new">

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
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Take a selfie <span class="text-red-500">*</span></label>
                            <div id="id-camera-area" 
                                 class="relative border-2 border-dashed border-slate-300 bg-slate-50 rounded-2xl overflow-hidden transition-all @error('valid_id') border-red-300 bg-red-50 @enderror">
                                
                                {{-- Idle state: Take Photo button --}}
                                <div id="camera-idle" class="p-8 text-center">
                                    <div class="space-y-3">
                                        <div class="w-14 h-14 mx-auto bg-white rounded-2xl flex items-center justify-center shadow-sm border border-slate-200">
                                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <button type="button" onclick="openCameraModal()"
                                                class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 active:scale-95 transition-all shadow-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Take Photo
                                        </button>
                                        <p class="text-xs text-slate-500">Take a selfie for identity verification</p>
                                    </div>
                                </div>

                                {{-- Preview state: shows captured thumbnail --}}
                                <div id="photo-preview-inline" class="hidden relative">
                                    <img id="photo-preview-img" src="" alt="Selfie Preview" class="w-full h-52 object-cover">
                                    <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/60 to-transparent flex items-center justify-between">
                                        <span class="text-sm text-white font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Photo captured
                                        </span>
                                        <button type="button" onclick="retakePhoto()" 
                                                class="px-3 py-1.5 bg-white/20 backdrop-blur-sm text-white text-sm font-medium rounded-lg hover:bg-white/30 transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Retake
                                        </button>
                                    </div>
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
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Basac</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Calango</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Lotuban</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Malungay Diot</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Maluay</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Mayabon</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Nabago</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Nasig-id</option>
                                    <option value="Barangay Poblacion" {{ old('barangay') == 'Barangay Poblacion' ? 'selected' : '' }}>Barangay Najandig</option>
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
                                    <option value="5" {{ old('purok') == '5' ? 'selected' : '' }}>Purok 5</option>
                                    <option value="6" {{ old('purok') == '6' ? 'selected' : '' }}>Purok 6</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow-sm ring-1 ring-slate-200 rounded-2xl mb-8 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-green-50/80">
                        <h3 class="text-lg font-semibold text-slate-800">Service Details</h3>
                        <p class="text-sm text-slate-500 mt-1">Select your Service type</p>
                    </div>
                    <div class="p-6 md:p-8 space-y-6">
                        <div>
                            <label for="service_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Service Type <span class="text-red-500">*</span></label>
                            <select id="service_id" name="service_id" required aria-describedby="service_id-error service_id-help"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition-all focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 appearance-none @error('service_id') border-red-300 focus:border-red-500 focus:ring-red-500/10 @enderror">
                                <option value="" disabled {{ old('service_id') ? '' : 'selected' }}>Choose a Service</option>
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
                    </div>
                </div>

                {{-- Data Privacy Consent --}}
                <div class="mb-6 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                    <label class="flex items-start gap-3 cursor-pointer group">
                        <div class="flex items-center h-5 mt-0.5">
                            <input type="checkbox" name="data_privacy_consent" id="data_privacy_consent" required
                                   class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-colors cursor-pointer">
                        </div>
                        <div class="text-sm">
                            <span class="font-semibold text-slate-800 group-hover:text-emerald-700 transition-colors">I agree to the Data Privacy Policy. <span class="text-red-500">*</span></span>
                            <p class="text-slate-500 mt-1 leading-relaxed">By proceeding, I consent to the collection and processing of my personal data and photo/selfie by the Rural Health Unit (RHU) for the purpose of registration, in accordance with the Data Privacy Act of 2012.</p>
                        </div>
                    </label>
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
        {{-- RETURNING PATIENT MODAL                               --}}
        {{-- ===================================================== --}}
        <div id="returning-patient-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeReturningModal()"></div>
            <div class="relative w-full max-w-3xl max-h-[90vh] bg-slate-50 rounded-2xl shadow-2xl overflow-y-auto flex flex-col">
                {{-- Modal Header --}}
                <div class="sticky top-0 z-10 flex items-center justify-between px-6 py-4 bg-white border-b border-slate-100/80 backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Returning Patient</h3>
                    </div>
                    <button type="button" onclick="closeReturningModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                {{-- Modal Body --}}
                <div class="p-6 md:p-8">

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
                            <h3 class="text-lg font-semibold text-slate-800">Choose Service</h3>
                            <p class="text-sm text-slate-500 mt-1">Select your Service Type</p>
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
                        </div>
                    </div>

                    {{-- Data Privacy Consent --}}
                    <div class="mb-6 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="flex items-center h-5 mt-0.5">
                                <input type="checkbox" name="ret_data_privacy_consent" id="ret_data_privacy_consent" required
                                       class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 transition-colors cursor-pointer">
                            </div>
                            <div class="text-sm">
                                <span class="font-semibold text-slate-800 group-hover:text-emerald-700 transition-colors">I agree to the Data Privacy Policy. <span class="text-red-500">*</span></span>
                                <p class="text-slate-500 mt-1 leading-relaxed">By proceeding, I consent to the processing of my personal data by the Rural Health Unit (RHU) for the purpose of scheduling my medical appointment, in accordance with the Data Privacy Act of 2012.</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" id="ret-submit-btn"
                                class="w-full sm:w-auto inline-flex items-center justify-center bg-emerald-600 text-white px-8 py-3.5 rounded-xl shadow-sm hover:bg-emerald-700 hover:shadow transition-all font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 disabled:opacity-70 disabled:cursor-not-allowed min-w-[200px]">
                            <span id="ret-submit-text">Confirm </span>
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

                </div>
            </div>
        </div>{{-- end #returning-patient-modal --}}

        {{-- ===================================================== --}}
        {{-- TICKET MODAL (Shown after successful booking)         --}}
        {{-- ===================================================== --}}
        @if(session('ticket'))
        <div id="ticket-modal" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm border-0 hide-on-print" onclick="document.getElementById('ticket-modal').remove()"></div>
            <div class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col printable-ticket-container">
                {{-- Ticket Header --}}
                <div class="bg-emerald-600 px-6 py-6 text-center text-white printable-no-bg">
                    <h2 class="text-2xl font-bold tracking-tight uppercase">RHU Appointment</h2>
                    <p class="text-emerald-100 text-sm mt-1 uppercase font-semibold">Official Queue Ticket</p>
                </div>
                
                {{-- Ticket Body --}}
                <div class="p-8 text-center bg-white">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-2">Queue Number</p>
                    <h1 class="text-6xl font-extrabold text-slate-800 mb-6 font-mono tracking-tighter">{{ session('ticket')['queue_number'] }}</h1>
                    
                    <div class="space-y-4 text-left border-t-2 border-dashed border-slate-200 pt-6">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Patient Name</p>
                            <p class="text-sm font-bold text-slate-800 uppercase">{{ session('ticket')['patient_name'] }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Service</p>
                            <p class="text-sm font-bold text-slate-800 uppercase">{{ session('ticket')['service_name'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Date</p>
                                <p class="text-sm font-bold text-slate-800 uppercase">{{ \Carbon\Carbon::parse(session('ticket')['schedule'])->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">Time</p>
                                <p class="text-sm font-bold text-slate-800 uppercase">{{ date('h:i A', strtotime(session('ticket')['schedule_time'])) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions (Hidden on Print) --}}
                <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col gap-3 hide-on-print">
                    <button type="button" onclick="printTicket()"
                            class="w-full px-4 py-3 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Print Ticket
                    </button>
                    <button type="button" onclick="document.getElementById('ticket-modal').remove()"
                            class="w-full px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-200 transition-all focus:outline-none text-center">
                        Close Display
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- ═══ Camera Modal ═══ --}}
<div id="camera-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCameraModal()"></div>
    <div class="relative bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden ring-1 ring-white/10 flex flex-col max-h-[90vh]">
        
        {{-- Modal header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-white/10 z-10">
            <h3 class="text-base font-semibold text-white flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Take a Selfie
            </h3>
            <button type="button" onclick="closeCameraModal()" 
                    class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/20 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Camera viewfinder --}}
        <div class="relative flex-1 bg-black min-h-[350px]">
            <video id="camera-video" autoplay playsinline muted class="absolute inset-0 w-full h-full object-cover"></video>
            <canvas id="camera-canvas" class="hidden"></canvas>

            {{-- Face guide overlay --}}
            <div id="camera-guide" class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="w-56 h-64 border-2 border-white/50 border-dashed rounded-[3rem] shadow-[0_0_0_9999px_rgba(0,0,0,0.4)] transition-all"></div>
            </div>

            {{-- Loading overlay --}}
            <div id="camera-loading" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900 z-20">
                <div class="w-12 h-12 mb-3 bg-slate-800 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-slate-300 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-slate-400">Starting camera...</p>
            </div>

            {{-- Error overlay --}}
            <div id="camera-error" class="absolute inset-0 hidden flex-col items-center justify-center bg-slate-900 px-6 text-center z-20">
                <div class="w-14 h-14 mb-4 bg-red-500/10 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <p class="text-base font-semibold text-white mb-2">Camera Access Denied</p>
                <p class="text-sm text-slate-400">Please allow camera permissions in your browser settings to take a selfie.</p>
            </div>
            
            {{-- Helper text --}}
            <div class="absolute top-4 inset-x-0 text-center z-10">
                <span class="inline-block px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs font-medium text-white tracking-wide">
                    Position your face within the frame
                </span>
            </div>
        </div>

        {{-- Modal footer with controls --}}
        <div id="camera-controls" class="flex flex-col items-center justify-center py-6 px-4 bg-slate-900 border-t border-white/5 relative z-10">
            <button type="button" id="capture-btn" onclick="capturePhoto()"
                    class="w-[72px] h-[72px] bg-white rounded-full p-1.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                <div class="w-full h-full bg-white rounded-full border-[3px] border-slate-900"></div>
            </button>
        </div>
    </div>
</div>

<style>
    /* No @media print rules needed on this page — printing is handled via popup window */
</style>

<script>
    // ===================================================================
    // PRINT TICKET — Opens a dedicated popup window with only the ticket
    // so the browser print dialog shows exactly 1 page / 1 copy.
    // ===================================================================
    function printTicket() {
        const ticketEl = document.querySelector('.printable-ticket-container');
        if (!ticketEl) return;

        const ticketHTML = ticketEl.innerHTML;

        const printWin = window.open('', '_blank', 'width=400,height=600,toolbar=0,menubar=0,scrollbars=0');
        printWin.document.write(`
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Queue Ticket</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page {
            size: 80mm auto;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            width: 80mm;
            margin: 0 auto;
            background: #fff;
        }
        /* Header */
        .ticket-header {
            background: #059669;
            padding: 16px;
            text-align: center;
            color: #fff;
            border-bottom: 2px dashed #000;
        }
        .ticket-header h2 {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fff;
        }
        .ticket-header p {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: #d1fae5;
            margin-top: 2px;
        }
        /* Body */
        .ticket-body {
            padding: 20px 16px;
            text-align: center;
            background: #fff;
        }
        .queue-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .queue-number {
            font-size: 56px;
            font-weight: 900;
            color: #1e293b;
            font-family: monospace;
            letter-spacing: -2px;
            margin-bottom: 16px;
            line-height: 1;
        }
        .ticket-details {
            text-align: left;
            border-top: 2px dashed #000;
            padding-top: 14px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 1px;
        }
        .detail-value {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        @media print {
            html, body { width: 80mm; }
        }
    </style>
</head>
<body>
    <div class="ticket-header">
        <h2>RHU Appointment</h2>
        <p>Official Queue Ticket</p>
    </div>
    <div class="ticket-body">
        <p class="queue-label">Queue Number</p>
        <p class="queue-number">{{ session('ticket')['queue_number'] ?? '' }}</p>
        <div class="ticket-details">
            <div class="detail-row">
                <p class="detail-label">Patient Name</p>
                <p class="detail-value">{{ session('ticket')['patient_name'] ?? '' }}</p>
            </div>
            <div class="detail-row">
                <p class="detail-label">Service</p>
                <p class="detail-value">{{ session('ticket')['service_name'] ?? '' }}</p>
            </div>
            <div class="detail-grid">
                <div class="detail-row">
                    <p class="detail-label">Date</p>
                    <p class="detail-value">{{ isset(session('ticket')['schedule']) ? \Carbon\Carbon::parse(session('ticket')['schedule'])->format('M d, Y') : '' }}</p>
                </div>
                <div class="detail-row">
                    <p class="detail-label">Time</p>
                    <p class="detail-value">{{ isset(session('ticket')['schedule_time']) ? date('h:i A', strtotime(session('ticket')['schedule_time'])) : '' }}</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
        `);
        printWin.document.close();
        printWin.focus();
        // Small delay so the browser can render before printing
        setTimeout(() => {
            printWin.print();
            printWin.close();
        }, 300);
    }

    // ===================================================================
    // RETURNING PATIENT MODAL
    // ===================================================================
    const returningModal = document.getElementById('returning-patient-modal');

    function openReturningModal() {
        returningModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // prevent background scrolling
    }

    function closeReturningModal() {
        returningModal.classList.add('hidden');
        document.body.style.overflow = '';
        resetLookup();
    }

    // Auto-open Returning Patient modal if the page loaded with errors
    // from the returning-patient form (identified by having a patient_id in old input)
    @if($errors->any() && old('patient_id'))
        openReturningModal();
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

    // === Valid ID Camera Capture ===
    const cameraVideo     = document.getElementById('camera-video');
    const cameraCanvas    = document.getElementById('camera-canvas');
    const cameraLoading   = document.getElementById('camera-loading');
    const cameraError     = document.getElementById('camera-error');
    const cameraControls  = document.getElementById('camera-controls');
    const cameraModal     = document.getElementById('camera-modal');
    
    const cameraIdle      = document.getElementById('camera-idle');
    const photoPreviewInl = document.getElementById('photo-preview-inline');
    const photoPreviewImg = document.getElementById('photo-preview-img');
    const validIdInput    = document.getElementById('valid_id');
    const idCameraArea    = document.getElementById('id-camera-area');
    let cameraStream      = null;

    // Open camera modal when user clicks "Take Photo"
    function openCameraModal() {
        cameraModal.classList.remove('hidden');
        startCamera();
    }

    // Close camera modal
    function closeCameraModal() {
        stopCamera();
        cameraModal.classList.add('hidden');
        // Reset loading/error states for next attempt
        cameraLoading.classList.remove('hidden');
        cameraError.classList.add('hidden');
        cameraError.classList.remove('flex');
    }

    async function startCamera() {
        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
            });
            cameraVideo.srcObject = cameraStream;
            cameraVideo.onloadedmetadata = () => {
                cameraLoading.classList.add('hidden');
                cameraControls.style.display = '';
            };
        } catch (err) {
            console.warn('Camera access denied or unavailable:', err);
            cameraLoading.classList.add('hidden');
            cameraControls.style.display = 'none';
            cameraError.classList.remove('hidden');
            cameraError.classList.add('flex');
        }
    }

    function stopCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
    }

    function capturePhoto() {
        if (!cameraStream) return;

        // Set canvas to video dimensions for full-quality capture
        cameraCanvas.width  = cameraVideo.videoWidth;
        cameraCanvas.height = cameraVideo.videoHeight;
        const ctx = cameraCanvas.getContext('2d');
        // Apply mirroring since it's a front-facing camera
        ctx.translate(cameraCanvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(cameraVideo, 0, 0);

        // Show preview
        const dataUrl = cameraCanvas.toDataURL('image/jpeg', 0.85);
        photoPreviewImg.src = dataUrl;
        
        cameraIdle.classList.add('hidden');
        photoPreviewInl.classList.remove('hidden');

        // Convert to file and attach to hidden input
        cameraCanvas.toBlob(function(blob) {
            const file = new File([blob], 'valid_id_photo.jpg', { type: 'image/jpeg' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            validIdInput.files = dataTransfer.files;
        }, 'image/jpeg', 0.85);

        // Update border to success state
        idCameraArea.classList.remove('border-slate-300', 'border-dashed', 'bg-slate-50', 'bg-slate-900');
        idCameraArea.classList.add('border-emerald-400', 'border-solid');

        closeCameraModal();
    }

    function retakePhoto() {
        photoPreviewInl.classList.add('hidden');
        photoPreviewImg.src = '';
        validIdInput.value = '';

        // Reset border back to idle
        idCameraArea.classList.remove('border-emerald-400', 'border-solid');
        idCameraArea.classList.add('border-slate-300', 'border-dashed', 'bg-slate-50');

        cameraIdle.classList.remove('hidden');
        
        // Directly open the modal again to retake
        openCameraModal();
    }

    // Handle fallback file upload — show preview same as camera capture
    validIdInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) { alert('Please upload a JPEG or PNG image.'); this.value = ''; return; }
        if (file.size > 2 * 1024 * 1024) { alert('File size must not exceed 2MB.'); this.value = ''; return; }

        const reader = new FileReader();
        reader.onload = function(e) {
            photoPreviewImg.src = e.target.result;
            cameraIdle.classList.add('hidden');
            photoPreviewInl.classList.remove('hidden');
            idCameraArea.classList.remove('border-slate-300', 'border-dashed', 'bg-slate-50', 'bg-slate-900');
            idCameraArea.classList.add('border-emerald-400', 'border-solid');
            stopCamera();
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection