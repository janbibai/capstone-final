@extends('welcome')

@section('title', 'Book Appointment')

@section('content')


<div class="min-h-screen bg-green-50 py-12 px-4">
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 border border-green-100">

        <!-- Title -->
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-bold text-green-700">
                Book an Appointment
            </h2>
            <p class="text-gray-500 mt-2">
                Please fill in your details to register and schedule your visit.
            </p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-6 rounded-lg bg-green-50 text-green-800 border-2 border-green-300 shadow-sm" role="alert">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lg mb-1">Appointment Booked Successfully!</h3>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="this.parentElement.parentElement.style.display='none'" 
                            class="text-green-700 hover:text-green-900 ml-4" 
                            aria-label="Dismiss success message">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-700 border border-red-300" role="alert">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold mb-2">Please fill up the following:</h3>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.style.display='none'" 
                            class="text-red-700 hover:text-red-900" aria-label="Dismiss error message">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('appointment.storePatient') }}" id="appointment-form" class="space-y-6" enctype="multipart/form-data" novalidate>
            @csrf

            <!-- Personal Information Section -->
            <fieldset class="space-y-6">
                <legend class="text-xl font-semibold text-green-700 mb-4 pb-2 border-b border-green-200 w-full">
                    Personal Information
                </legend>

                <!-- Name Row -->
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name *
                        </label>
                        <input type="text" 
                               id="first_name"
                               name="first_name" 
                               value="{{ old('first_name') }}"
                               required
                               maxlength="30"
                               aria-describedby="first_name-error"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('first_name') border-red-500 @enderror"
                               placeholder="First Name">
                        @error('first_name')
                            <p id="first_name-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Middle Name
                        </label>
                        <input type="text" 
                               id="middle_name"
                               name="middle_name" 
                               value="{{ old('middle_name') }}"
                               maxlength="30"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                               placeholder="Middle Name">
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name *
                        </label>
                        <input type="text" 
                               id="last_name"
                               name="last_name" 
                               value="{{ old('last_name') }}"
                               required
                               maxlength="30"
                               aria-describedby="last_name-error"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('last_name') border-red-500 @enderror"
                               placeholder="Last Name">
                        @error('last_name')
                            <p id="last_name-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            <!-- DOB + Gender -->
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">
                        Date of Birth *
                    </label>
                    <input type="date" 
                           id="date_of_birth"
                           name="date_of_birth" 
                           value="{{ old('date_of_birth') }}"
                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                           required
                           aria-describedby="date_of_birth-error date_of_birth-help"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('date_of_birth') border-red-500 @enderror">
                    <p id="date_of_birth-help" class="text-xs text-gray-500 mt-1">Must be a past date</p>
                    @error('date_of_birth')
                        <p id="date_of_birth-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                        Gender *
                    </label>
                    <select id="gender"
                            name="gender"
                            required
                            aria-describedby="gender-error"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('gender') border-red-500 @enderror">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <p id="gender-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Valid ID Upload -->
            <div class="mt-4">
                <label for="valid_id" class="block text-sm font-medium text-gray-700 mb-1">
                    Valid Government ID *
                </label>
                <div id="id-upload-area" 
                     class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition cursor-pointer @error('valid_id') border-red-500 @enderror"
                     onclick="document.getElementById('valid_id').click()">
                    
                    <!-- Upload prompt (shown when no file selected) -->
                    <div id="upload-prompt">
                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">
                            <span class="font-semibold text-green-600">Click to upload</span> your valid ID
                        </p>
                        <p class="mt-1 text-xs text-gray-500">JPEG or PNG, max 2MB</p>
                    </div>

                    <!-- Image preview (hidden initially) -->
                    <div id="id-preview-container" class="hidden">
                        <img id="id-preview" src="" alt="ID Preview" class="mx-auto max-h-48 rounded-lg shadow-sm">
                        <div id="id-file-info" class="mt-2 text-sm text-gray-600"></div>
                        <button type="button" id="remove-id-btn" 
                                class="mt-2 text-xs text-red-500 hover:text-red-700 font-medium"
                                onclick="event.stopPropagation(); removeIdFile()">
                            ✕ Remove
                        </button>
                    </div>
                </div>

                <input type="file" 
                       id="valid_id" 
                       name="valid_id" 
                       accept="image/jpeg,image/png"
                       class="hidden"
                       aria-describedby="valid_id-help valid_id-error">
                <p id="valid_id-help" class="text-xs text-gray-500 mt-1">Upload a clear photo of your government-issued ID (e.g. National ID, Driver's License)</p>
                @error('valid_id')
                    <p id="valid_id-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                @enderror
            </div>

            </fieldset>

            <!-- Contact Details Section -->
            <fieldset class="space-y-6 border-t border-green-200 pt-6">
                <legend class="text-xl font-semibold text-green-700 mb-4 pb-2 border-b border-green-200 w-full">
                    Contact Details
                </legend>

                <!-- Contact Info -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone
                        </label>
                        <input type="tel" 
                               id="phone"
                               name="phone" 
                               value="{{ old('phone') }}"
                               pattern="09[0-9]{9}|[0-9]{11}"
                               maxlength="13"
                               aria-describedby="phone-help phone-error"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('phone') border-red-500 @enderror"
                               placeholder="09XXXXXXXXX">
                        <p id="phone-help" class="text-xs text-gray-500 mt-1">Format: 09XXXXXXXXX (11 digits)</p>
                        @error('phone')
                            <p id="phone-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" 
                               id="email"
                               name="email" 
                               value="{{ old('email') }}"
                               maxlength="30"
                               aria-describedby="email-error"
                               class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('email') border-red-500 @enderror"
                               placeholder="example@email.com">
                        @error('email')
                            <p id="email-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                        Address
                    </label>
                    <input type="text" 
                           id="address"
                           name="address" 
                           value="{{ old('address') }}"
                           maxlength="50"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none"
                           placeholder="Full Address">
                </div>
            </fieldset>
            <!-- Appointment Details Section -->
            <fieldset class="space-y-6 border-t border-green-200 pt-6">
                <legend class="text-xl font-semibold text-green-700 mb-4 pb-2 border-b border-green-200 w-full">
                    Appointment Details
                </legend>

                <!-- Service Selection -->
                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Select Service *
                    </label>
                    <select id="service_id"
                            name="service_id"
                            required
                            aria-describedby="service_id-error service_id-help"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('service_id') border-red-500 @enderror">
                        <option value="">-- Choose a Service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ ucfirst($service->name) }} — {{ $service->description }} ({{ $service->estimated_time }} min)
                            </option>
                        @endforeach
                    </select>
                    <p id="service_id-help" class="text-xs text-gray-500 mt-1">Select the service you need</p>
                    @error('service_id')
                        <p id="service_id-error" class="text-red-500 text-sm mt-2" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Schedule Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        Appointment Date & Time
                    </h3>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="schedule" class="block text-sm font-medium text-gray-700 mb-1">
                                Date *
                            </label>
                            <input type="date" 
                                   id="schedule"
                                   name="schedule"
                                   value="{{ old('schedule') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   aria-describedby="schedule-error schedule-help"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('schedule') border-red-500 @enderror">
                            <p id="schedule-help" class="text-xs text-gray-500 mt-1">Select today or a future date</p>
                            @error('schedule')
                                <p id="schedule-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="schedule_time" class="block text-sm font-medium text-gray-700 mb-1">
                                Time *
                            </label>
                            <select id="schedule_time"
                                   name="schedule_time"
                                   required
                                   aria-describedby="schedule_time-error schedule_time-help"
                                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 focus:outline-none @error('schedule_time') border-red-500 @enderror">
                                <option value="">-- Choose a Time --</option>
                                @php
                                    $start = strtotime('08:00');
                                    $end = strtotime('17:00');
                                    $oldTime = old('schedule_time');
                                @endphp
                                @for ($i = $start; $i <= $end; $i += 900)
                                    @php
                                        // Skip lunch break: 12:00 PM to 12:45 PM
                                        if ($i >= strtotime('12:00') && $i < strtotime('13:00')) {
                                            continue;
                                        }

                                        $timeValue = date('H:i', $i);
                                        $timeLabel = date('h:i A', $i);
                                    @endphp
                                    <option value="{{ $timeValue }}" {{ $oldTime == $timeValue ? 'selected' : '' }}>
                                        {{ $timeLabel }}
                                    </option>
                                @endfor
                            </select>
                            <p id="schedule_time-help" class="text-xs text-gray-500 mt-1">Business hours: 8:00 AM - 5:00 PM</p>
                            @error('schedule_time')
                                <p id="schedule_time-error" class="text-red-500 text-sm mt-1" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </fieldset>



            <!-- Submit Button -->
            <div class="pt-4 text-center">
                <button type="submit"
                        id="submit-btn"
                        class="bg-green-600 text-white px-8 py-3 rounded-xl shadow-md hover:bg-green-700 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed min-w-[140px] min-h-[44px]">
                    <span id="submit-text">Book Appointment</span>
                    <span id="submit-spinner" class="hidden">
                        <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

<script>
    // Form submission handling
    document.getElementById('appointment-form').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        
        // Disable button and show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        submitSpinner.classList.remove('hidden');
    });

    // Real-time validation feedback
    const form = document.getElementById('appointment-form');
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        // Add error styling on blur if invalid
        input.addEventListener('blur', function() {
            if (!this.validity.valid && this.value !== '') {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });

        // Remove error styling on input
        input.addEventListener('input', function() {
            if (this.validity.valid) {
                this.classList.remove('border-red-500');
            }
        });
    });

    // Form auto-save to localStorage
    const formData = {};
    inputs.forEach(input => {
        // Restore saved values
        const savedValue = localStorage.getItem(`appointment_${input.name}`);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }

        // Save on change
        input.addEventListener('change', function() {
            localStorage.setItem(`appointment_${input.name}`, this.value);
        });
    });

    // Clear localStorage on successful submission
    form.addEventListener('submit', function() {
        inputs.forEach(input => {
            localStorage.removeItem(`appointment_${input.name}`);
        });
    });

    // Fetch and Handle Booked Times
    const scheduleDateInput = document.getElementById('schedule');
    const scheduleTimeSelect = document.getElementById('schedule_time');

    async function fetchBookedTimes(date) {
        if (!date) {
            resetTimeOptions();
            return;
        }

        try {
            const response = await fetch(`/appointments/booked-times?date=${date}`);
            const bookedTimes = await response.json();
            updateTimeOptions(bookedTimes);
        } catch (error) {
            console.error('Error fetching booked times:', error);
        }
    }

    function resetTimeOptions() {
        Array.from(scheduleTimeSelect.options).forEach(option => {
            if (option.value === '') return;
            option.disabled = false;
            option.text = option.text.replace(' (Booked)', '');
        });
    }

    function updateTimeOptions(bookedTimes) {
        resetTimeOptions();
        
        // Disable booked times
        Array.from(scheduleTimeSelect.options).forEach(option => {
            if (option.value && bookedTimes.includes(option.value)) {
                option.disabled = true;
                if (!option.text.includes('(Booked)')) {
                    option.text += ' (Booked)';
                }
                
                // If currently selected slot is now disabled, reset selection
                if (option.selected) {
                    scheduleTimeSelect.value = '';
                }
            }
        });
    }

    scheduleDateInput.addEventListener('change', function() {
        fetchBookedTimes(this.value);
    });

    // Run on init if date has a value limit from old input or local storage
    setTimeout(() => {
        if (scheduleDateInput.value) {
            fetchBookedTimes(scheduleDateInput.value);
        }
    }, 100);

    // === Valid ID Upload Preview ===
    const validIdInput = document.getElementById('valid_id');
    const uploadArea = document.getElementById('id-upload-area');
    const uploadPrompt = document.getElementById('upload-prompt');
    const previewContainer = document.getElementById('id-preview-container');
    const previewImg = document.getElementById('id-preview');
    const fileInfo = document.getElementById('id-file-info');

    validIdInput.addEventListener('change', function() {
        handleIdFile(this.files[0]);
    });

    function handleIdFile(file) {
        if (!file) return;

        // Validate file type
        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a JPEG or PNG image.');
            validIdInput.value = '';
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must not exceed 2MB.');
            validIdInput.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            uploadArea.classList.remove('border-gray-300', 'border-dashed');
            uploadArea.classList.add('border-green-400', 'border-solid');
            fileInfo.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        };
        reader.readAsDataURL(file);
    }

    function removeIdFile() {
        validIdInput.value = '';
        previewImg.src = '';
        uploadPrompt.classList.remove('hidden');
        previewContainer.classList.add('hidden');
        uploadArea.classList.add('border-gray-300', 'border-dashed');
        uploadArea.classList.remove('border-green-400', 'border-solid');
        fileInfo.textContent = '';
    }

    // Drag and drop support
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-green-400', 'bg-green-50');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-green-400', 'bg-green-50');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-green-400', 'bg-green-50');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Set the file to the input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            validIdInput.files = dataTransfer.files;
            handleIdFile(file);
        }
    });
</script>

@endsection
