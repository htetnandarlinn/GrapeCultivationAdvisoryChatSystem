<?php

$errors   = $_SESSION['errors'] ?? [];
$old      = $_SESSION['old'] ?? [];
$success  = $_SESSION['success'] ?? '';

unset($_SESSION['errors'], $_SESSION['old'], $_SESSION['success']);

// IMPORTANT: pass clean variables for components
$componentErrors = $errors;
$componentOld = $old;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Grape Cultivation Advisory Chat System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== ''): ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .field input::placeholder { font-size: 0.75rem; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        .form-card { transition: box-shadow .35s cubic-bezier(0.16,1,0.3,1), transform .35s cubic-bezier(0.16,1,0.3,1); }
        .form-card:hover { transform: translateY(-3px); box-shadow: 0 30px 80px -30px rgba(21,128,61,0.35); }

        .login-btn {
            background: linear-gradient(135deg, #15803D 0%, #166534 100%);
            box-shadow: 0 10px 30px -10px rgba(21,128,61,0.6);
            transition: transform .2s ease, box-shadow .25s ease, filter .2s ease;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 40px -12px rgba(21,128,61,0.7); filter: brightness(1.05); }
        .login-btn:active { transform: translateY(0) scale(0.985); }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-emerald-50/30 flex flex-col min-h-screen antialiased selection:bg-green-200">

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<main class="flex-grow w-full max-w-7xl mx-auto flex items-center justify-center px-6 py-10 lg:py-12">

    <!-- LOGIN FORM -->
        <div class="form-card bg-white p-6 sm:p-8 rounded-[1.75rem] shadow-xl border border-gray-100 max-w-md w-full animate-fade-in-up">

        <form action="<?= BASE_URL ?>/login" method="POST" novalidate class="space-y-3">

            <div class="text-center pb-2">
                <div class="mx-auto mb-4 w-14 h-14 rounded-2xl bg-gradient-to-br from-[#15803D] to-[#166534] flex items-center justify-center text-white text-xl shadow-lg shadow-emerald-600/30">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Account Sign In</h2>
                    <?php $emailVerificationError = $componentErrors['email'] ?? null; ?>
                    <?php if (!empty($emailVerificationError)): ?>
                        <div class="mt-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                            <?= htmlspecialchars($emailVerificationError, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="space-y-1">
                    <?php
                    $label = 'Username or Email';
                    $name = 'username_or_email';
                    $type = 'text';
                    $icon = 'fa-solid fa-user';
                    $placeholder = 'Enter your username or email';
                    $errors = $componentErrors;
                    $old = $componentOld;
                    include __DIR__ . '/../components/input.php';
                    ?>
                </div>

                <div class="space-y-1">
                    <?php
                    $label = 'Password';
                    $name = 'password';
                    $icon = 'fa-solid fa-lock';
                    $placeholder = 'Enter your password';
                    $errors = $componentErrors;
                    $old = $componentOld;
                    include __DIR__ . '/../components/password-input.php';
                    ?>
                </div>

                <div class="flex items-center justify-between pt-1 pb-1 text-sm">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer group select-none">
                        <input type="checkbox" name="remember_me" value="1"
                               class="w-4 h-4 rounded border-slate-300 text-green-700 focus:ring-green-600 focus:ring-2 accent-green-700 transition-all cursor-pointer">
                        <span class="group-hover:text-slate-900 transition-colors font-medium">Remember Me</span>
                    </label>

                    <a href="<?= BASE_URL ?>/forgot-password"
                       class="text-green-700 hover:text-green-600 font-bold transition-colors hover:underline">
                        Forgot Password?
                    </a>
                </div>

                <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== ''): ?>
                <div class="pt-2 flex justify-center">
                    <div class="g-recaptcha" data-sitekey="<?= RECAPTCHA_SITE_KEY ?>"></div>
                </div>

                
                <?php endif; ?>
                <?php if (!empty($componentErrors['recaptcha'])): ?>
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700" role="alert">
                    <?= htmlspecialchars($componentErrors['recaptcha'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php endif; ?>

                <div class="pt-2">
                    <button type="submit" class="group login-btn w-full py-3 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition-transform duration-200 active:scale-[0.98]">
                        <span>Login</span>
                        <i class="fa-solid fa-arrow-right translate-x-0 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

            </form>

            <?php if (defined('GOOGLE_CLIENT_ID') && defined('GOOGLE_CLIENT_SECRET') && GOOGLE_CLIENT_ID !== '' && GOOGLE_CLIENT_SECRET !== ''): ?>
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400 font-medium">OR</span></div>
            </div>
            <a href="<?= BASE_URL ?>/auth/google" class="flex items-center justify-center gap-3 w-full no-underline px-4 py-2.5 border border-slate-300 rounded-md text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </a>
            <?php endif; ?>

            <p class="text-center mt-6 text-sm text-slate-500 font-medium">
                Don't have an account?
                <a href="<?= BASE_URL ?>/register" class="text-green-700 font-bold hover:text-green-600 hover:underline transition-colors ml-1">
                    Sign Up
                </a>
            </p>

        </div>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
