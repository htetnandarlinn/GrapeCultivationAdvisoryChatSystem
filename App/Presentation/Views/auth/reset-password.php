<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
$token = $token ?? '';

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
    <title>Reset Password | Grape Cultivation Advisory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-white min-h-screen flex flex-col antialiased selection:bg-green-700 selection:text-white">

    <div class="flex-grow w-full flex items-center justify-center p-4 sm:p-6">
        
        <main class="w-full max-w-md bg-white rounded-2xl p-8 shadow-xl shadow-slate-200/80 border border-slate-100 animate__animated animate__zoomIn animate__faster">
            
            <div class="text-center mb-6">
                <div class="mx-auto w-14 h-14 bg-green-50 rounded-full flex items-center justify-center text-green-700 mb-4 animate__animated animate__fadeInDown">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-[2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Reset Password</h2>
                <p class="text-sm text-slate-500 mt-1">Please enter and confirm your new secure credentials profile.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl mb-5 text-sm font-medium flex items-start gap-3 animate__animated animate__shakeX">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="whitespace-pre-line"><?= htmlspecialchars(implode("\n", $errors)) ?></span>
                </div>
            <?php endif; ?>

            <div id="jsErrorBlock" class="hidden p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl mb-5 text-sm font-medium flex items-start gap-3 animate__animated animate__fadeIn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span id="jsErrorMsg"></span>
            </div>

            <form action="<?= BASE_URL ?>/reset-password" method="POST" id="resetForm" class="space-y-5">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input type="password" 
                               name="password" 
                               id="password"
                               required 
                               minlength="6"
                               class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:border-green-700 focus:ring-4 focus:ring-green-700/10 transition-all duration-200" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.952 11.952 0 01-6.83 1.666L5 12.233c0 3.308 1.808 6.193 4.532 7.737l1.464.83.146.083a.997.997 0 00.951 0l.146-.083 1.464-.83C16.425 18.425 18.233 15.54 18.233 12.233l-.173-7.623a11.954 11.954 0 01-2.442-.628z" />
                            </svg>
                        </span>
                        <input type="password" 
                               name="password_confirm" 
                               id="password_confirm"
                               required 
                               class="block w-full rounded-xl border border-slate-200 pl-11 pr-4 py-3 text-sm placeholder-slate-400 focus:outline-none focus:border-green-700 focus:ring-4 focus:ring-green-700/10 transition-all duration-200" 
                               placeholder="••••••••">
                    </div>
                </div>
                
                <div>
                    <button type="submit" 
                            id="submitBtn"
                            class="w-full bg-[#15803D] hover:bg-green-800 text-white py-3 px-4 font-semibold rounded-xl tracking-wide transition-all duration-200 shadow-md shadow-green-700/10 active:scale-[0.99] flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-700">
                        <span id="btnText">Update Password</span>
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
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const confirmPass = document.getElementById('password_confirm').value;
            const errorBlock = document.getElementById('jsErrorBlock');
            const errorMsg = document.getElementById('jsErrorMsg');

            errorBlock.classList.add('hidden');

            if (pass !== confirmPass) {
                e.preventDefault();
                errorMsg.innerText = "Passwords do not match. Please re-enter.";
                errorBlock.classList.remove('hidden');
                return false;
            }

            const btn = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');
            
            btn.disabled = true;
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            text.innerText = 'Updating Security Profile...';
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>