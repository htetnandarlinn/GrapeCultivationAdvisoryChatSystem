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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root { --brand: #15803D; --brand-dark: #0d331a; }
        body { font-family: 'Inter', sans-serif; }
        .auth-bg {
            background:
                radial-gradient(1200px 600px at 15% -10%, rgba(34,197,94,0.18), transparent 60%),
                radial-gradient(900px 500px at 110% 120%, rgba(21,128,61,0.22), transparent 55%),
                linear-gradient(135deg, #f0fdf4 0%, #ffffff 45%, #ecfdf5 100%);
        }
        .brand-panel {
            background:
                radial-gradient(700px 400px at 80% 0%, rgba(74,222,128,0.35), transparent 60%),
                linear-gradient(160deg, #166534 0%, #15803D 45%, #0d331a 100%);
        }
        /* Floating decorative shapes */
        .float-shape { position: absolute; border-radius: 9999px; filter: blur(0.5px); opacity: 0.25; animation: floaty 9s ease-in-out infinite; }
        @keyframes floaty { 0%,100% { transform: translateY(0) translateX(0) rotate(0deg); } 50% { transform: translateY(-22px) translateX(10px) rotate(8deg); } }
        .float-2 { animation-delay: 1.5s; animation-duration: 11s; }
        .float-3 { animation-delay: 3s; animation-duration: 13s; }
        .float-4 { animation-delay: 2s; animation-duration: 10s; }

        /* Entrance animations */
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(26px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeSlideRight { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes popIn { from { opacity: 0; transform: scale(0.92); } to { opacity: 1; transform: scale(1); } }
        @keyframes glowPulse { 0%,100% { box-shadow: 0 0 0 0 rgba(21,128,61,0.0);} 50% { box-shadow: 0 10px 40px -10px rgba(21,128,61,0.45);} }
        .anim-up { animation: fadeSlideUp 0.7s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-right { animation: fadeSlideRight 0.8s cubic-bezier(0.16,1,0.3,1) both; }
        .anim-pop { animation: popIn 0.6s cubic-bezier(0.16,1,0.3,1) both; }
        .delay-1 { animation-delay: 0.08s; } .delay-2 { animation-delay: 0.16s; }
        .delay-3 { animation-delay: 0.24s; } .delay-4 { animation-delay: 0.32s; }
        .delay-5 { animation-delay: 0.40s; }

        .brand-glow { animation: glowPulse 5s ease-in-out infinite; }

        /* Form input polish */
        .field input {
            transition: border-color .25s ease, box-shadow .25s ease, background-color .25s ease, transform .15s ease;
        }
        .field input:focus { transform: translateY(-1px); }
        .login-btn {
            background: linear-gradient(135deg, #15803D 0%, #166534 100%);
            box-shadow: 0 10px 30px -10px rgba(21,128,61,0.6);
            transition: transform .2s ease, box-shadow .25s ease, filter .2s ease;
        }
        .login-btn:hover { transform: translateY(-2px); box-shadow: 0 16px 40px -12px rgba(21,128,61,0.7); filter: brightness(1.05); }
        .login-btn:active { transform: translateY(0) scale(0.985); }

        .grape-spin { animation: grapeSpin 18s linear infinite; }
        @keyframes grapeSpin { to { transform: rotate(360deg); } }

        .link-underline { background-image: linear-gradient(currentColor, currentColor); background-size: 0% 1.5px; background-repeat: no-repeat; background-position: 0 100%; transition: background-size .3s ease; }
        .link-underline:hover { background-size: 100% 1.5px; }

        @media (prefers-reduced-motion: reduce) {
            * { animation: none !important; transition: none !important; }
        }
    </style>
</head>

<body class="auth-bg flex flex-col min-h-screen antialiased selection:bg-green-200">

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<main class="flex-grow w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 px-5 sm:px-8 py-10 lg:py-14 items-center">

    <!-- LEFT: Brand Panel -->
    <section class="brand-panel relative hidden lg:flex flex-col justify-between overflow-hidden rounded-[2rem] p-10 xl:p-12 text-white min-h-[560px] anim-right brand-glow">
        <!-- floating shapes -->
        <span class="float-shape w-28 h-28 bg-white/20 top-10 left-8"></span>
        <span class="float-shape float-2 w-20 h-20 bg-lime-300/30 bottom-24 left-16"></span>
        <span class="float-shape float-3 w-40 h-40 bg-emerald-300/15 -right-10 top-1/3"></span>
        <span class="float-shape float-4 w-16 h-16 bg-white/15 bottom-10 right-20"></span>

        <div class="relative z-10 anim-up">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white/15 backdrop-blur flex items-center justify-center text-2xl grape-spin">🍇</div>
                <span class="text-sm font-bold tracking-wide">Grape Advisory</span>
            </div>
        </div>

        <div class="relative z-10 space-y-5 anim-up delay-2">
            <h1 class="text-4xl xl:text-5xl font-black leading-[1.1] tracking-tight">
                Welcome<br>Back, <span class="text-lime-300">Grower.</span>
            </h1>
            <p class="text-emerald-50/80 text-sm xl:text-base max-w-md leading-relaxed">
                Sign in to continue your smart-farming journey — get expert cultivation guidance, disease management, and real-time support for healthier grapes.
            </p>

            <ul class="space-y-3 pt-2">
                <?php foreach ([
                    ['icon' => 'fa-solid fa-leaf', 'text' => 'Expert disease & pest diagnosis'],
                    ['icon' => 'fa-solid fa-comments', 'text' => 'Live chat with agronomists'],
                    ['icon' => 'fa-solid fa-chart-line', 'text' => 'Boost your yield with data'],
                ] as $f): ?>
                <li class="flex items-center gap-3 text-sm text-emerald-50/90 anim-up delay-3">
                    <span class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center"><i class="<?= $f['icon'] ?> text-lime-200"></i></span>
                    <?= htmlspecialchars($f['text']) ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="relative z-10 flex items-center gap-2 text-xs text-emerald-100/70 anim-up delay-4">
            <i class="fa-solid fa-shield-halved"></i> Your farm data stays private & secure.
        </div>
    </section>

    <!-- RIGHT: Login Form -->
    <section class="w-full flex justify-center anim-up delay-1">
        <div class="bg-white/90 backdrop-blur-xl p-7 sm:p-10 rounded-[2rem] shadow-2xl border border-white w-full max-w-xl transition-all duration-300 hover:shadow-[0_30px_80px_-30px_rgba(21,128,61,0.45)]">

            <div class="lg:hidden flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#15803D] to-[#166534] flex items-center justify-center text-xl">🍇</div>
                <span class="font-extrabold text-slate-800">Grape Advisory</span>
            </div>

            <div class="text-center pb-1 anim-up delay-2">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Sign In</h2>
                <p class="text-slate-400 text-sm mt-1">Access your cultivation dashboard</p>
            </div>

            <?php $emailVerificationError = $componentErrors['email'] ?? null; ?>
            <?php if (!empty($emailVerificationError)): ?>
                <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 anim-pop" role="alert">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i><?= htmlspecialchars($emailVerificationError, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/login" method="POST" novalidate class="space-y-5 mt-6">

                <div class="field anim-up delay-2">
                    <?php
                    $label = 'Username or Email';
                    $name = 'username_or_email';
                    $type = 'text';
                    $placeholder = 'Enter your username or email';
                    $errors = $componentErrors;
                    $old = $componentOld;
                    include __DIR__ . '/../components/input.php';
                    ?>
                </div>

                <div class="field anim-up delay-3">
                    <?php
                    $label = 'Password';
                    $name = 'password';
                    $placeholder = 'Enter your password';
                    $errors = $componentErrors;
                    $old = $componentOld;
                    include __DIR__ . '/../components/password-input.php';
                    ?>
                </div>

                <div class="flex items-center justify-between text-sm anim-up delay-3">
                    <label class="flex items-center gap-2 text-slate-600 cursor-pointer group select-none">
                        <input type="checkbox" name="remember_me" value="1"
                               class="w-4 h-4 rounded border-slate-300 text-[#15803D] focus:ring-[#15803D] accent-[#15803D] transition-all cursor-pointer">
                        <span class="group-hover:text-slate-900 transition-colors font-medium">Remember Me</span>
                    </label>
                    <a href="<?= BASE_URL ?>/forgot-password" class="text-[#15803D] hover:text-green-700 font-bold link-underline transition-all">
                        Forgot Password?
                    </a>
                </div>

                <div class="pt-1 anim-up delay-4">
                    <button type="submit" class="login-btn w-full py-3.5 rounded-xl text-white font-bold text-base flex items-center justify-center gap-2">
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
            <a href="<?= BASE_URL ?>/auth/google" class="flex items-center justify-center gap-3 w-full px-4 py-3 border border-slate-300 rounded-xl text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 hover:border-slate-400 transition-all active:scale-[0.99]">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </a>
            <?php endif; ?>

            <p class="text-center mt-6 text-sm text-slate-500 font-medium anim-up delay-5">
                Don't have an account?
                <a href="<?= BASE_URL ?>/register" class="text-[#15803D] font-bold link-underline hover:text-green-700 transition-all ml-1">Sign Up</a>
            </p>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
