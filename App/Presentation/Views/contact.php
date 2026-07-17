<style>
.reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1); }
.reveal.visible { opacity: 1; transform: translateY(0); }

/* Staggered contact items */
.contact-card { opacity: 0; transform: translateY(28px) scale(0.98); }
.contact-card.visible { animation: cardPop 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
@keyframes cardPop {
    0% { opacity: 0; transform: translateY(28px) scale(0.98); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

.contact-item { opacity: 0; transform: translateX(-14px); }
.contact-item.visible { animation: itemSlide 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
@keyframes itemSlide {
    0% { opacity: 0; transform: translateX(-14px); }
    100% { opacity: 1; transform: translateX(0); }
}

.social-btn { opacity: 0; transform: translateY(10px) scale(0.9); }
.social-btn.visible { animation: socialPop 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
@keyframes socialPop {
    0% { opacity: 0; transform: translateY(10px) scale(0.9); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

.contact-card:hover { transform: translateY(-4px); box-shadow: 0 28px 60px -28px rgba(21,128,61,0.35); }
.contact-icon { transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.3s ease; }
.contact-item:hover .contact-icon { transform: scale(1.12) rotate(-6deg); background-color: #15803D; color: #fff; }
.social-btn { transition: transform 0.25s ease, background-color 0.25s ease, color 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease; }
.social-btn:hover { transform: translateY(-3px) scale(1.08); box-shadow: 0 10px 22px -10px rgba(21,128,61,0.5); }

.faq-item { opacity: 0; transform: translateY(18px) scale(0.98); }
.faq-item.visible { animation: cardPop 0.6s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
.faq-item { transition: box-shadow 0.3s ease, border-color 0.3s ease, transform 0.3s ease; }
.faq-item:hover { transform: translateY(-2px); box-shadow: 0 16px 36px -22px rgba(21,128,61,0.4); border-color: rgba(21,128,61,0.25); }
.faq-btn .faq-chevron { transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.3s ease; }
.faq-item.active .faq-chevron { transform: rotate(180deg); color: #15803D; }
.faq-item.active .faq-btn { color: #15803D; }
.faq-content { max-height: 0; overflow: hidden; transition: max-height 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.faq-item.active .faq-content { max-height: 200px; }

.faq-title { background: linear-gradient(90deg, #15803D, #22c55e, #15803D); background-size: 200% auto; -webkit-background-clip: text; background-clip: text; color: transparent; }
.faq-title-wrap { opacity: 0; transform: translateY(16px); }
.faq-title-wrap.visible { animation: cardPop 0.7s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
.faq-title.animate-flow { animation: flowGradient 4s linear infinite; }
@keyframes flowGradient { to { background-position: 200% center; } }

.faq-content p { opacity: 0; transform: translateY(6px); transition: opacity 0.4s ease 0.05s, transform 0.4s ease 0.05s; }
.faq-item.active .faq-content p { opacity: 1; transform: translateY(0); }

.map-card { opacity: 0; transform: translateY(24px) scale(0.97); }
.map-card.visible { animation: cardPop 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards; }
.map-pin-wrap { position: relative; display: inline-flex; }
.map-pin { animation: pinBounce 2.4s cubic-bezier(0.34, 1.56, 0.64, 1) infinite; }
@keyframes pinBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-7px); }
}
.map-ring { position: absolute; left: 50%; top: 70%; transform: translate(-50%, -50%); width: 14px; height: 14px; border-radius: 9999px; background: rgba(21,128,61,0.35); animation: mapPulse 2.4s cubic-bezier(0.16, 1, 0.3, 1) infinite; }
@keyframes mapPulse {
    0% { transform: translate(-50%, -50%) scale(0.6); opacity: 0.7; }
    70% { transform: translate(-50%, -50%) scale(2.6); opacity: 0; }
    100% { transform: translate(-50%, -50%) scale(2.6); opacity: 0; }
}
.map-card:hover { box-shadow: 0 28px 60px -28px rgba(21,128,61,0.35); }

@media (prefers-reduced-motion: reduce) {
    * { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
}
</style>

<!-- Hero -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-900 via-green-800 to-emerald-900 text-white">
    <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle at 70% 30%, rgba(255,255,255,0.15) 0%, transparent 50%);"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 relative">
        <div class="text-center max-w-2xl mx-auto" style="animation: fadeInUp 0.6s ease-out;">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-emerald-200 text-[10px] font-bold tracking-wider uppercase mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Get In Touch
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black leading-[1.15] tracking-tight">
                We'd Love to<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-green-200">Hear From You</span>
            </h1>
            <p class="text-sm sm:text-base text-emerald-100/80 mt-3 max-w-xl mx-auto leading-relaxed">
                Have a question, suggestion, or need support? Our team is ready to help you with anything you need.
            </p>
        </div>
    </div>
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 40V20C240 0 480 0 720 20C960 40 1200 40 1440 20V40H0Z" fill="#F8FAFC"/></svg>
    </div>
</section>

<!-- Contact Info (Centered) -->
<section class="bg-slate-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="contact-card bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/70 shadow-sm reveal">
            <div class="text-center mb-6">
                <h2 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">Contact Information</h2>
                <p class="text-xs text-slate-500 mt-1">Reach out through any of these channels. We typically respond within 24 hours.</p>
            </div>

            <div class="space-y-2.5">
                <div class="contact-item bg-slate-50/60 rounded-xl p-3.5 border border-slate-100 flex items-center gap-3 hover:bg-emerald-50/60 transition-colors">
                    <div class="contact-icon w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Email</h3>
                        <p class="text-sm text-slate-900 truncate">support@grapecultivation.com</p>
                    </div>
                </div>

                <div class="contact-item bg-slate-50/60 rounded-xl p-3.5 border border-slate-100 flex items-center gap-3 hover:bg-emerald-50/60 transition-colors">
                    <div class="contact-icon w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Phone</h3>
                        <p class="text-sm text-slate-900">+95 9 752 479 443</p>
                    </div>
                </div>

                <div class="contact-item bg-slate-50/60 rounded-xl p-3.5 border border-slate-100 flex items-center gap-3 hover:bg-emerald-50/60 transition-colors">
                    <div class="contact-icon w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Location</h3>
                        <p class="text-sm text-slate-900">Ywadan, Yamethin, Myanmar</p>
                    </div>
                </div>

                <div class="contact-item bg-slate-50/60 rounded-xl p-3.5 border border-slate-100 flex items-center gap-3 hover:bg-emerald-50/60 transition-colors">
                    <div class="contact-icon w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Hours</h3>
                        <p class="text-sm text-slate-900">24/7 — Always Available</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-12 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-6 reveal faq-title-wrap">
            <h2 class="faq-title animate-flow text-xl sm:text-2xl font-black tracking-tight">Frequently Asked Questions</h2>
            <p class="text-slate-500 mt-1 text-xs">Quick answers to common questions</p>
        </div>
        <div class="space-y-2 reveal reveal-d1" id="faq-list">
            <div class="faq-item bg-white rounded-lg border border-slate-200/50 overflow-hidden">
                <button class="faq-btn w-full flex items-center justify-between p-3 text-left group" onclick="toggleFaq(this)">
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">How do I start a consultation?</span>
                    <svg class="faq-chevron w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="faq-content px-3 pb-3">
                    <p class="text-xs text-slate-600 leading-relaxed">Register an account, log in as a farmer, and click "Start Consultation" to describe your issue. Pay the consultation fee securely, and an expert will be assigned to help you.</p>
                </div>
            </div>
            <div class="faq-item bg-white rounded-lg border border-slate-200/50 overflow-hidden">
                <button class="faq-btn w-full flex items-center justify-between p-3 text-left group" onclick="toggleFaq(this)">
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">Is there a fee for consultations?</span>
                    <svg class="faq-chevron w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="faq-content px-3 pb-3">
                    <p class="text-xs text-slate-600 leading-relaxed">Yes. Farmers pay a consultation fee for each session. A portion goes to the platform as a service fee, and the rest is paid out to the expert who assists you.</p>
                </div>
            </div>
            <div class="faq-item bg-white rounded-lg border border-slate-200/50 overflow-hidden">
                <button class="faq-btn w-full flex items-center justify-between p-3 text-left group" onclick="toggleFaq(this)">
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">How quickly will an expert respond?</span>
                    <svg class="faq-chevron w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="faq-content px-3 pb-3">
                    <p class="text-xs text-slate-600 leading-relaxed">Once your payment is confirmed, an admin assigns it to the most suitable expert. Our experts typically respond within 24 hours during business days.</p>
                </div>
            </div>
            <div class="faq-item bg-white rounded-lg border border-slate-200/50 overflow-hidden">
                <button class="faq-btn w-full flex items-center justify-between p-3 text-left group" onclick="toggleFaq(this)">
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">Can I attach images to my consultation?</span>
                    <svg class="faq-chevron w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="faq-content px-3 pb-3">
                    <p class="text-xs text-slate-600 leading-relaxed">Absolutely! You can upload images when creating a consultation and share photos during your chat for better diagnosis.</p>
                </div>
            </div>
            <div class="faq-item bg-white rounded-lg border border-slate-200/50 overflow-hidden">
                <button class="faq-btn w-full flex items-center justify-between p-3 text-left group" onclick="toggleFaq(this)">
                    <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">How does the payment system work?</span>
                    <svg class="faq-chevron w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                </button>
                <div class="faq-content px-3 pb-3">
                    <p class="text-xs text-slate-600 leading-relaxed">When you start a consultation, you pay the fee securely through the platform. Once the consultation is completed, the platform deducts its service fee and releases the remaining payout to the expert automatically.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map -->
<section class="py-10 bg-slate-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="map-card rounded-xl overflow-hidden border border-slate-200/70 shadow-sm bg-gradient-to-br from-emerald-50 to-green-50 h-36 sm:h-40 flex items-center justify-center transition-shadow duration-300">
            <div class="text-center">
                <div class="map-pin-wrap mb-2">
                    <span class="map-ring"></span>
                    <svg class="map-pin w-8 h-8 text-emerald-500 drop-shadow" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </div>
                <p class="text-xs font-semibold text-slate-500">Ywadan, Yamethin, Myanmar</p>
                <p class="text-[10px] text-slate-400 mt-0.5">Serving grape farmers across the region</p>
            </div>
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

function revealWithStagger(selector, baseDelay) {
    document.querySelectorAll(selector).forEach((el, i) => {
        setTimeout(() => el.classList.add('visible'), baseDelay + i * 90);
    });
}
const infoCard = document.querySelector('.contact-card');
if (infoCard) {
    const infoObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                revealWithStagger('.contact-item', 150);
                entry.target.classList.add('visible');
                infoObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    infoObserver.observe(infoCard);
}

function toggleFaq(btn) {
    const item = btn.parentElement;
    const isActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));
    if (!isActive) item.classList.add('active');
}

const faqList = document.getElementById('faq-list');
if (faqList) {
    const faqObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                revealWithStagger('.faq-item', 100);
                faqObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    faqObserver.observe(faqList);
}

const mapCard = document.querySelector('.map-card');
if (mapCard) {
    const mapObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                mapObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    mapObserver.observe(mapCard);
}
</script>