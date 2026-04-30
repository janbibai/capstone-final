<footer class="bg-slate-900 text-slate-300 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <img src="{{ asset('images/ZamboanguitaLogo.png') }}" alt="RHU Logo" class="w-8 h-8 rounded-lg object-contain">
                    <h2 class="text-xl font-bold text-white font-montserrat">Rural Health Unit</h2>
                </div>
                <p class="text-sm leading-relaxed">Dedicated to bringing world-class healthcare to our rural communities through technology and compassion.</p>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-6 font-montserrat">Quick Links</h3>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="#">Our Services</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Health Tips</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Patient Portal</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-6 font-montserrat">Services</h3>
                <ul class="space-y-4 text-sm">
                    <li><a class="hover:text-primary transition-colors" href="#">Outpatient Consultation</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Maternal and Child health</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Dental Services</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Public Health services</a></li>
                    <li><a class="hover:text-primary transition-colors" href="#">Medical Consultation</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-white font-bold mb-6 font-montserrat">Emergency Hotlines</h3>
                <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                    <p class="text-primary font-black text-2xl mb-1">09631717322</p>
                    <p class="text-xs uppercase tracking-widest text-slate-400">Emergency</p>
                </div>
            </div>
        </div>
        
        <div class="pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <p>© {{ date('Y') }} Rural Health Unit. All rights reserved.</p>
            <div class="flex gap-6">
                <a class="hover:text-white transition-colors" href="#">Privacy Policy</a>
                <a class="hover:text-white transition-colors" href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>