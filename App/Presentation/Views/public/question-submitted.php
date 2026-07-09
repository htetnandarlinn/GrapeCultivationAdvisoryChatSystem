<?php
$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
?>
<div class="min-h-screen bg-gradient-to-b from-[#f8faf8] to-white flex items-center justify-center px-4">
    <div class="w-full max-w-md text-center">
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
            <div class="mx-auto w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900">Question Submitted!</h2>
            <p class="text-sm text-slate-500 mt-2">
                <?= $success ? htmlspecialchars($success) : 'Your question has been received. Our experts will review it shortly.' ?>
            </p>
            <div class="mt-8 flex flex-col gap-3">
                <a href="<?= BASE_URL ?>/consultation/my-questions" class="bg-[#15803D] text-white px-5 py-3 rounded-xl text-sm font-bold hover:bg-[#116631] transition shadow-sm">
                    View My Questions
                </a>
                <a href="<?= BASE_URL ?>/" class="text-slate-500 text-xs font-semibold hover:text-slate-700 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
