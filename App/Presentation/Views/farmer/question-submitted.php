<?php
// App/Presentation/Views/farmer/question-submitted.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? null;
unset($_SESSION['success']);

// Fallback constant check if BASE_URL isn't globally available yet
if (!defined('BASE_URL')) {
    define('BASE_URL', '/GrapeCultivationAdvisoryChatSystem'); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Submitted | Grape Cultivation Advisory</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Animate.css for smooth modern animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Canvas Confetti for the success pop -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 sm:p-6 antialiased selection:bg-emerald-500 selection:text-white">

    <main class="w-full max-w-md bg-white rounded-2xl shadow-xl shadow-slate-200/80 border border-slate-100 overflow-hidden animate__animated animate__zoomIn animate__faster">
        <!-- Top Accent Bar (Updated to match dashboard theme in image_381d80.png) -->
        <div class="h-2 bg-emerald-600"></div>

        <div class="p-8 text-center">
            <!-- Animated Icon Container -->
            <div class="mx-auto my-4 w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 animate__animated animate__bounceIn animate__delay-1s">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 stroke-[2.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Header -->
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight mt-6">
                Question Submitted!
            </h2>

            <!-- Dynamic Feedback Message -->
            <div class="mt-3 text-slate-600 font-medium px-2">
                <?php if ($success): ?>
                    <p class="text-emerald-600 bg-emerald-50 rounded-lg p-3 text-sm border border-emerald-100 animate__animated animate__fadeIn">
                        <?= htmlspecialchars($success) ?>
                    </p>
                <?php else: ?>
                    <p class="text-sm text-slate-500">
                        Your question has been securely received. Our experts will review it and get back to you shortly.
                    </p>
                <?php endif; ?>
            </div>

            <!-- Divider Line -->
            <hr class="my-8 border-slate-100">

            <!-- Action Button (Updated to match dashboard brand color) -->
            <div class="space-y-3">
                <a href="<?= BASE_URL ?>/farmer-dashboard" 
                   class="inline-flex w-full items-center justify-center px-5 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-600/20 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                   Return to Dashboard
                </a>
            </div>
        </div>
    </main>

    <!-- JavaScript for the confetti explosion -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Shoots a burst of green/teal theme confetti to delight the farmer
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#10b981', '#059669', '#34d399', '#6ee7b7']
            });
        });
    </script>
</body>
</html>