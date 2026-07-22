<?php
$baseUrl = defined('BASE_URL') && BASE_URL !== '' ? rtrim(BASE_URL, '/') : 'http://localhost/GrapeCultivationAdvisoryChatSystem/public';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Failed</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        @keyframes customPulseError {
            0%, 100% { transform: scale(1); opacity: .4; }
            50% { transform: scale(1.1); opacity: .1; }
        }
        .pulse-ring-error {
            animation: customPulseError 3s infinite ease-in-out;
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col antialiased">

    <div class="flex-1 flex items-center justify-center p-6 w-full">

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 max-w-md w-full p-8 text-center border border-slate-100/80 animate__animated animate__fadeInUp animate__fast">

            <div class="relative flex items-center justify-center w-24 h-24 mx-auto mb-6">
                <div class="absolute inset-0 bg-rose-100 rounded-full pulse-ring-error"></div>
                <div class="absolute w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-rose-500 animate__animated animate__headShake animate__delay-1s"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 tracking-tight mb-3 animate__animated animate__fadeIn animate__delay-1s">
                Verification Failed
            </h2>

            <div class="bg-rose-50 rounded-xl p-4 mb-8 border border-rose-100/60 animate__animated animate__fadeIn animate__delay-1s">
                <p class="text-rose-700 text-sm sm:text-base leading-relaxed font-medium">
                    <?= htmlspecialchars($message ?? 'The verification link is invalid or has expired.') ?>
                </p>
            </div>

            <div class="animate__animated animate__fadeInUp animate__delay-1s">
                <a href="<?= $baseUrl ?>/login"
                   class="inline-flex items-center justify-center w-full px-6 py-3.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 rounded-xl transition-all duration-200 shadow-sm hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Login
                </a>
            </div>

        </div>
    </div>

</body>
</html>