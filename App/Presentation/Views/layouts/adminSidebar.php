<?php
$currentPage = $activePage ?? 'dashboard';
?>

<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

<aside id="sidebarDrawer" class="fixed inset-y-0 left-0 z-50 w-72 transform overflow-y-auto border-r border-slate-200 bg-white p-6 shadow-2xl shadow-slate-900/5 transition-transform duration-300 ease-out lg:translate-x-0 lg:w-72">
    <div class="flex flex-col gap-8">
        <div class="space-y-4">
            <a href="<?= BASE_URL ?>/admin-dashboard" class="flex items-center gap-3 rounded-3xl bg-slate-50 px-4 py-3 transition duration-300 hover:bg-[#ECFDF5]">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" class="h-11 w-11 rounded-2xl object-cover shadow-sm" alt="Logo">
                <div>
                    <h1 class="text-base font-black text-slate-900">Grape Cultivation</h1>
                    <p class="text-xs uppercase tracking-[0.28em] text-[#15803D]">Advisory System</p>
                </div>
            </a>

            <div class="rounded-[28px] bg-[#F7FDF7] px-4 py-5 ring-1 ring-[#DEF7EC]">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Admin tools</p>
                <p class="mt-3 text-sm leading-6 text-slate-600">Quick access to your dashboard, role controls, and platform settings.</p>
            </div>
        </div>

        <nav class="space-y-3">

            <a href="<?= BASE_URL ?>/admin-dashboard"
               class="group flex items-center gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'dashboard'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                </span>
                Dashboard
            </a>

            <a href="<?= BASE_URL ?>/admin/farmers"
               class="group flex items-center gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'farmers'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
                Farmer Management
            </a>

            <a href="<?= BASE_URL ?>/admin/experts"
               class="group flex items-center gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'experts'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </span>
                Expert Management
            </a>

            <a href="<?= BASE_URL ?>/admin/questions"
               class="group flex items-center justify-between gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'questions'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>Question Management</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-amber-500 px-2.5 py-0.5 text-[10px] font-bold text-white ring-1 ring-amber-500/20"><?= $questionCount ?? 0 ?></span>
            </a>

            <a href="<?= BASE_URL ?>/admin/notifications"
               class="group flex items-center justify-between gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'notifications'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                    <span>Notifications</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-red-500 px-2.5 py-0.5 text-[10px] font-bold text-white ring-1 ring-red-500/20">5</span>
            </a>

            <a href="<?= BASE_URL ?>/admin/notifications"
               class="group flex items-center justify-between gap-3 rounded-3xl border px-4 py-3 text-sm font-semibold transition-all duration-200
               <?= $currentPage === 'notifications'
    ? 'bg-[#15803D] text-white border-transparent shadow-lg shadow-[#0f5f2d]/15'
    : 'border-slate-200 text-slate-700 hover:border-[#15803D] hover:bg-[#ECFDF5] hover:text-[#15803D]' ?>">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-3xl bg-slate-100 text-[#15803D] transition duration-300 group-hover:bg-[#d1fae5]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </span>
                    <span>Notifications</span>
                </div>
                <span class="inline-flex items-center rounded-full bg-red-500 px-2.5 py-0.5 text-[10px] font-bold text-white ring-1 ring-red-500/20">5</span>
            </a>

        </nav>
    </div>
</aside>