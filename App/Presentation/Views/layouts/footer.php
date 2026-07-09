<footer class="bg-[#111] text-[#aaa] px-5 py-[40px] md:px-[60px] md:py-[52px] pb-[32px]">

    <!-- Structural Grid Engine: 1 column on mobile, 2 columns on tablets, 4 columns on desktop layouts -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1.8fr_1fr_1fr_1.4fr] gap-10 mb-10">

        <!-- Column 1: Brand Ecosystem Column Layout Group -->
        <div class="space-y-3">
            <div class="text-[15px] font-bold text-white leading-tight">
                🍇 Grape Advisory<br>
                <span class="text-xs text-[#888] font-normal">Chat System</span>
            </div>

            <p class="text-xs text-[#888] line-height-[1.6] max-w-[220px]">
                A digital platform connecting grape farmers with agricultural experts for better cultivation.
            </p>

            <!-- Social Icon Anchor Row Section -->
            <div class="flex items-center gap-2.5 pt-2">
                <a href="#" class="no-underline w-8 h-8 rounded bg-[#222] flex items-center justify-center text-[#aaa] text-sm hover:text-white transition-colors">f</a>
                <a href="#" class="no-underline w-8 h-8 rounded bg-[#222] flex items-center justify-center text-[#aaa] text-sm hover:text-white transition-colors">✈</a>
                <a href="#" class="no-underline w-8 h-8 rounded bg-[#222] flex items-center justify-center text-[#aaa] text-sm hover:text-white transition-colors">✉</a>
            </div>
        </div>

        <!-- Column 2: Navigation Map Links -->
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Quick Links</h5>
            <ul class="list-none p-0 space-y-2.5">
                <li><a href="<?= BASE_URL ?>/" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Home</a></li>
                <li><a href="<?= BASE_URL ?>/articles" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Articles</a></li>
                <li><a href="#" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Experts</a></li>
                <li><a href="#" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Features</a></li>
                <li><a href="#" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Contact</a></li>
            </ul>
        </div>

        <!-- Column 3: Identity Controls Area Layout -->
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">User Access</h5>
            <ul class="list-none p-0 space-y-2.5">
                <li><a href="<?= BASE_URL ?>/login" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Farmer Login</a></li>
                <li><a href="<?= BASE_URL ?>/login" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Expert Login</a></li>
                <li><a href="<?= BASE_URL ?>/register" class="no-underline text-[#888] text-xs hover:text-white transition-colors">Register</a></li>
            </ul>
        </div>

        <!-- Column 4: Location Vector & Technical Contact Matrix -->
        <div>
            <h5 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Contact Us</h5>
            <ul class="list-none p-0 space-y-3">
                <li class="flex items-start gap-2.5 text-xs text-[#888]">
                    <svg width="14" height="14" fill="#888" class="shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                    <span>+95 123 456 789</span>
                </li>
                <li class="flex items-start gap-2.5 text-xs text-[#888]">
                    <svg width="14" height="14" fill="#888" class="shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    <span class="break-all">support@grapeadvisory.com</span>
                </li>
                <li class="flex items-start gap-2.5 text-xs text-[#888]">
                    <svg width="14" height="14" fill="#888" class="shrink-0 mt-0.5" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    <span>Agricultural Advisory Center,<br>Nay Pyi Taw, Myanmar</span>
                </li>
            </ul>
        </div>

    </div>

    <!-- Footer Copyright Bottom Row Area -->
    <div class="border-t border-[#222] pt-5 text-center text-xs text-[#555]">
        &copy; <span id="currentYear"></span> Grape Advisory Chat System. All rights reserved.
    </div>

</footer>

<script>
// Safely inserts the current calendar year dynamically
document.getElementById('currentYear').textContent = new Date().getFullYear();

function togglePassword(id, button){
    const input = document.getElementById(id);
    if(input.type === "password"){
        input.type = "text";
        button.innerHTML = "👁‍🗨";
    } else {
        input.type = "password";
        button.innerHTML = "👁";
    }
}
</script>
</body>
</html>