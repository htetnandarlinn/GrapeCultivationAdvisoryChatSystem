<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? null;
unset($_SESSION['errors'], $_SESSION['success']);

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
    <title>Forgot Password | Grape Cultivation Advisory</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-white min-h-screen flex flex-col antialiased selection:bg-green-700 selection:text-white">

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="flex-grow w-full flex items-center justify-center p-4 sm:p-6">
        
        <main class="w-full max-w-md bg-white rounded-2xl p-8 shadow-2xl shadow-slate-200/90 border border-slate-100 animate__animated animate__zoomIn animate__faster">
            
            <div class="text-center mb-6">
                <div class="mx-auto w-14 h-14 bg-green-50 rounded-full flex items-center justify-center text-green-700 mb-4 animate__animated animate__fadeInDown">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Forgot Password?</h2>
                <p class="text-sm text-slate-500 mt-1">No worries, we'll send you recovery instructions.</p>
            </div>

            <?php if ($success): ?>
                <div class="p-4 bg-green-50 border border-green-100 text-green-700 rounded-xl mb-5 text-sm font-medium flex items-start gap-3 animate__animated animate__fadeIn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl mb-5 text-sm font-medium flex items-start gap-3 animate__animated animate__shakeX">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="whitespace-pre-line"><?= htmlspecialchars(implode("\n", $errors)) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/forgot-password" method="POST" id="recoveryForm" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Registered Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </span>
                        <input type="email" 
                               name="email" 
                               required 
                               class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:border-green-700 focus:ring-4 focus:ring-green-700/10 transition-all duration-200" 
                               placeholder="you@example.com">
                    </div>
                </div>
                
                <div>
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full bg-[#15803D] hover:bg-green-800 text-white py-3 px-4 font-semibold rounded-xl tracking-wide transition-all duration-200 shadow-md shadow-green-700/10 active:scale-[0.99] flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700">
                        <span id="btnText">Send Reset Link</span>
                        <svg id="btnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>
            </form>

            <p class="text-sm text-center font-medium mt-6">
                <a href="<?= BASE_URL ?>/login" class="text-green-700 hover:text-green-800 transition-colors inline-flex items-center gap-1 hover:underline underline-offset-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </a>
            </p>
        </main>
    </div>

    <script>
        document.getElementById('recoveryForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            text.innerText = 'Sending Link...';
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>