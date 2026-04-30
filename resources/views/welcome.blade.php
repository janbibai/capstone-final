@extends('layouts.app')

@section('content')

    {{-- HERO SECTION --}}
    <section class="relative py-20 sm:py-28 overflow-hidden">
        {{-- Background Image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('images/pic.jpg') }}" alt="" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/85 via-slate-900/70 to-slate-900/50"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                {{-- LEFT CONTENT --}}
                <div class="max-w-2xl text-center lg:text-center flex flex-col items-center lg:items-center">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] tracking-tight font-montserrat">
                        Quality Healthcare
                        <br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-400">
                            Made Accessible
                        </span>
                    </h1>

                    <p class="mt-6 text-lg text-slate-300 leading-relaxed max-w-lg mx-auto">
                        Manage your clinic visits with our Smart Queuing system. Experience healthcare designed for your convenience.
                    </p>
                </div>

                {{-- RIGHT VISUAL / ACTIONS --}}
                <div class="relative flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md bg-white/10 backdrop-blur-xl rounded-[15px] shadow-2xl shadow-emerald-900/20 p-8 lg:p-10 flex flex-col items-center border border-white/20">
                        
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-extrabold text-white mb-3 font-montserrat">Skip the Waiting Room</h3>
                            <p class="text-sm text-emerald-50/90 leading-relaxed">
                                Take control of your health journey. Register ahead of time or track your live queue status effortlessly.
                            </p>
                        </div>
                        
                        <div class="w-full flex flex-col gap-4">
                            <a href="{{ route('appointment.create') }}"
                               class="group relative overflow-hidden flex items-center gap-5 bg-emerald-600/95 hover:bg-emerald-500 text-white p-5 rounded-2xl shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/40 border border-emerald-400/30 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02]">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="bg-white/20 p-3.5 rounded-[1rem] flex-shrink-0 shadow-inner group-hover:scale-110 transition-transform duration-300">
                                    <span class="material-symbols-outlined text-3xl drop-shadow-sm text-white">how_to_reg</span>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-lg font-bold tracking-wide">Register Now</span>
                                    <span class="text-xs text-emerald-100 font-medium">Register in minutes</span>
                                </div>
                                <span class="material-symbols-outlined absolute right-6 text-white/50 group-hover:text-white transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-1">arrow_forward</span>
                            </a>

                            <a href="{{ route('appointment.queueStatus') }}"
                               class="group relative overflow-hidden flex items-center gap-5 bg-slate-800/90 hover:bg-slate-700 text-white p-5 rounded-2xl shadow-lg shadow-slate-900/40 hover:shadow-slate-800/50 border border-slate-600/50 transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02]">
                                <div class="absolute inset-0 bg-gradient-to-r from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <div class="bg-emerald-500/20 border border-emerald-500/30 p-3.5 rounded-[1rem] flex-shrink-0 group-hover:bg-emerald-500/30 group-hover:scale-110 transition-all duration-300">
                                    <span class="material-symbols-outlined text-3xl text-emerald-400 drop-shadow-sm">pending_actions</span>
                                </div>
                                <div class="flex flex-col text-left">
                                    <span class="text-lg font-bold tracking-wide">Check Queue</span>
                                    <span class="text-xs text-slate-300 font-medium">View real-time waiting position</span>
                                </div>
                                <span class="material-symbols-outlined absolute right-6 text-emerald-400/50 group-hover:text-emerald-400 transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:translate-x-1">arrow_forward</span>
                            </a>
                        </div>
                        
                        <div class="absolute -top-12 -right-12 w-40 h-40 bg-emerald-500/20 rounded-full blur-3xl -z-10 pointer-events-none"></div>
                        <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-teal-800/30 rounded-full blur-3xl -z-10 pointer-events-none"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SERVICES SECTION --}}
    <section class="py-24 bg-white relative" id="services">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight font-montserrat">Our Primary Services</h2>
                <p class="text-lg text-slate-500">We offer comprehensive primary care services to ensure the health and well-being of every family member in our community.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">stethoscope</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">General Check-up</h3>
                    <p class="text-slate-600 leading-relaxed">Comprehensive physical examinations and health screenings to keep you and your family in top health year-round.</p>
                </div>

                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">dentistry</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">Dental Services</h3>
                    <p class="text-slate-600 leading-relaxed">Professional oral health care including routine cleanings, fillings, and dental treatments for all ages.</p>
                </div>

                <div class="group relative p-8 bg-white rounded-3xl shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300 border border-slate-100 overflow-hidden transform hover:-translate-y-1">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mb-6 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <span class="material-symbols-outlined text-3xl">child_care</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">Maternal Care</h3>
                    <p class="text-slate-600 leading-relaxed">Specialized prenatal and postnatal care for expectant mothers and newborns ensuring a healthy start for every child.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SMART QUEUE SECTION --}}
    <section class="py-24 bg-slate-50 border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight font-montserrat">Smart Queuing System</h2>
                <p class="text-lg text-slate-500">Save time and skip the long lines with our digital queue management system.</p>
            </div>
            
            <div class="relative">
                <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-gradient-to-r from-emerald-200 via-teal-300 to-emerald-200 -z-10"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-white rounded-full border-4 border-emerald-100 flex items-center justify-center mb-6 shadow-xl shadow-slate-200/50 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-emerald-600 text-4xl">event_available</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">1. Register on site</h4>
                        <p class="text-slate-600 px-4">Register on site and get your queue number.</p>
                    </div>
                    
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-white rounded-full border-4 border-emerald-100 flex items-center justify-center mb-6 shadow-xl shadow-slate-200/50 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-emerald-600 text-4xl">qr_code_2</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">2. Get Number</h4>
                        <p class="text-slate-600 px-4">Instantly receive a queue number.</p>
                    </div>
                    
                    <div class="flex flex-col items-center text-center relative group">
                        <div class="w-24 h-24 bg-emerald-600 rounded-full border-4 border-emerald-200 flex items-center justify-center mb-6 shadow-xl shadow-emerald-600/30 group-hover:border-emerald-400 group-hover:scale-110 transition-all duration-300">
                            <span class="material-symbols-outlined text-white text-4xl">check_circle</span>
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3 font-montserrat">3. Check-in</h4>
                        <p class="text-slate-600 px-4">Show your queue number or ID to the staff upon arrival.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DOCTORS SECTION --}}
    <section class="py-24 bg-white" id="doctors">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 max-w-2xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight font-montserrat">Meet Our Medical Team</h2>
                <p class="text-lg text-slate-500">Dedicated professionals committed to providing the best care for our community.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
                <div class="group bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-300/60 transition-all duration-300 border border-slate-100">
                    <div class="overflow-hidden h-72">
                        <img alt="Dr. Louise Pinili Cas" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDhL6n7WWraGI4o5gtPpWTDy0_3ukcGV5I2igvT39RoqM7R_t-sHa83zOpPZbOrBLDatzxcjSzyG_lN1uuKaA5LMoGaeRE3LuU5and-n0Wxnc6dhvgb897ohWtUrA0190tjayLJdzfpp3cmNAlUT467zjQYN6JWGXl1OXZH4_48mhg9q641PpbOdUNd9vurAZN6eJKYZseQQc4Etk1xYuwTZeIges_tfNtLE5VpOfWJ4Fj-yjvPl4wuWr5m6m4aZo2ehuVSlY59buk"/>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-1 font-montserrat">Dr. Louise Pinili Cas</h3>
                        <p class="text-emerald-600 font-semibold text-sm mb-4 uppercase tracking-wider">General Physician</p>
                        <p class="text-slate-600 leading-relaxed mb-8">Expert in preventive medicine with over 12 years of experience in rural community health.</p>
                    </div>
                </div>
                
                <div class="group bg-white rounded-3xl overflow-hidden shadow-lg shadow-slate-200/50 hover:shadow-2xl hover:shadow-slate-300/60 transition-all duration-300 border border-slate-100">
                    <div class="overflow-hidden h-72">
                        <img alt="Dr. Angelo Electona" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBwTDCiQudy5dNxNrsc7hVM9UBrf63-V8B0IKSsnxELSqKC_zdG7P1Gw1kNYjdMGYiqHE82YdmKX5hEvPLKrpvC23D5nSbrG_cdqhQBMXd3U0CKyGBlzl157VYg9KkikxxsgiiG5t2hArLXzX2vG7RcTfX-Q22PEbFGpEa5mya_gitI3rKgMMILxVdOfLJ0reAkl9L5P3kTb2RNVP9B6KNGhItoY4p-T1vATZVpumR-lRJmWdwQjbtlGKsLhd7ai5MlAOdF2i3flQg"/>
                    </div>
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-slate-900 mb-1 font-montserrat">Dr. Angelo Electona</h3>
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
                        <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-8 tracking-tight font-montserrat">Visit Our Center</h2>
                        
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
                                    <p class="text-slate-600">Phone numbers: <a href="tel:09631717322" class="hover:text-emerald-600 transition-colors">(0963) 171-7322</a></p>
                                    <p class="text-red-600 font-bold mt-1">Emergency: 09631717322</p>
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