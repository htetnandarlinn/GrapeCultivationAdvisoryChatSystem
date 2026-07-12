<style>
@keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
@keyframes glow { 0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.3); } 50% { box-shadow: 0 0 20px 4px rgba(16, 185, 129, 0.15); } }
.reveal { opacity: 0; transform: translateY(20px); transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1); }
.reveal.visible { opacity: 1; transform: translateY(0); }
.reveal-d1 { transition-delay: 0.1s; }
.reveal-d2 { transition-delay: 0.2s; }
.reveal-d3 { transition-delay: 0.3s; }
.reveal-d4 { transition-delay: 0.4s; }
.animate-float { animation: float 4s ease-in-out infinite; }
.animate-glow { animation: glow 3s ease-in-out infinite; }
</style>

<!-- Hero -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-green-800 to-emerald-900 text-white">
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 30% 40%, rgba(255,255,255,0.2) 0%, transparent 50%);"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 relative">
        <div class="text-center max-w-2xl mx-auto" style="animation: fadeInUp 0.6s ease-out;">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emerald-200 text-[10px] font-bold tracking-wider uppercase mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                About Our Platform
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-[1.15] tracking-tight">
                Growing Smarter,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-green-200">Together</span>
            </h1>
            <p class="text-sm sm:text-base text-emerald-100/80 mt-3 max-w-xl mx-auto leading-relaxed">
                We bridge the gap between farmers and agricultural experts through real-time digital consultation, empowering sustainable grape cultivation across Myanmar.
            </p>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 40V20C240 0 480 0 720 20C960 40 1200 40 1440 20V40H0Z" fill="#F8FAFC"/></svg>
    </div>
</section>

<!-- Mission & Vision -->
<section class="bg-slate-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-5">
            <div class="reveal visible">
                <div class="bg-white rounded-xl p-5 border border-slate-200/70 shadow-sm h-full hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                        </div>
                        <h2 class="text-base font-black text-slate-900">Our Mission</h2>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">To empower grape farmers with accessible, real-time expert agricultural advice through innovative digital technology, reducing crop loss and increasing sustainable yields across Myanmar's vineyards.</p>
                </div>
            </div>
            <div class="reveal visible reveal-d1">
                <div class="bg-white rounded-xl p-5 border border-slate-200/70 shadow-sm h-full hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                        </div>
                        <h2 class="text-base font-black text-slate-900">Our Vision</h2>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">A world where every grape farmer has instant access to expert knowledge, fostering a community of data-driven, sustainable agriculture that thrives in harmony with nature.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 reveal">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Our Impact in Numbers</h2>
            <p class="text-slate-500 mt-1 text-xs">Growing the grape cultivation community every day</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="reveal reveal-d1">
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 text-center border border-emerald-100/50 hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="text-2xl font-black text-emerald-700">50+</div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Expert Advisors</p>
                </div>
            </div>
            <div class="reveal reveal-d2">
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 text-center border border-emerald-100/50 hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="text-2xl font-black text-emerald-700">200+</div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Farmers Assisted</p>
                </div>
            </div>
            <div class="reveal reveal-d3">
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 text-center border border-emerald-100/50 hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="text-2xl font-black text-emerald-700">300+</div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Consultations Done</p>
                </div>
            </div>
            <div class="reveal reveal-d4">
                <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-5 text-center border border-emerald-100/50 hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="text-2xl font-black text-emerald-700">100%</div>
                    <p class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mt-0.5">Free Access</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values -->
<section class="py-12 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 reveal">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">What We Stand For</h2>
            <p class="text-slate-500 mt-1 text-xs">Our core values drive every decision we make</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="reveal reveal-d1">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Accessibility</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Free expert advice for every farmer, regardless of location or background.</p>
                </div>
            </div>
            <div class="reveal reveal-d2">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Expert Knowledge</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Verified specialists with years of hands-on experience in grape cultivation.</p>
                </div>
            </div>
            <div class="reveal reveal-d3">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Real-Time Support</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Instant messaging and live consultations when you need them most.</p>
                </div>
            </div>
            <div class="reveal reveal-d1">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Sustainable Growth</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Eco-friendly practices that protect soil health and improve long-term yields.</p>
                </div>
            </div>
            <div class="reveal reveal-d2">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Community First</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">A supportive network where farmers learn from experts and each other.</p>
                </div>
            </div>
            <div class="reveal reveal-d3">
                <div class="bg-white rounded-xl p-4 border border-slate-200/70 shadow-sm hover:shadow-md transition-all hover:-translate-y-0.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 mb-2.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.61-.464 5.027-1.136 7.105m-10.5-13.364A7.5 7.5 0 004.5 10.5c0 2.61.464 5.027 1.136 7.105m10.5-13.364a7.5 7.5 0 00-1.136-.614"/></svg></div>
                    <h3 class="text-sm font-bold text-slate-900 mb-1">Innovation</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Leveraging technology to transform traditional farming at your fingertips.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Story -->
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8 items-center">
            <div class="reveal">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[9px] font-bold tracking-wider uppercase mb-2.5">Our Story</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mb-3">From Farm Fields to Digital Fields</h2>
                <p class="text-xs text-slate-600 leading-relaxed mb-2.5">Born from the challenges faced by grape farmers in Myanmar, our platform started as a simple idea — connect farmers directly with agricultural experts through instant messaging.</p>
                <p class="text-xs text-slate-600 leading-relaxed mb-2.5">Today, we've grown into a comprehensive advisory system where farmers can share images of crop issues, receive real-time diagnoses, access a library of cultivation articles, and get personalized recommendations from verified specialists.</p>
                <p class="text-xs text-slate-600 leading-relaxed">Every consultation, every article, and every expert interaction brings us closer to our vision of a thriving, knowledge-powered agricultural community.</p>
            </div>
            <div class="reveal reveal-d1">
                <div class="relative animate-float">
                    <div class="rounded-xl overflow-hidden shadow-lg bg-gradient-to-br from-emerald-100 via-green-50 to-emerald-50 animate-glow hover:shadow-emerald-200/50 hover:shadow-xl transition-shadow duration-500">
                        <img src="<?= BASE_URL ?>/assets/images/grape-chat-preview.png" alt="Grape Cultivation Chat System Preview" class="w-full h-48 object-cover hover:scale-[1.03] transition-transform duration-700">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-12 bg-gradient-to-br from-emerald-900 via-green-800 to-emerald-900 text-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center reveal">
        <h2 class="text-xl sm:text-2xl font-black tracking-tight mb-2">Ready to Transform Your Farming?</h2>
        <p class="text-emerald-100/80 text-sm max-w-lg mx-auto mb-5">Join hundreds of farmers already getting expert advice. Sign up today — it's completely free.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="<?= BASE_URL ?>/register" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-white text-emerald-800 font-bold rounded-lg hover:bg-emerald-50 transition-all shadow-lg hover:shadow-xl text-xs">
                Get Started Free
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
            </a>
            <a href="<?= BASE_URL ?>/articles" class="inline-flex items-center gap-1.5 px-5 py-2.5 border border-white/30 text-white font-bold rounded-lg hover:bg-white/10 transition-all text-xs">
                Browse Articles
            </a>
        </div>
    </div>
</section>

<script>
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
    });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>