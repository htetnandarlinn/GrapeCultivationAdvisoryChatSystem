<?php
$current_route = $_SERVER['REQUEST_URI'];
function is_active($path) {
    global $current_route;
    return strpos($current_route, $path) !== false;
}
?>
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

<aside id="app-sidebar" class="fixed top-0 left-0 bottom-0 w-72 bg-white border-r border-slate-100 z-40 translate-x-[-100%] md:translate-x-0 transition-transform duration-300 ease-out flex flex-col">
    <div class="p-6 flex items-center gap-3 border-b border-slate-50">
        <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#15803D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828 0l.707-.707M17.657 6.343l.707-.707M14 12a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <div>
            <h1 class="text-sm font-black text-slate-900 leading-tight">Grape Cultivation</h1>
            <p class="text-[10px] font-bold text-slate-400 tracking-wider uppercase">Advisory Chat</p>
        </div>
    </div>

    <nav class="flex-grow p-4 space-y-1 overflow-y-auto">

<?php if (!empty($_SESSION['user'])): ?>
        <a href="<?= BASE_URL ?>/dashboard" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('dashboard') && !is_active('ask-question') && !is_active('total-questions') && !is_active('admin/') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                Dashboard
            </div>
        </a>
<?php endif; ?>

<?php if (can('users.view')): ?>
        <a href="<?= BASE_URL ?>/admin/users" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('admin/users') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                User Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('questions.view')): ?>
        <a href="<?= BASE_URL ?>/admin/questions" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('admin/questions') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Question Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('roles.view')): ?>
        <a href="<?= BASE_URL ?>/admin/roles" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('admin/roles') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                Role Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('questions.ask')): ?>
        <a href="<?= BASE_URL ?>/farmer-dashboard/ask-question" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('ask-question') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                Ask Question
            </div>
        </a>
<?php endif; ?>

<?php if (can('questions.view')): ?>
        <a href="<?= BASE_URL ?>/farmer-dashboard/total-questions" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('total-questions') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                My Questions
            </div>
        </a>
<?php endif; ?>

<?php if (can('questions.answer')): ?>
        <a href="<?= BASE_URL ?>/expert/questions" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('expert/questions') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                View Questions
            </div>
            <span class="px-2 py-0.5 text-xs font-black rounded-full bg-amber-500 text-white animate-pulse">8</span>
        </a>
<?php endif; ?>

<?php if (can('articles.view')): ?>
        <a href="<?= BASE_URL ?>/expert/articles" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('expert/articles') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                Article Management
            </div>
        </a>
<?php endif; ?>

<?php if (can('profile.view')): ?>
        <a href="<?= BASE_URL ?>/profile" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('profile') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                Profile
            </div>
        </a>
<?php endif; ?>

    </nav>
</aside>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if(sidebar.classList.contains('translate-x-[-100%]')) {
        sidebar.classList.remove('translate-x-[-100%]');
        backdrop.classList.remove('hidden');
        setTimeout(() => backdrop.classList.remove('opacity-0'), 10);
    } else {
        sidebar.classList.add('translate-x-[-100%]');
        backdrop.classList.add('opacity-0');
        setTimeout(() => backdrop.classList.add('hidden'), 30);
    }
}
</script>
