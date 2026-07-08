<?php
$current_route = $_SERVER['REQUEST_URI'];
function is_active($path) {
    global $current_route;
    return strpos($current_route, $path) !== false;
}
?>
<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

<aside id="expert-sidebar" class="fixed top-0 left-0 bottom-0 w-72 bg-white border-r border-slate-100 z-40 translate-x-[-100%] md:translate-x-0 transition-transform duration-300 ease-out flex flex-col">
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
        <a href="<?= BASE_URL ?>/expert/dashboard" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('dashboard') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                Dashboard
            </div>
        </a>

        <a href="<?= BASE_URL ?>/expert/questions" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('questions') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                View Questions
            </div>
            <span class="px-2 py-0.5 text-xs font-black rounded-full bg-amber-500 text-white animate-pulse">8</span>
        </a>

        <a href="<?= BASE_URL ?>/expert/chats" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('chats') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                Active Chats
            </div>
            <span class="px-2 py-0.5 text-xs font-black rounded-full bg-emerald-500 text-white">5</span>
        </a>

        <a href="<?= BASE_URL ?>/expert/history" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('history') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                Consultations History
            </div>
        </a>

        <a href="<?= BASE_URL ?>/expert/articles" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('articles') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                Article Management
            </div>
        </a>

        <a href="<?= BASE_URL ?>/expert/notifications" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('notifications') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                Notifications
            </div>
            <span class="px-2 py-0.5 text-xs font-black rounded-full bg-rose-500 text-white">5</span>
        </a>

        <a href="<?= BASE_URL ?>/expert/profile" class="flex items-center justify-between p-3 rounded-xl font-bold text-sm transition-all <?= is_active('profile') ? 'bg-[#15803D] text-white shadow-md shadow-emerald-900/10' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' ?>">
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 01-8 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                Profile
            </div>
        </a>
    </nav>
</aside>