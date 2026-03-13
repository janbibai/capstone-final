@extends('layouts.app')

@section('content')

    {{-- HERO (UPDATED TO MATCH IMAGE) --}}
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                {{-- LEFT --}}
                <div>
                    <span class="inline-flex items-center px-4 py-2 mb-6 text-[11px] font-bold uppercase tracking-widest text-primary bg-primary/10 rounded-full">
                        Community Health First
                    </span>

                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-[1.05]">
                        Quality Healthcare
                        <br />
                        <span class="text-primary">Made Accessible</span>
                    </h1>

                    <p class="mt-6 text-slate-600 max-w-lg leading-relaxed">
                        Book appointments online and manage your clinic visits with our Smart Queuing system.
                        Experience healthcare designed for your convenience.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('appointment.create') }}"
                           class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 text-white px-7 py-3.5 rounded-lg text-sm font-semibold shadow-sm transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Book Your Appointment
                        </a>

                        <a href="#services"
                           class="inline-flex items-center justify-center gap-2 bg-white border border-black/10 hover:bg-black/5 px-7 py-3.5 rounded-lg text-sm font-semibold text-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Learn More
                        </a>
                    </div>
                </div>

                {{-- RIGHT (TEAL MOCKUP CARD LIKE IMAGE) --}}
                <div class="flex justify-center lg:justify-end">
                    <div class="w-full max-w-lg bg-teal-200 rounded-2xl shadow-xl p-10 flex items-center justify-center min-h-[320px]">
                        <div class="w-48 h-64 bg-white/25 rounded-2xl border-4 border-white flex flex-col items-center justify-center">
                            <div class="w-24 h-24 rounded-full bg-white/25 flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>

                            {{-- small "carousel dots" like the mockup --}}
                            <div class="flex gap-2">
                                <span class="w-2 h-2 rounded-full bg-white/70"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SERVICES (UNCHANGED) --}}
    <section class="py-20 bg-white" id="services">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Our Primary Services</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">We offer comprehensive primary care services to ensure the health and well-being of every family member in our community.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-2xl border border-primary/10 bg-background-light hover:shadow-xl transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">General Check-up</h3>
                    <p class="text-slate-600 leading-relaxed">Comprehensive physical examinations and health screenings to keep you and your family in top health year-round.</p>
                </div>
                <div class="group p-8 rounded-2xl border border-primary/10 bg-background-light hover:shadow-xl transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C9.243 2 7 4.243 7 7c0 2.104 1.3 3.898 3.138 4.64C9.468 12.804 9 14.343 9 16c0 2.757 1.343 5 3 5s3-2.243 3-5c0-1.657-.468-3.196-1.138-4.36C15.7 10.898 17 9.104 17 7c0-2.757-2.243-5-5-5z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Dental Services</h3>
                    <p class="text-slate-600 leading-relaxed">Professional oral health care including routine cleanings, fillings, and dental treatments for all ages.</p>
                </div>
                <div class="group p-8 rounded-2xl border border-primary/10 bg-background-light hover:shadow-xl transition-all">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Maternal Care</h3>
                    <p class="text-slate-600 leading-relaxed">Specialized prenatal and postnatal care for expectant mothers and newborns ensuring a healthy start for every child.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SMART QUEUE (UNCHANGED) --}}
    <section class="py-20 bg-primary/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Smart Queuing System</h2>
                <p class="text-slate-600">Save time and skip the long lines with our digital queue management system.</p>
            </div>
            <div class="relative">
                <div class="hidden md:block absolute top-1/2 left-0 w-full h-0.5 bg-primary/20 -translate-y-1/2 -z-10"></div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-primary flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4"/></svg>
                        </div>
                        <h4 class="text-lg font-bold mb-2">1. Book Online</h4>
                        <p class="text-sm text-slate-600">Select your service and preferred time slot through our website.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-primary flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M3 11h2V9H3v2zm0-4h2V3H3v4zm4 4h2V9H7v2zm0-4h2V3H7v4zM3 15h2v-2H3v2zm8-4h2V9h-2v2zm-4 4h2v-2H7v2zm4 0h2v-2h-2v2zm0-8h2V3h-2v4zm4 4h2V9h-2v2zm0-4h2V3h-2v4zm4 4h2V9h-2v2zm0-4h2V3h-2v4zm0 8h2v-2h-2v2zm-4 0h2v-2h-2v2zm-8 4h2v-2H7v2zm4 0h2v-2h-2v2zm4 0h2v-2h-2v2zm0-4h2v-2h-2v2zm4 0h2v-2h-2v2zm0 4h2v-2h-2v2zM3 19h2v-2H3v2zm0 2h2v-2H3v2zm4 0h2v-2H7v2z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold mb-2">2. Get QR Ticket</h4>
                        <p class="text-sm text-slate-600">Receive a digital ticket with a unique QR code for your appointment.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="w-20 h-20 bg-white rounded-full border-4 border-primary flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold mb-2">3. Arrive & Check-in</h4>
                        <p class="text-sm text-slate-600">Scan your QR code at the entrance to confirm your arrival instantly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DOCTORS (UNCHANGED) --}}
    <section class="py-20" id="doctors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-slate-900 mb-4">Meet Our Medical Team</h2>
                <p class="text-slate-600">Dedicated professionals committed to providing the best care for our community.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all border border-primary/5">
                    <div class="w-full h-72 overflow-hidden">
                        <img src="{{ asset('images/jenn.jpg') }}" alt="Dr. Elena Cruz" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900">Dr. Elena Cruz</h3>
                        <p class="text-primary font-semibold text-sm mb-4">General Physician</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">Expert in preventive medicine with over 12 years of experience in rural community health.</p>
                        <button class="w-full py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">View Profile</button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all border border-primary/5">
                    <div class="w-full h-72 overflow-hidden">
                        <img src="{{ asset('images/jenn.jpg') }}" alt="Dr. Marcus Reyes" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900">Dr. Marcus Reyes</h3>
                        <p class="text-primary font-semibold text-sm mb-4">Pediatrician</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">Specializing in child development and pediatric infectious diseases for over 8 years.</p>
                        <button class="w-full py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">View Profile</button>
                    </div>
                </div>
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all border border-primary/5">
                    <div class="w-full h-72 overflow-hidden">
                        <img src="{{ asset('images/jenn.jpg') }}" alt="Dr. Sarah Lim" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-900">Dr. Sarah Lim</h3>
                        <p class="text-primary font-semibold text-sm mb-4">Dentist</p>
                        <p class="text-slate-600 text-sm leading-relaxed mb-6">A specialist in restorative dentistry and oral hygiene education for families.</p>
                        <button class="w-full py-2 bg-primary/10 text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">View Profile</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT (UNCHANGED) --}}
    <section class="py-20 bg-white" id="contact">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-extrabold mb-8">Visit Our Center</h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="bg-primary/10 p-3 rounded-lg text-primary shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Our Location</h4>
                                <p class="text-slate-600">Acupanda St., Poblacion, Zamboanguita, Negros Oriental, 6218</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-primary/10 p-3 rounded-lg text-primary shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Working Hours</h4>
                                <p class="text-slate-600">Monday - Friday: 8 AM - 5 PM</p>
                                <p class="text-slate-600">Saturday: 8 AM - 12 PM</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="bg-primary/10 p-3 rounded-lg text-primary shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Contact Details</h4>
                                <p class="text-slate-600">Appointments: (555) 123-4567</p>
                                <p class="text-primary font-bold">Emergency: 911</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden h-[400px] shadow-lg border border-primary/10">
                    <iframe class="w-full h-full" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&height=400&hl=en&q=Zamboangita%20Rural%20Health%20Unit&t=&z=14&ie=UTF8&iwloc=B&output=embed"></iframe>
                </div>
            </div>
        </div>
    </section>

@endsection