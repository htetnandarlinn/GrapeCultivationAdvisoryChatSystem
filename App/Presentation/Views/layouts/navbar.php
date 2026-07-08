<nav class="flex items-center justify-between px-5 py-3.5 md:px-[60px] md:py-3.5 bg-white shadow-[0_1px_6px_rgba(0,0,0,0.07)] sticky top-0 z-[100]">
    <div class="flex items-center gap-3">
        <div class="w-[60px] h-[60px] flex items-center justify-center shrink-0">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Grape Cultivation Advisory Chat System Logo" class="w-full h-full object-contain">
        </div>
        <div class="text-xs font-bold leading-[1.3] text-grapeDark">
            Grape Cultivation<br>
            <span class="block font-normal text-[#555]">Advisory Chat System</span>
        </div>
    </div>
    <div class="hidden md:flex items-center gap-9">
        <a href="<?= BASE_URL ?>/" class="no-underline text-grapeGreen font-semibold text-sm">Home</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Articles</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">About</a>
        <a href="#" class="no-underline text-[#555] font-medium text-sm hover:text-grapeGreen hover:font-semibold transition-colors">Contact</a>
    </div>
    <div class="flex items-center gap-2.5">
        <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (defined('BASE_URL') && BASE_URL !== '' && str_starts_with($currentPath, BASE_URL)) {
            $currentPath = substr($currentPath, strlen(BASE_URL)) ?: '/';
        }
        $hideLogin = preg_match('#^/(login|register|forgot-password|reset-password)(\?|$)#', $currentPath);
        ?>
        <?php if (!$hideLogin): ?>
        <a href="<?= BASE_URL ?>/login" class="no-underline px-[22px] py-2 border-[1.5px] border-grapeGreen text-grapeGreen bg-transparent rounded-md text-sm font-semibold transition-colors hover:bg-green-50">Login</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/register" class="hidden sm:inline-block no-underline px-[22px] py-2 border-0 bg-grapeGreen text-white rounded-md text-sm font-semibold transition-opacity hover:opacity-90">Register</a>
        <button class="md:hidden flex flex-col justify-center items-center w-8 h-8 gap-1 border border-gray-200 rounded p-1" onclick="toggleMobileNavbar()">
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
            <span class="w-5 h-0.5 bg-[#555]"></span>
        </button>
    </div>
</nav>
<div id="mobileNavigationDrawer" class="hidden md:hidden bg-white border-b border-gray-100 px-6 py-4 space-y-3.5 shadow-inner">
    <a href="<?= BASE_URL ?>/" class="block no-underline text-grapeGreen font-semibold text-sm">Home</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Articles</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">About</a>
    <a href="#" class="block no-underline text-[#555] font-medium text-sm hover:text-grapeGreen">Contact</a>
    <a href="<?= BASE_URL ?>/register" class="block sm:hidden no-underline text-grapeGreen font-semibold text-sm pt-2 border-t border-gray-100">Register Account</a>
</div>
<script>
function toggleMobileNavbar() {
    const drawer = document.getElementById('mobileNavigationDrawer');
    drawer.classList.toggle('hidden');
}
</script>
