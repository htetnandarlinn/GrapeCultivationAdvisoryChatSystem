<style>
@keyframes breathe {
  0%, 100% { transform: scale(1); box-shadow: 0 4px 14px rgba(21, 128, 61, 0.15); }
  50% { transform: scale(1.04); box-shadow: 0 6px 24px rgba(21, 128, 61, 0.3); }
}
.btn-breathe { animation: breathe 2s ease-in-out infinite; }
.btn-breathe-secondary { animation: breathe 2s ease-in-out 0.4s infinite; }
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-4px); }
}
.circle-float { animation: float 2.5s ease-in-out infinite; }
.circle-float:nth-child(2) { animation-delay: 0.3s; }
.circle-float:nth-child(3) { animation-delay: 0.6s; }
@keyframes messageInLeft {
  0% { opacity: 0; transform: translateX(-30px) scale(0.95); }
  60% { transform: translateX(4px) scale(1.01); }
  100% { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes messageInRight {
  0% { opacity: 0; transform: translateX(30px) scale(0.95); }
  60% { transform: translateX(-4px) scale(1.01); }
  100% { opacity: 1; transform: translateX(0) scale(1); }
}
@keyframes imageUpload {
  0% { width: 0%; opacity: 1; }
  80% { opacity: 1; }
  100% { width: 100%; opacity: 0; }
}
@keyframes sentPulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.1); } }
@keyframes readCheck { 0% { opacity:0; transform:translateX(-4px); } 100% { opacity:1; transform:translateX(0); } }
@keyframes statusTransition { 0% { color:#94a3b8; } 50% { color:#15803D; } 100% { color:#15803D; } }
@keyframes glowNew { 0%,100% { box-shadow:0 0 0 0 rgba(21,128,61,0.15); } 50% { box-shadow:0 0 12px 4px rgba(21,128,61,0.08); } }
@keyframes scrollHint { 0%,100% { transform:translateY(0); opacity:0.6; } 50% { transform:translateY(3px); opacity:1; } }
.msg-left { animation: messageInLeft 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
.msg-right { animation: messageInRight 0.5s cubic-bezier(0.16,1,0.3,1) forwards; }
.upload-bar { animation: imageUpload 2s ease-in-out forwards; }
.sent-dot { animation: sentPulse 1.5s ease-in-out infinite; }
.read-check { animation: readCheck 0.3s ease-out forwards; }
.status-read { animation: statusTransition 1s ease-in-out 0.5s forwards; }
.glow-new { animation: glowNew 2s ease-in-out 2; }

/* --- Chat card hover animation --- */
@keyframes cornerTraceX { 0% { width: 0; } 100% { width: 24px; } }
@keyframes cornerTraceY { 0% { height: 0; } 100% { height: 24px; } }
@keyframes sweepRay {
  0% { transform: translateX(-120%) translateY(-120%) rotate(35deg); opacity: 0; }
  10% { opacity: 0.6; }
  90% { opacity: 0.6; }
  100% { transform: translateX(120%) translateY(120%) rotate(35deg); opacity: 0; }
}
.chat-hover-card {
  position: relative;
  transition: transform 0.35s cubic-bezier(0.16,1,0.3,1), box-shadow 0.35s cubic-bezier(0.16,1,0.3,1);
}
.chat-hover-card::before,
.chat-hover-card::after {
  content: '';
  position: absolute;
  background: #15803D;
  border-radius: 1px;
  opacity: 0;
  transition: opacity 0.25s;
  z-index: 2;
  pointer-events: none;
}
.chat-hover-card::before { top: 10px; left: 10px; height: 2px; transform-origin: left; }
.chat-hover-card::after  { top: 10px; left: 10px; width: 2px; transform-origin: top; }
.chat-hover-card:hover::before { opacity: 1; animation: cornerTraceX 0.3s ease-out forwards; }
.chat-hover-card:hover::after  { opacity: 1; animation: cornerTraceY 0.3s ease-out 0.1s forwards; }

.chat-hover-card .sweep-beam {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  border-radius: inherit;
  z-index: 1;
  opacity: 0;
  transition: opacity 0.01s 0.15s;
}
@keyframes badgeShimmer {
  0% { background-position: -200% center; }
  100% { background-position: 200% center; }
}
.animate-badge {
  background: linear-gradient(110deg, transparent 30%, rgba(21,128,61,0.12) 48%, rgba(255,255,255,0.25) 50%, rgba(21,128,61,0.12) 52%, transparent 70%);
  background-size: 200% 100%;
  animation: badgeShimmer 3s ease-in-out infinite;
}

/* --- Feature cards scroll reveal + float + action --- */
@keyframes cardEntrance {
  0% { opacity: 0; transform: translateY(36px) scale(0.93); box-shadow: none; }
  45% { opacity: 1; transform: translateY(-6px) scale(1.015); box-shadow: 0 6px 24px rgba(21,128,61,0.08); }
  70% { transform: translateY(2px) scale(1); box-shadow: 0 10px 34px rgba(21,128,61,0.11); }
  100% { opacity: 1; transform: translateY(0) scale(1); box-shadow: 0 8px 28px rgba(21,128,61,0.08); }
}
@keyframes cardFloat {
  0%, 100% { transform: translateY(0) scale(1); }
  50% { transform: translateY(-6px) scale(1); }
}
@keyframes iconReveal {
  0% { transform: scale(1) rotate(0deg); box-shadow: none; }
  50% { transform: scale(1) rotate(0deg); box-shadow: none; }
  80% { transform: scale(1.13) rotate(180deg); box-shadow: 0 6px 20px rgba(21,128,61,0.18); }
  100% { transform: scale(1.1) rotate(360deg); box-shadow: 0 4px 16px rgba(21,128,61,0.12); }
}
@keyframes iconRotate {
  0% { transform: scale(1.1) rotate(0deg); }
  100% { transform: scale(1.1) rotate(360deg); }
}
@keyframes barSlide {
  0% { width: 0; }
  55% { width: 0; }
  80% { width: 44px; }
  100% { width: 36px; }
}
.feature-card {
  opacity: 0;
  transform: translateY(36px) scale(0.93);
  box-shadow: none;
}
.feature-card.in-view {
  animation: cardEntrance 0.7s cubic-bezier(0.16,1,0.3,1) forwards,
             cardFloat 3s ease-in-out 0.9s infinite;
}
.feature-card:hover {
  animation-play-state: paused;
  transform: translateY(-10px) scale(1);
  box-shadow: 0 14px 44px rgba(21,128,61,0.15);
  transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), box-shadow 0.3s;
}
.feature-card .icon-box {
  transform: scale(1) rotate(0deg);
  box-shadow: none;
}
.feature-card.in-view .icon-box {
  animation: iconReveal 0.7s cubic-bezier(0.16,1,0.3,1) forwards,
             iconRotate 4s linear 0.8s infinite;
}
.feature-card:hover .icon-box {
  animation-play-state: paused;
  transform: scale(1.18) rotate(0deg);
  box-shadow: 0 8px 24px rgba(21,128,61,0.22);
  transition: transform 0.3s cubic-bezier(0.16,1,0.3,1), box-shadow 0.3s;
}
.feature-card .accent-bar {
  width: 0;
  height: 2px;
  background: #15803D;
  border-radius: 2px;
  margin-top: 3px;
}
.feature-card.in-view .accent-bar {
  animation: barSlide 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
}
.feature-card:hover .accent-bar {
  width: 52px;
  transition: width 0.3s cubic-bezier(0.16,1,0.3,1);
}

/* --- Horizontal Looping Steps --- */
.h-scroll-wrap {
  overflow: hidden;
  padding: 10px 0;
  mask-image: linear-gradient(to right, transparent 0%, black 5%, black 95%, transparent 100%);
}
.h-scroll-track {
  display: flex;
  gap: 24px;
  width: max-content;
  animation: hScroll 18s linear infinite;
}
.h-scroll-track:hover { animation-play-state: paused; }
.h-scroll-card {
  flex-shrink: 0;
  width: 160px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 16px 12px;
  transition: box-shadow 0.25s, transform 0.25s;
}
.h-scroll-card:hover {
  box-shadow: 0 6px 20px rgba(21,128,61,0.12);
  transform: translateY(-2px);
}
.h-scroll-card .step-circle {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 6px;
  transition: background 0.25s, box-shadow 0.25s;
}
.h-scroll-card:hover .step-circle {
  background: #15803D;
  box-shadow: 0 4px 14px rgba(21,128,61,0.2);
}
.h-scroll-card .step-number {
  font-size: 0.85rem;
  font-weight: 900;
  color: #94a3b8;
  transition: color 0.25s;
}
.h-scroll-card:hover .step-number { color: white; }
.h-scroll-card h4 {
  font-size: 0.65rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 2px;
}
.h-scroll-card p {
  font-size: 0.55rem;
  color: #64748b;
  line-height: 1.35;
  max-width: 130px;
}
@keyframes hScroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
@media (max-width: 767px) {
  .h-scroll-card { width: 130px; padding: 12px 10px; }
  .h-scroll-track { gap: 16px; }
  .h-scroll-card .step-circle { width: 30px; height: 30px; }
  .h-scroll-card .step-number { font-size: 0.7rem; }
  .h-scroll-card h4 { font-size: 0.55rem; }
  .h-scroll-card p { font-size: 0.5rem; max-width: 100px; }
}
.chat-hover-card:hover .sweep-beam { opacity: 1; }
.chat-hover-card .sweep-beam::after {
  content: '';
  position: absolute;
  top: -50%; left: -50%;
  width: 200%; height: 200%;
  background: linear-gradient(35deg, transparent 30%, rgba(21,128,61,0.07) 45%, rgba(255,255,255,0.2) 50%, rgba(21,128,61,0.07) 55%, transparent 70%);
  animation: sweepRay 0.9s cubic-bezier(0.16,1,0.3,1) forwards;
}
</style>

<!-- HERO SECTION -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-50 via-white to-emerald-50/30 px-6 py-10 md:px-16 md:py-14">
  <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 25px 25px, #15803D 1px, transparent 0); background-size: 50px 50px;"></div>

  <div class="relative max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 items-center gap-8">
    <!-- Left content -->
    <div class="max-w-xl">
      <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 border border-emerald-200 text-emerald-700 text-[10px] font-bold tracking-wider uppercase mb-4 animate__animated animate__fadeInUp animate-badge">Expert Advice at Your Fingertips</span>
      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-[1.1] tracking-tight animate__animated animate__fadeInUp" style="animation-delay:0.1s">
        Grape Cultivation<br />
        <span class="text-[#15803D]">Advisory Chat System</span>
      </h1>
      <p class="text-sm sm:text-base text-slate-500 leading-relaxed mt-4 max-w-lg animate__animated animate__fadeInUp" style="animation-delay:0.2s">
        Browse expert cultivation articles, get reliable advice, and improve your grape farming with our advisory chat system.
      </p>

      <!-- CTA Buttons -->
      <div class="flex flex-wrap gap-3 mt-6 animate__animated animate__fadeInUp" style="animation-delay:0.3s">
        <a href="<?= BASE_URL ?>/articles" class="btn-breathe inline-flex items-center gap-2 bg-[#15803D] text-white px-5 py-3 rounded-xl text-xs font-bold hover:bg-green-800 hover:shadow-lg hover:shadow-emerald-900/20 hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.97]">
          Browse Articles
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </a>
        <a href="<?= BASE_URL ?>/register" class="btn-breathe-secondary inline-flex items-center gap-2 border-2 border-slate-200 text-slate-700 px-5 py-3 rounded-xl text-xs font-bold hover:border-emerald-200 hover:text-emerald-700 hover:-translate-y-0.5 transition-all duration-200 active:scale-[0.97]">
          Get Started Free
        </a>
      </div>

      <!-- Social Proof -->
      <div class="flex items-center gap-3 mt-6 animate__animated animate__fadeInUp" style="animation-delay:0.4s">
        <div class="flex -space-x-2">
          <?php foreach ($profiles as $p): ?>
            <span class="circle-float w-8 h-8 rounded-full border-2 border-white bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-700 shadow-sm hover:scale-110 hover:-translate-y-1 transition-transform duration-200"><?= strtoupper(substr($p->getUsername(), 0, 1)) ?></span>
          <?php endforeach; ?>
        </div>
        <p class="text-xs text-slate-500 font-medium">Trusted by farmers. Guided by experts.</p>
      </div>
    </div>

    <!-- Right - Chat Mockup -->
    <div class="relative animate__animated animate__fadeInRight" style="animation-delay:0.3s">
      <div class="relative bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xl hover:shadow-2xl transition-shadow duration-500 chat-hover-card">
        <span class="sweep-beam"></span>
        <!-- Header -->
        <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 bg-gradient-to-r from-emerald-700 to-emerald-600">
          <div class="flex gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-300"></span>
          </div>
          <span class="text-[11px] font-semibold text-white ml-2">Grape Advisory Chat</span>
          <span class="ml-auto flex items-center gap-1.5 text-[9px] text-emerald-200 font-semibold">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
            Online
          </span>
        </div>
        <!-- Messages -->
        <div class="p-3 space-y-2 min-h-[260px] bg-slate-50/50 overflow-hidden relative">
          <div id="chatMessages" class="space-y-2">
          <div class="absolute bottom-2 right-3 text-[7px] text-slate-400 flex items-center gap-1" style="animation: scrollHint 1.5s ease-in-out infinite;">
            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
            scroll
          </div>
          <!-- 1. Farmer asks question -->
          <div class="flex items-start gap-2 max-w-[88%] opacity-0 msg-left" style="animation-delay:0.3s">
            <span class="w-6 h-6 rounded-full bg-amber-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-amber-700 shrink-0 mt-0.5 shadow-sm">F</span>
            <div class="bg-white rounded-2xl rounded-tl-sm px-3 py-2 shadow-sm glow-new">
              <p class="text-[10px] text-slate-700 leading-relaxed">My grape leaves have purple spots, what should I do?</p>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[7px] text-slate-400">Farmer Juan</span>
                <div class="flex items-center gap-1">
                  <span class="text-[6px] text-slate-400">2 min ago</span>
                  <svg class="w-2 h-2 text-slate-400" viewBox="0 0 16 16" fill="currentColor"><path d="M11.5 5.5L5.5 11.5L2.5 8.5" stroke="currentColor" stroke-width="1.5" fill="none"/><path d="M11.5 9.5L8.5 12.5" stroke="currentColor" stroke-width="1.5" fill="none"/></svg>
                </div>
              </div>
            </div>
          </div>
          <!-- 2. Expert replies -->
          <div class="flex items-start gap-2 max-w-[88%] ml-auto flex-row-reverse opacity-0 msg-right" style="animation-delay:0.7s">
            <span class="w-6 h-6 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-emerald-700 shrink-0 mt-0.5 shadow-sm">E</span>
            <div class="bg-emerald-50 rounded-2xl rounded-tr-sm px-3 py-2 shadow-sm border border-emerald-100 glow-new">
              <p class="text-[10px] text-slate-700 leading-relaxed">That could be black rot. Can you send a photo of the leaves?</p>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[7px] text-slate-400">Expert Maria</span>
                <div class="flex items-center gap-1">
                  <span class="text-[6px] text-slate-400">1 min ago</span>
                  <svg class="w-2 h-2 text-emerald-500 read-check" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2.5 8.5L5.5 11.5L11.5 5.5"/><path d="M7 12L9 14.5L15 8" stroke-linecap="round"/></svg>
                </div>
              </div>
            </div>
          </div>
          <!-- 3. Farmer sends a photo -->
          <div class="flex items-start gap-2 max-w-[72%] opacity-0 msg-left" style="animation-delay:1.1s">
            <span class="w-6 h-6 rounded-full bg-amber-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-amber-700 shrink-0 mt-0.5 shadow-sm">F</span>
            <div class="bg-white rounded-2xl rounded-tl-sm p-1.5 shadow-sm border border-slate-100">
              <div class="h-0.5 bg-slate-100 rounded-full mb-1.5 overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full upload-bar" style="width:0%"></div>
              </div>
              <div class="rounded-lg overflow-hidden bg-slate-100 border border-slate-200 relative group">
                <img src="<?= BASE_URL ?>/assets/images/grape6.jpg" alt="Grape leaf" class="w-28 h-20 object-cover">
                <div class="absolute bottom-1 right-1 w-3.5 h-3.5 rounded-full bg-emerald-500 flex items-center justify-center opacity-0 msg-left" style="animation-delay:1.4s">
                  <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
              </div>
              <div class="flex items-center justify-between mt-1 px-1">
                <span class="text-[7px] text-slate-400">photo_2026-07-10.jpg</span>
                <div class="flex items-center gap-1">
                  <span class="text-[6px] text-slate-400">36 KB</span>
                  <div class="flex items-center gap-0.5">
                    <span class="w-1 h-1 rounded-full bg-emerald-500 sent-dot"></span>
                    <span class="text-[6px] text-emerald-600 font-bold status-read">Delivered</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 4. Expert receives photo -->
          <div class="flex items-start gap-2 max-w-[55%] ml-auto flex-row-reverse opacity-0 msg-right" style="animation-delay:1.6s">
            <span class="w-6 h-6 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-emerald-700 shrink-0 mt-0.5 shadow-sm">E</span>
            <div class="bg-emerald-50 rounded-2xl rounded-tr-sm px-3 py-2 shadow-sm border border-emerald-100">
              <div class="flex items-center gap-1.5">
                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-semibold text-slate-700">Photo received</span>
              </div>
              <span class="text-[7px] text-slate-400 mt-0.5 block">Expert Maria — analyzing...</span>
            </div>
          </div>
          <!-- 5. Expert gives final advice -->
          <div class="flex items-start gap-2 max-w-[88%] ml-auto flex-row-reverse opacity-0 msg-right" style="animation-delay:2s">
            <span class="w-6 h-6 rounded-full bg-emerald-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-emerald-700 shrink-0 mt-0.5 shadow-sm">E</span>
            <div class="bg-emerald-50 rounded-2xl rounded-tr-sm px-3 py-2 shadow-sm border border-emerald-100 glow-new">
              <p class="text-[10px] text-slate-700 leading-relaxed">Confirmed! That's <strong>black rot</strong>. Apply a copper-based fungicide and remove the infected leaves immediately.</p>
              <div class="flex items-center justify-between mt-1">
                <span class="text-[7px] text-slate-400">Expert Maria</span>
                <div class="flex items-center gap-1">
                  <span class="text-[6px] text-slate-400">just now</span>
                  <svg class="w-2 h-2 text-emerald-500" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M2.5 8.5L5.5 11.5L11.5 5.5"/><path d="M7 12L9 14.5L15 8" stroke-linecap="round"/></svg>
                </div>
              </div>
            </div>
          </div>
          <!-- 6. Farmer typing -->
          <div class="flex items-center gap-2 max-w-[25%] opacity-0 msg-left" style="animation-delay:2.5s">
            <span class="w-6 h-6 rounded-full bg-amber-100 border-2 border-white flex items-center justify-center text-[7px] font-bold text-amber-700 shrink-0 shadow-sm">F</span>
            <div class="bg-white rounded-2xl rounded-tl-sm px-2.5 py-2 shadow-sm flex gap-1">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0s"></span>
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.15s"></span>
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-bounce" style="animation-delay:0.3s"></span>
            </div>
          </div>
          </div>
        </div>
        <script>
        (function(){
          var chat = document.getElementById('chatMessages');
          if (!chat) return;
          var html = chat.innerHTML;
          function replay() { chat.innerHTML = html; }
          setTimeout(replay, 6500);
          setInterval(replay, 6500);
        })();
        </script>
        <!-- Input -->
        <div class="border-t border-slate-100 px-3 py-2.5 bg-white flex items-center gap-2">
          <div class="flex-1 bg-slate-50 rounded-xl px-3 py-1.5 text-[10px] text-slate-400 border border-slate-100">Type your message...</div>
          <div class="w-7 h-7 rounded-xl bg-[#15803D] flex items-center justify-center hover:bg-green-800 transition-colors cursor-pointer shadow-sm">
            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M12 5l7 7-7 7"/></svg>
          </div>
        </div>
      </div>
      <div class="absolute -top-3 -right-3 w-16 h-16 opacity-10">
        <svg viewBox="0 0 100 100" class="w-full h-full text-emerald-700">
          <circle cx="50" cy="30" r="12" fill="currentColor" opacity="0.8"/>
          <circle cx="35" cy="50" r="10" fill="currentColor" opacity="0.6"/>
          <circle cx="65" cy="50" r="10" fill="currentColor" opacity="0.6"/>
          <circle cx="50" cy="65" r="9" fill="currentColor" opacity="0.5"/>
          <path d="M50 18 Q55 5 65 8" stroke="#15803D" stroke-width="2" fill="none"/>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- FEATURES SECTION -->
<section class="relative px-6 py-10 md:px-16 md:py-14 bg-white">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-2xl mx-auto mb-10">
      <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-bold tracking-wider uppercase mb-3">What We Offer</span>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Everything You Need for Smarter Grape Farming</h2>
      <p class="text-xs text-slate-500 mt-2 max-w-md mx-auto">Expert guidance, real-time advice, and a community of growers helping each other succeed.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5" id="featuresGrid">
      <div class="feature-card relative bg-white rounded-2xl p-5 cursor-default" style="animation-delay:0.05s">
        <div class="icon-box w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm1 16h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Disease Management</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Get correct identification and management advice for grape diseases from experts.</p>
      </div>

      <a href="<?= BASE_URL ?>/articles" class="feature-card relative bg-white rounded-2xl p-5 block" style="animation-delay:0.1s">
        <div class="icon-box w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H7V9h10v2zm-3 4H7v-2h7v2zM17 7H7V5h10v2z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Cultivation Articles</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Read helpful articles and guides on grape farming techniques.</p>
      </a>

      <div class="feature-card relative bg-white rounded-2xl p-5 cursor-default" style="animation-delay:0.15s">
        <div class="icon-box w-10 h-10 rounded-xl bg-yellow-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Smart Notifications</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Get notified when experts reply or new updates are available.</p>
      </div>

      <div class="feature-card relative bg-white rounded-2xl p-5 cursor-default" style="animation-delay:0.2s">
        <div class="icon-box w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Profile Management</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Update personal information and manage your account settings.</p>
      </div>

      <div class="feature-card relative bg-white rounded-2xl p-5 cursor-default" style="animation-delay:0.25s">
        <div class="icon-box w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Live Chat Support</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Chat directly with experts for personalized advice on your grape crops.</p>
      </div>

      <div class="feature-card relative bg-white rounded-2xl p-5 cursor-default" style="animation-delay:0.3s">
        <div class="icon-box w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center mb-3">
          <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        </div>
        <h4 class="text-xs font-bold text-slate-900">Secure &amp; Private</h4>
        <div class="accent-bar"></div>
        <p class="text-xs text-slate-500 leading-relaxed mt-1">Role-based access for farmers and experts ensures data security and privacy.</p>
      </div>
    </div>
  </div>
</section>

<script>
(function(){
  var grid = document.getElementById('featuresGrid');
  if (!grid || !('IntersectionObserver' in window)) return;
  var cards = grid.querySelectorAll('.feature-card');
  var obs = new IntersectionObserver(function(entries) {
    if (entries[0].isIntersecting) {
      cards.forEach(function(c) { c.classList.add('in-view'); });
      obs.disconnect();
    }
  }, { threshold: 0.15 });
  obs.observe(grid);
})();
</script>



<!-- HOW IT WORKS -->
<section class="px-6 py-10 md:px-16 md:py-14 bg-slate-50">
  <div class="max-w-7xl mx-auto">
    <div class="text-center max-w-2xl mx-auto mb-10">
      <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-[9px] font-bold tracking-wider uppercase mb-3">How It Works</span>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Get Advice in 5 Simple Steps</h2>
    </div>
    <div class="h-scroll-wrap">
      <div class="h-scroll-track" id="hScrollTrack">
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">1</span></div>
          <h4>Sign Up Free</h4>
          <p>Create your account as a farmer or expert.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">2</span></div>
          <h4>Ask Your Question</h4>
          <p>Describe your grape issue with photos.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">3</span></div>
          <h4>Get Expert Advice</h4>
          <p>Personalized guidance from experts.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">4</span></div>
          <h4>Apply Tips</h4>
          <p>Put solutions to work in your vineyard.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">5</span></div>
          <h4>Track Progress</h4>
          <p>Monitor vine health over time.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">1</span></div>
          <h4>Sign Up Free</h4>
          <p>Create your account as a farmer or expert.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">2</span></div>
          <h4>Ask Your Question</h4>
          <p>Describe your grape issue with photos.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">3</span></div>
          <h4>Get Expert Advice</h4>
          <p>Personalized guidance from experts.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">4</span></div>
          <h4>Apply Tips</h4>
          <p>Put solutions to work in your vineyard.</p>
        </div>
        <div class="h-scroll-card">
          <div class="step-circle"><span class="step-number">5</span></div>
          <h4>Track Progress</h4>
          <p>Monitor vine health over time.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA BANNER -->
<section class="px-6 py-10 md:py-14 bg-white">
  <div class="max-w-3xl mx-auto text-center">
    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Ready to Transform Your Grape Farming?</h2>
    <p class="text-xs text-slate-500 mt-2 max-w-lg mx-auto">Join our community of farmers and experts. Get the advice you need, when you need it.</p>
    <div class="flex flex-wrap justify-center gap-3 mt-6">
      <a href="<?= BASE_URL ?>/register" class="inline-flex items-center gap-2 bg-[#15803D] text-white px-5 py-3 rounded-xl text-xs font-bold hover:bg-green-800 hover:shadow-lg hover:shadow-emerald-900/20 hover:-translate-y-0.5 transition-all duration-200">
        Get Started Free
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
      </a>
      <a href="<?= BASE_URL ?>/articles" class="inline-flex items-center gap-2 border-2 border-slate-200 text-slate-700 px-5 py-3 rounded-xl text-xs font-bold hover:border-emerald-200 hover:text-emerald-700 hover:-translate-y-0.5 transition-all duration-200">
        Browse Articles
      </a>
    </div>
  </div>
</section>
