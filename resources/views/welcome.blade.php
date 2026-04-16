@extends('layouts.app')

@section('content')

    {{-- HERO SECTION --}}
    <section class="relative py-20 sm:py-28 overflow-hidden font-sans">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/pic.jpg') }}" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/85 via-slate-900/70 to-slate-900/50"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT CONTENT --}}
                <div class="max-w-2xl">
                    <span class="inline-flex items-center gap-2 px-4 py-2 mb-8 text-xs font-bold uppercase tracking-widest text-emerald-300 bg-emerald-500/20 rounded-full border border-emerald-400/30 backdrop-blur-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        Community Health First
                    </span>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight">
                        Quality Healthcare
                        <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">
                            Made Accessible
                        </span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-relaxed max-w-lg">
                        Book appointments online and manage your clinic visits with our Smart Queuing system. Experience healthcare designed for your convenience.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('appointment.create') }}"
                           class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-4 rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/30 hover:shadow-emerald-600/40 hover:-translate-y-0.5 transition-all duration-300">
                            <span class="material-symbols-outlined text-[20px]">calendar_today</span>
                            Patient Registration
                        </a>

                        <a href="{{ route('appointment.queueStatus') }}"
                           class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm border-2 border-white/20 hover:border-emerald-400/50 hover:bg-emerald-500/20 px-8 py-4 rounded-xl text-sm font-bold text-white hover:text-emerald-300 transition-all duration-300">
                            <span class="material-symbols-outlined text-[20px]">queue</span>
                            Check Queue Status
                        </a>
                    </div>
                </div>

                {{-- RIGHT VISUAL --}}
                {{-- <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-lg bg-white/10 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-black/20 p-10 flex items-center justify-center min-h-[400px] overflow-hidden transform hover:-translate-y-2 transition-transform duration-500 border border-white/20">
                        <div class="relative z-10 w-56 h-72 bg-white/10 backdrop-blur-md rounded-3xl border border-white/30 flex flex-col items-center justify-center shadow-2xl">
                            <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center mb-8 shadow-inner border border-white/20">
                                <span class="material-symbols-outlined text-white text-5xl drop-shadow-md">medical_services</span>
                            </div> --}}

                            {{-- Carousel dots --}}
                            {{-- <div class="flex gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-white shadow-sm"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                                <span class="w-2 h-2 rounded-full bg-white/40"></span>
                            </div>
                        </div>
                        
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-800/20 rounded-full blur-2xl"></div>
                    </div>
                </div> --}}

            </div>
        </div>
    </section>

    {{-- SERVICES SECTION --}}
    <section class="py-24 bg-white relative" id="services">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Our Primary Services</h2>
                <p class="text-lg text-slate-500">We offer comprehensive primary care services to ensure the health and well-being of every family member in our community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">stethoscope</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">General Check-up</h3>
                    <p class="text-slate-600 leading-relaxed">Comprehensive physical examinations and health screenings to keep you and your family in top health year-round.</p>
                </div>

                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">dentistry</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Dental Services</h3>
                    <p class="text-slate-600 leading-relaxed">Professional oral health care including routine cleanings, fillings, and dental treatments for all ages.</p>
                </div>

                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">child_care</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Maternal Care</h3>
                    <p class="text-slate-600 leading-relaxed">Specialized prenatal and postnatal care for expectant mothers and newborns ensuring a healthy start for every child.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SMART QUEUE SECTION --}}
    <section class="py-24 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Smart Queuing System</h2>
                <p class="text-lg text-slate-500">Save time and skip the long lines with our digital queue management system.</p>
            </div>
            
            <div class="relative">
                <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-gradient-to-r from-emerald-200 via-teal-300 to-emerald-200 -z-10"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-white rounded-full border-4 border-emerald-100 flex items-center justify-center mb-6 shadow-xl shadow-slate-200/50 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-emerald-600 text-4xl">event_available</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">1. Book Online</h4>
                        <p class="text-slate-600 px-4">Select your service and preferred time slot through our secure website.</p>
                    </div>
                    
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-white rounded-full border-4 border-emerald-100 flex items-center justify-center mb-6 shadow-xl shadow-slate-200/50 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-emerald-600 text-4xl">qr_code_2</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">2. Get Number</h4>
                        <p class="text-slate-600 px-4">Instantly receive a digital queue number for your appointment.</p>
                    </div>
                    
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-emerald-600 rounded-full border-4 border-emerald-200 flex items-center justify-center mb-6 shadow-xl shadow-emerald-600/30 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-white text-4xl">check_circle</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">3. Check-in</h4>
                        <p class="text-slate-600 px-4">Show your digital queue number or ID to the staff upon arrival.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DOCTORS SECTION --}}
    <section class="py-24 bg-white" id="doctors">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">Meet Our Medical Team</h2>
                <p class="text-lg text-slate-500">Dedicated professionals committed to providing the best care for our community.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
                <div class="group bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-300/60 transition-all duration-300 border border-slate-100">
                    <div class="overflow-hidden h-72">
                        <img alt="Dr. Louise Pinili Cas" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhL6n7WWraGI4o5gtPpWTDy0_3ukcGV5I2igvT39RoqM7R_t-sHa83zOpPZbOrBLDatzxcjSzyG_lN1uuKaA5LMoGaeRE3LuU5and-n0Wxnc6dhvgb897ohWtUrA0190tjayLJdzfpp3cmNAlUT467zjQYN6JWGXl1OXZH4_48mhg9q641PpbOdUNd9vurAZN6eJKYZseQQc4Etk1xYuwTZeIges_tfNtLE5VpOfWJ4Fj-yjvPl4wuWr5m6m4aZo2ehuVSlY59buk"/>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-1">Dr. Louise Pinili Cas</h3>
                        <p class="text-emerald-600 font-semibold text-sm mb-4 uppercase tracking-wider">General Physician</p>
                        <p class="text-slate-600 leading-relaxed mb-8">Expert in preventive medicine with over 12 years of experience in rural community health.</p>
                    </div>
                </div>
                
                <div class="group bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-300/60 transition-all duration-300 border border-slate-100">
                    <div class="overflow-hidden h-72">
                        <img alt="Dr. Angelo Electona" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwTDCiQudy5dNxNrsc7hVM9UBrf63-V8B0IKSsnxELSqKC_zdG7P1Gw1kNYjdMGYiqHE82YdmKX5hEvPLKrpvC23D5nSbrG_cdqhQBMXd3U0CKyGBlzl157VYg9KkikxxsgiiG5t2hArLXzX2vG7RcTfX-Q22PEbFGpEa5mya_gitI3rKgMMILxVdOfLJ0reAkl9L5P3kTb2RNVP9B6KNGhItoY4p-T1vATZVpumR-lRJmWdwQjbtlGKsLhd7ai5MlAOdF2i3flQg"/>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-1">Dr. Angelo Electona</h3>
                        <p class="text-emerald-600 font-semibold text-sm mb-4 uppercase tracking-wider">General Physician</p>
                        <p class="text-slate-600 leading-relaxed mb-8">Specializing in child development and pediatric infectious diseases for over 8 years.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTACT SECTION --}}
    <section class="py-24 bg-slate-50 border-t border-slate-100" id="contact">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2">
                    <div class="p-10 lg:p-16">
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8 tracking-tight">Visit Our Center</h2>
                        
                        <div class="space-y-8">
                            <div class="flex items-start gap-5">
                                <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-600 shrink-0">
                                    <span class="material-symbols-outlined text-2xl">location_on</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-lg mb-1">Our Location</h4>
                                    <p class="text-slate-600 leading-relaxed">Acupanda St., Poblacion, Zamboanguita,<br> Negros Oriental, 6218</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-5">
                                <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-600 shrink-0">
                                    <span class="material-symbols-outlined text-2xl">schedule</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-lg mb-1">Working Hours</h4>
                                    <p class="text-slate-600">Monday - Friday: 8 AM - 5 PM</p>
                                    <p class="text-slate-600">Saturday: 8 AM - 12 PM</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-5">
                                <div class="bg-emerald-50 p-4 rounded-2xl text-emerald-600 shrink-0">
                                    <span class="material-symbols-outlined text-2xl">phone_in_talk</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-lg mb-1">Contact Details</h4>
                                    <p class="text-slate-600">Appointments: <a href="tel:5551234567" class="hover:text-emerald-600 transition-colors">(555) 123-4567</a></p>
                                    <p class="text-red-600 font-bold mt-1">Emergency: 911</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative h-80 lg:h-auto bg-slate-200">
                        <iframe class="absolute inset-0 w-full h-full" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&height=400&hl=en&q=Zamboangita%20Rural%20Health%20Unit&t=&z=14&ie=UTF8&iwloc=B&output=embed"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection