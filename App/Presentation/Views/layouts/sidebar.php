<?php
$raw = $_SERVER['REQUEST_URI'];
$base = defined('BASE_URL') ? BASE_URL : '';
if ($base && str_starts_with($raw, $base)) {
    $raw = substr($raw, strlen($base));
}
$raw = parse_url($raw, PHP_URL_PATH);
$raw = rtrim($raw, '/') ?: '/';
$current_route = $raw;
// function $is_active($path) {
//     global $current_route;
//     $normalised = rtrim($path, '/');
//     if ($normalised === '' || $normalised === '/') {
//         return $current_route === '/';
//     }
//     $normalised = '/' . ltrim($normalised, '/');
//     return $current_route === $normalised || str_starts_with($current_route, $normalised . '/');
// }

$current_route = $raw;

$is_active = function ($path) use ($current_route) {
    $normalised = '/' . trim($path, '/');

    return $current_route === $normalised
        || str_starts_with($current_route, $normalised . '/');
};

function active_style($cond) {
    return $cond ? 'background:#15803D!important;color:#fff!important;border-radius:12px!important;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1)!important;' : '';
}
?>
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden md:hidden opacity-0 transition-opacity duration-300" onclick="toggleSidebar()" aria-hidden="true"></div>

<aside id="app-sidebar" class="fixed top-0 left-0 bottom-0 w-72 bg-white border-r border-slate-100 z-50 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-out flex flex-col" aria-label="Sidebar">
    <div class="p-6 flex items-center gap-3 border-b border-slate-50">
        <div class="w-[45px] h-[45px] flex items-center justify-center shrink-0">
            <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo" class="w-full h-full object-contain">
        </div>
        <div>
            <h1 class="text-sm font-black text-slate-900 leading-tight">Grape Cultivation</h1>
            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Advisory Chat</p>
        </div>
    </div>

    <nav class="flex-grow p-4 space-y-1 overflow-y-auto">

<?php if (!empty($_SESSION['user'])): ?>
        <a href="<?= BASE_URL ?>/dashboard" style="<?= active_style($is_active('dashboard') && !$is_active('admin/') && !$is_active('notifications')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                Dashboard
            </div>
        </a>
<?php endif; ?>

<?php if (can('users.view')): ?>
        <a href="<?= BASE_URL ?>/admin/users" style="<?= active_style($is_active('admin/users')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                User Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('consultations.view')): ?>
        <a href="<?= BASE_URL ?>/admin/consultations" style="<?= active_style($is_active('admin/consultations')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Consultation Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('consultations.view')): ?>
        <a href="<?= BASE_URL ?>/admin/payments" style="<?= active_style($is_active('admin/payments')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Payment Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('consultations.answer') && ($_SESSION['user_role'] ?? '') === 'expert'): ?>
        <a href="<?= BASE_URL ?>/expert/consultations/hub" style="<?= active_style($is_active('expert/consultations/hub')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Consultations
            </div>
        </a>
        <a href="<?= BASE_URL ?>/expert/farmers" style="<?= active_style($is_active('expert/farmers')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                Consulting Farmers
            </div>
        </a>
<?php endif; ?>

<?php if (can('articles.view')): ?>
        <a href="<?= BASE_URL ?>/expert/articles" style="<?= active_style($is_active('expert/articles')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                Article Management
            </div>
        </a>
        <?php if (($_SESSION['user_role'] ?? '') === 'expert'): ?>
        <a href="<?= BASE_URL ?>/expert/article-images" style="<?= active_style($is_active('expert/article-images')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                Uploaded Images
            </div>
        </a>
        <?php endif; ?>
<?php endif; ?>

<?php if (!empty($_SESSION['user'])): ?>
        <a href="<?= BASE_URL ?>/notifications" style="<?= active_style($is_active('notifications')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                Notifications
            </div>
        </a>
<?php endif; ?>

<?php if (!empty($_SESSION['user']) && ($_SESSION['user_role'] ?? '') === 'farmer'): ?>
        <a href="<?= BASE_URL ?>/consultation/my-consultations" style="<?= active_style($is_active('consultation/')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                My Consultations
            </div>
        </a>
        <a href="<?= BASE_URL ?>/payment/history" style="<?= active_style($is_active('payment/history')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                Payment History
            </div>
        </a>
<?php endif; ?>

<?php if (can('roles.view')): ?>
        <a href="<?= BASE_URL ?>/admin/roles" style="<?= active_style($is_active('admin/roles')) ?>" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all text-slate-600 hover:bg-[#15803D]/10 hover:text-[#15803D]">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Setting
            </div>
        </a>
<?php endif; ?>

    </nav>
</aside>

<script>
(function () {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');
    const toggles = [
        document.getElementById('mobileSidebarToggle'),
    ].filter(Boolean);

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            rafId = requestAnimationFrame(() => backdrop.classList.remove('opacity-0'));
        });
        syncAria(true);
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        backdrop.classList.add('opacity-0');
        document.body.classList.remove('overflow-hidden');
        const hide = () => backdrop.classList.add('hidden');
        if ('transitionDuration' in backdrop.style) {
            const onEnd = (e) => {
                if (e.propertyName !== 'opacity') return;
                backdrop.removeEventListener('transitionend', onEnd);
                hide();
            };
            backdrop.addEventListener('transitionend', onEnd);
            setTimeout(() => { backdrop.removeEventListener('transitionend', onEnd); hide(); }, 350);
        } else {
            hide();
        }
        syncAria(false);
    }

    function syncAria(open) {
        toggles.forEach((btn) => {
            btn.setAttribute('aria-expanded', String(open));
            const icon = btn.querySelector('span');
            if (icon) icon.textContent = open ? '✕' : '☰';
        });
    }

    // Exposed for inline onclick usage (backdrop / legacy button)
    window.toggleSidebar = function () {
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    };

    toggles.forEach((btn) => btn.addEventListener('click', window.toggleSidebar));

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Auto-close when resizing up to desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768 && !sidebar.classList.contains('-translate-x-full')) {
            closeSidebar();
        }
    });

    // Close after navigating via sidebar links (mobile)
    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 768) closeSidebar();
        });
    });
})();
</script>
