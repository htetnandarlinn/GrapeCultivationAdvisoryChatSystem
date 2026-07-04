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
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>

<!-- Updated body background to matching solid soft green tint from home page -->
<body class="bg-green-50 flex flex-col min-h-screen antialiased selection:bg-green-200">

<!-- MAIN LAYOUT CONTAINER -->
<main class="flex-grow w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-x-16 px-6 py-12 lg:py-16 items-start">
    
    <!-- LEFT SIDE: Text Column -->
    <div class="lg:col-span-5 space-y-4 text-left lg:pr-4 pt-2 lg:pt-6 animate-fade-in-up">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-[1.15]">
            Welcome Back!<br>
            Let's Grow Better <span class="text-green-700">Grapes</span>
        </h1>
        <p class="text-gray-600 text-sm sm:text-base max-w-xl leading-relaxed">
            Log in to continue your smart farming journey and receive cultivation guidance, disease management recommendations, and farming support.
        </p>
    </div>

    <!-- RIGHT SIDE: Login Form Module Column -->
    <div class="lg:col-span-7 w-full flex justify-center lg:justify-end animate-fade-in-up" style="animation-delay: 0.1s;">

        <!-- Form Card Structure -->
        <div class="bg-white p-8 sm:p-12 rounded-2xl shadow-xl border border-gray-100 max-w-xl w-full transition-all duration-300 hover:shadow-2xl">

            <!-- Flash messages component -->
            <?php include __DIR__ . '/../components/flash.php'; ?>

            <form action="<?= BASE_URL ?>/login" method="POST" novalidate class="space-y-6">

                <div class="text-center pb-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Account Sign In</h2>
                </div>

                <!-- Username or Email Field Module -->
                <div class="space-y-1">
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

                <!-- Password Field Module -->
                <div class="space-y-1">
                    <?php
                    $label = 'Password';
                    $name = 'password';
                    $placeholder = 'Enter your password';

                    $errors = $componentErrors;
                    $old = $componentOld;

                    include __DIR__ . '/../components/password-input.php';
                    ?>
                </div>

                <!-- Remember + Forgot Row System -->
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

                <!-- Dynamic Interactive CTA Button Container -->
                <div class="pt-2 transition-transform duration-200 active:scale-[0.98]">
                    <?php
                    $text = 'Login';
                    include __DIR__ . '/../components/button.php';
                    ?>
                </div>

            </form>

            <!-- Footer Link inside the Card -->
            <p class="text-center mt-6 text-sm text-slate-500 font-medium">
                Don't have an account?
                <a href="<?= BASE_URL ?>/register" class="text-green-700 font-bold hover:text-green-600 hover:underline transition-colors ml-1">
                    Sign Up
                </a>
            </p>

        </div>
    </div>
</main>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    if (!input) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '👁‍🗨';
    } else {
        input.type = 'password';
        btn.innerHTML = '👁';
    }
}
</script>

</body>
</html>