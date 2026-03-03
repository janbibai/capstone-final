<header class="sticky top-0 z-50 bg-white border-b border-black/5">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="bg-primary text-white w-10 h-10 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">health_and_safety</span>
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
                <span class="material-symbols-outlined text-3xl">menu</span>
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