

  <!-- HERO SECTION -->
  <!-- Responsive: 1 column on mobile, 2 columns on desktops (lg:grid-cols-[0.9fr_1.3fr]) -->
  <section class="grid grid-cols-1 lg:grid-cols-[0.9fr_1.3fr] items-center gap-8 px-6 py-10 md:px-16 md:py-14 bg-gradient-to-b from-[#eaf3e9] to-[#e3f0e2]">
    <div class="max-w-[420px] mx-auto lg:mx-0">
      
      
      <!-- Typography with Fluid Scaling -->
      <h1 class="text-3xl sm:text-4.5xl font-extrabold leading-[1.15] text-[#111] tracking-tight">
        Grape Cultivation<br />
        <span class="text-[#2e7d32]">Advisory Chat System</span>
      </h1>
      
      <p class="text-sm sm:text-base text-[#555] leading-relaxed my-5 max-w-[380px]">
        Connect with agricultural experts, ask questions, upload disease images, and get reliable advice to improve your grape cultivation.
      </p>
      
      <!-- CTA Action Row with Micro-Interactions -->
      <div class="flex flex-wrap gap-3.5">
        <button class="bg-[#1a1a1a] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:bg-black transition-all active:scale-[0.98]" id="askExpertBtn">
          Ask an Expert
        </button>
        <a href="<?= BASE_URL ?>/articles" class="bg-white text-[#1a1a1a] px-5 py-3 rounded-lg text-sm font-semibold border-1.5 border-gray-300 hover:bg-gray-50 transition-all active:scale-[0.98]" id="browseArticlesBtn">
          Browse Articles
        </a>
      </div>
      
      <!-- Social Proof Stack -->
      <div class="mt-7 flex items-center gap-2.5">
        <div class="flex -space-x-2">
          <span class="w-8 h-8 rounded-full border-2 border-white bg-[#81c784]"></span>
          <span class="w-8 h-8 rounded-full border-2 border-white bg-[#64b5f6]"></span>
          <span class="w-8 h-8 rounded-full border-2 border-white bg-[#ffb74d]"></span>
        </div>
        <p class="text-xs sm:text-sm font-medium text-gray-600">Trusted by farmers. Guided by experts.</p>
      </div>
    </div>

    <!-- Right Media Layout Section -->
    <div class="w-full lg:ml-[-20px] transition-transform duration-500 hover:scale-[1.01]">
      <img src="<?= BASE_URL ?>/assets/images/grape-chat-preview.png" alt="Grape Advisory System" class="w-full h-auto block mx-auto max-w-md lg:max-w-none">
    </div>
  </section>

  <!-- FEATURES SECTION -->
  <section class="px-6 py-12 md:px-16 md:py-16 max-w-7xl mx-auto">
    <p class="text-center text-[#2e7d32] text-xs font-bold tracking-widest uppercase mb-2">WHAT WE OFFER</p>
    <h2 class="text-center text-2.5xl sm:text-3.5xl font-extrabold text-[#111]">Our Key Features</h2>
    
    <!-- Responsive Feature Grid: 1 col on mobile, 2 cols on tablet, 4 cols on big screens -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-9">

      <!-- Feature Card 1 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#e8f5e9] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Ask Questions</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Farmers can ask questions about grape cultivation, diseases, and farm management.</p>
      </div>

      <!-- Feature Card 2 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#f3e5f5] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#9c27b0" viewBox="0 0 24 24">
            <path d="M16 13l6.96 5.79c.32.27.04.79-.36.62l-8.55-3.6-.4 1.69c-.06.26-.42.29-.52.04l-2.16-5.43-7.6-3.21c-.31-.13-.27-.6.06-.68L20.84 4.6c.32-.08.6.21.51.53L16 13z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Upload Images</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Upload images of grape plants or affected areas to help experts give accurate advice.</p>
      </div>

      <!-- Feature Card 3 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#fff3e0] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#f57c00" viewBox="0 0 24 24">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm1 16h-2v-2h2v2zm0-4h-2V7h2v6z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Disease Management</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Get correct identification and management advice for grape diseases from experts.</p>
      </div>

      <!-- Feature Card 4 -->
      <a href="<?= BASE_URL ?>/articles" class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300 block">
        <div class="w-10 h-10 rounded-lg bg-[#e3f2fd] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#1565c0" viewBox="0 0 24 24">
            <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H7V9h10v2zm-3 4H7v-2h7v2zM17 7H7V5h10v2z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Cultivation Articles</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Read helpful articles and guides on grape farming techniques.</p>
      </a>

      <!-- Feature Card 5 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#fffde7] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#f9a825" viewBox="0 0 24 24">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Notifications</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Get notified when experts reply or new updates are available.</p>
      </div>

      <!-- Feature Card 6 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#e3f2fd] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#1565c0" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Profile Management</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Update personal information and manage your account settings.</p>
      </div>

      <!-- Feature Card 7 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#e8f5e9] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Secure Access</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Role-based access for farmers and experts ensures data security and privacy.</p>
      </div>

      <!-- Feature Card 8 -->
      <div class="border border-[#e8e8e8] rounded-xl p-5 hover:shadow-[0_10px_30px_rgba(0,0,0,0.06)] hover:-translate-y-0.5 transition-all duration-300">
        <div class="w-10 h-10 rounded-lg bg-[#fce4ec] flex items-center justify-center mb-3.5">
          <svg width="20" height="20" fill="#c62828" viewBox="0 0 24 24">
            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" />
          </svg>
        </div>
        <h4 class="text-sm font-bold mb-1.5">Consultation History</h4>
        <p class="text-xs sm:text-sm text-[#777] leading-relaxed">Track your consultation history and review past conversations.</p>
      </div>

    </div>
  </section>

  <!-- HOW IT WORKS SECTION -->
  <section class="bg-[#fafafa] px-6 py-12 md:px-16 md:py-16">
    <p class="text-center text-[#2e7d32] text-xs font-bold tracking-widest uppercase mb-2">HOW IT WORKS</p>
    <h2 class="text-center text-2.5xl sm:text-3.5xl font-extrabold text-[#111]">Simple Steps to Get Advice</h2>
    
    <div class="flex flex-col md:flex-row items-center justify-center gap-6 md:gap-4 mt-12 max-w-5xl mx-auto">

      <!-- Step 1 -->
      <div class="text-center max-w-[140px]">
        <div class="w-16 h-16 rounded-full bg-[#e8f5e9] flex items-center justify-center mx-auto mb-3.5 relative">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
          </svg>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#1a1a1a] text-white rounded-full text-[10px] font-bold flex items-center justify-center">1</div>
        </div>
        <h4 class="text-sm font-bold mb-1">Register</h4>
        <p class="text-xs text-[#777]">Create your account as a farmer.</p>
      </div>

      <div class="hidden md:block text-gray-300 font-bold text-xl px-2">&rarr;</div>

      <!-- Step 2 -->
      <div class="text-center max-w-[140px]">
        <div class="w-16 h-16 rounded-full bg-[#e8f5e9] flex items-center justify-center mx-auto mb-3.5 relative">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#1a1a1a] text-white rounded-full text-[10px] font-bold flex items-center justify-center">2</div>
        </div>
        <h4 class="text-sm font-bold mb-1">Ask a Question</h4>
        <p class="text-xs text-[#777]">Describe your problem and upload images.</p>
      </div>

      <div class="hidden md:block text-gray-300 font-bold text-xl px-2">&rarr;</div>

      <!-- Step 3 -->
      <div class="text-center max-w-[140px]">
        <div class="w-16 h-16 rounded-full bg-[#fff3e0] flex items-center justify-center mx-auto mb-3.5 relative">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 1c-1.46 0-2.78.35-3.83.91A4.99 4.99 0 0 0 4 18v1h16v-1a4.99 4.99 0 0 0-4.17-4.93A8.3 8.3 0 0 0 12 13z" />
          </svg>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#f57c00] text-white rounded-full text-[10px] font-bold flex items-center justify-center">3</div>
        </div>
        <h4 class="text-sm font-bold mb-1">Expert Reviews</h4>
        <p class="text-xs text-[#777]">The expert reviews and analyzes the issue.</p>
      </div>

      <div class="hidden md:block text-gray-300 font-bold text-xl px-2">&rarr;</div>

      <!-- Step 4 -->
      <div class="text-center max-w-[140px]">
        <div class="w-16 h-16 rounded-full bg-[#f3e5f5] flex items-center justify-center mx-auto mb-3.5 relative">
          <svg width="28" height="28" fill="#7b1fa2" viewBox="0 0 24 24">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
          </svg>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#7b1fa2] text-white rounded-full text-[10px] font-bold flex items-center justify-center">4</div>
        </div>
        <h4 class="text-sm font-bold mb-1">Get Expert Advice</h4>
        <p class="text-xs text-[#777]">Receive practical recommended solutions.</p>
      </div>

      <div class="hidden md:block text-gray-300 font-bold text-xl px-2">&rarr;</div>

      <!-- Step 5 -->
      <div class="text-center max-w-[140px]">
        <div class="w-16 h-16 rounded-full bg-[#e8f5e9] flex items-center justify-center mx-auto mb-3.5 relative">
          <svg width="28" height="28" fill="#2e7d32" viewBox="0 0 24 24">
            <path d="M9 16.2l-3.5-3.5L4 14.2 9 19.2l11-11-1.4-1.4z" />
          </svg>
          <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#1a1a1a] text-white rounded-full text-[10px] font-bold flex items-center justify-center">5</div>
        </div>
        <h4 class="text-sm font-bold mb-1">Improve Your Farm</h4>
        <p class="text-xs text-[#777]">Apply the advice and increase your success.</p>
      </div>

    </div>
  </section>

  <!-- CTA BANNER SECTION -->
  <div class="mx-6 my-10 md:mx-16 md:my-14 max-w-6xl lg:mx-auto">
    <div class="bg-gradient-to-br from-[#2e7d32] to-[#6a1b9a] rounded-2xl p-8 sm:p-11 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-lg">
      <div class="flex items-start gap-4">
        <div class="text-3xl sm:text-4xl bg-white/10 p-3 rounded-xl backdrop-blur-sm">🍇</div>
        <div>
          <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-tight">Need Help with Your Grapes?</h2>
          <p class="text-sm text-white/80 mt-1 max-w-xl">Our experts are ready to help you with any grape cultivation challenge you face in the field.</p>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/consultation/ask" class="inline-flex items-center gap-2 bg-white text-[#2e7d32] px-6 py-3.5 rounded-lg text-sm font-bold transition-all transform hover:bg-gray-50 active:scale-[0.98] shadow-md shrink-0 w-full md:w-auto justify-center" id="startConsultationBtn">
        <svg width="16" height="16" fill="none" stroke="#2e7d32" stroke-width="2" viewBox="0 0 24 24">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
        </svg>
        Start Consultation
      </a>
    </div>
  </div>


  <script>
    document.getElementById("askExpertBtn").addEventListener("click", () => { window.location.href = '<?= BASE_URL ?>/consultation/ask'; });
    document.getElementById("startConsultationBtn").addEventListener("click", e => {
      e.preventDefault();
      alert("Start Consultation clicked");
    });
  </script>