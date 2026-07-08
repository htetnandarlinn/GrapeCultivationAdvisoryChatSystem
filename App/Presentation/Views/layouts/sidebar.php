<?php
$currentPage = $activePage ?? 'dashboard';
?>

<div id="sidebarBackdrop" class="fixed inset-0 bg-slate-900/30 backdrop-blur-sm z-40 hidden lg:hidden transition-opacity duration-300 opacity-0"></div>

<aside id="sidebarDrawer" class="font-inter fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-100 p-5 z-50 transform -translate-x-full transition-transform duration-300 ease-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen flex flex-col justify-between shrink-0 antialiased text-[#1a1a1a]">

    <div class="space-y-6">

        <!-- LOGO -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <a href="<?= BASE_URL ?>/farmer-dashboard" class="flex items-center gap-3 group">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" class="w-10 h-10 object-contain">
                <div>
                    <span class="block font-extrabold text-xs group-hover:text-[#2e7d32]">Grape Cultivation</span>
                    <span class="block text-[9px] text-[#2e7d32] font-bold uppercase">Advisory Chat System</span>
                </div>
            </a>
        </div>

        <!-- NAV -->
        <nav class="space-y-1">

            <a href="<?= BASE_URL ?>/farmer-dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= $currentPage === 'dashboard'
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                📊 Dashboard
            </a>

            <a href="<?= BASE_URL ?>/farmer-dashboard/ask-question"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= ($currentPage === 'ask' || $currentPage === 'ask-question')
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                ❓ Ask Question
            </a>

            <a href="<?= BASE_URL ?>/farmer-dashboard/consultation"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= $currentPage === 'consultation'
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                💬 Consultation
            </a>

            <a href="<?= BASE_URL ?>/farmer-dashboard/articles"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= $currentPage === 'articles'
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                📰 Articles
            </a>

            <a href="<?= BASE_URL ?>/farmer-dashboard/notifications"
               class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= $currentPage === 'notifications'
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                <span>🔔 Notifications</span>
                <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">3</span>
            </a>

            <a href="<?= BASE_URL ?>/farmer-dashboard/total-questions"
               class="flex items-center gap-3 px-4 py-3 rounded-xl font-semibold text-sm transition-all
               <?= $currentPage === 'total-questions'
                    ? 'bg-[#2e7d32] text-white'
                    : 'text-slate-600 hover:text-[#2e7d32] hover:bg-[#eaf3e9]/40' ?>">
                📋 Total Questions
            </a>

        </nav>
    </div>

</aside>