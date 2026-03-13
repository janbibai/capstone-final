<header class="sticky top-0 z-50 bg-white border-b border-black/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="bg-primary text-white w-10 h-10 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="font-semibold text-lg text-gray-800">
                    RuralHealth Unit
                </span>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center gap-8 text-gray-600 font-medium">
                <a href="{{ url('/') }}" class="hover:text-primary transition">Home</a>
                <a href="{{ url('/#services') }}" class="hover:text-primary transition">Our Services</a>
                <a href="{{ url('/#doctors') }}" class="hover:text-primary transition">Doctors</a>
                <a href="{{ url('/#contact') }}" class="hover:text-primary transition">Contact</a>
                <a href="{{ route('staff.login') }}" class="hover:text-primary transition">Login</a>
            </nav>

            {{-- Desktop CTA --}}
            <a href="{{ route('appointment.create') }}"
               class="hidden md:inline-flex bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-medium transition shadow-sm">
                Book Appointment
            </a>

            {{-- Mobile Menu Button --}}
            <button
                type="button"
                class="md:hidden text-gray-700"
                aria-label="Open menu"
                onclick="document.getElementById('mobileNav').classList.toggle('hidden')"
            >
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

        </div>

        {{-- Mobile Nav --}}
        <div id="mobileNav" class="md:hidden hidden pb-4">
            <div class="flex flex-col gap-3 text-gray-700 font-medium">
                <a href="{{ url('/') }}" class="py-2 border-b border-black/5">Home</a>
                <a href="{{ url('/#services') }}" class="py-2 border-b border-black/5">Our Services</a>
                <a href="{{ url('/#doctors') }}" class="py-2 border-b border-black/5">Doctors</a>
                <a href="{{ url('/#contact') }}" class="py-2 border-b border-black/5">Contact</a>
                <a href="{{ route('staff.login') }}" class="py-2 border-b border-black/5">Login</a>

                <a href="{{ route('appointment.create') }}"
                   class="mt-2 inline-flex justify-center bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Book Appointment
                </a>
            </div>
        </div>

    </div>
</header>