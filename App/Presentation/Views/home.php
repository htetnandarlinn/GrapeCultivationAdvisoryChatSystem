

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
        Browse expert cultivation articles, get reliable advice, and improve your grape farming with our advisory chat system.
      </p>
      
      <!-- CTA Action Row with Micro-Interactions -->
      <div class="flex flex-wrap gap-3.5">
        <a href="<?= BASE_URL ?>/articles" class="bg-[#1a1a1a] text-white px-5 py-3 rounded-lg text-sm font-semibold hover:bg-black transition-all active:scale-[0.98]">
          Browse Articles
        </a>
        <a href="<?= BASE_URL ?>/articles" class="bg-white text-[#1a1a1a] px-5 py-3 rounded-lg text-sm font-semibold border-1.5 border-gray-300 hover:bg-gray-50 transition-all active:scale-[0.98]">
          Learn More
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

      <!-- Feature Card 2 -->
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

    </div>
  </section>