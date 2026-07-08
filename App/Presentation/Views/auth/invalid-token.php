<?php
// Fallback constant check if BASE_URL isn't globally available yet
if (!defined('BASE_URL')) {
    define('BASE_URL', '/GrapeCultivationAdvisoryChatSystem'); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid Link | Grape Cultivation Advisory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-white min-h-screen flex flex-col antialiased selection:bg-green-700 selection:text-white">

    <div class="flex-grow w-full flex items-center justify-center p-4 sm:p-6">
        
        <main class="w-full max-w-md bg-white rounded-2xl p-8 shadow-2xl shadow-slate-200/90 border border-slate-100 text-center animate__animated animate__zoomIn animate__faster">
            
            <div class="mx-auto w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center text-rose-600 mb-4 animate__animated animate__fadeInDown">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Invalid or Expired Link</h2>
            <p class="text-sm text-slate-500 mt-2 mb-6 max-w-xs mx-auto">The password reset link is invalid or has safely expired. Please request a new one.</p>
            
            <div>
                <a href="<?= BASE_URL ?>/forgot-password" 
                   class="inline-flex items-center justify-center w-full bg-[#15803D] hover:bg-green-800 text-white py-3 px-4 font-semibold rounded-xl tracking-wide transition-all duration-200 shadow-md shadow-green-700/10 active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700">
                    Request New Link
                </a>
            </div>

            <p class="text-sm font-medium mt-6">
                <a href="<?= BASE_URL ?>/login" class="text-slate-500 hover:text-slate-700 transition-colors inline-flex items-center gap-1 hover:underline underline-offset-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </a>
            </p>

        </main>
    </div>

</body>
</html>