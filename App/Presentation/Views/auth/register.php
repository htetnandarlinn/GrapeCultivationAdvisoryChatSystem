<?php
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
$success = $_SESSION['success'] ?? '';

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
    <title>Register | Grape Cultivation Advisory Chat System</title>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>

<body class="bg-gradient-to-br from-green-50/60 via-slate-50 to-emerald-50/40 flex flex-col min-h-screen antialiased selection:bg-green-200">

<!-- MAIN LAYOUT CONTAINER: Alignment updated to items-start for seamless matching with login layout -->
<main class="flex-grow w-fvar_dumpll max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 px-6 py-12 lg:py-16 items-start">
    
    <!-- LEFT SIDE: Text Column -->
    <div class="lg:col-span-5 space-y-4 text-left lg:pr-4 pt-2 lg:pt-6 animate-fade-in-up">
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight leading-[1.15]">
            Grape Cultivation Advisory <br> for Healthy <span class="text-green-700">Chat System</span>
        </h1>
        <p class="text-gray-600 text-sm sm:text-base max-w-xl leading-relaxed">
            Get expert advice, disease solutions, and cultivation tips to grow better grapes and increase your yield.
        </p>
    </div>

    <!-- RIGHT SIDE: Registration Form Module Column -->
    <div class="lg:col-span-7 w-full flex justify-center lg:justify-end animate-fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- Form Card Structure -->
        <div class="bg-white p-6 sm:p-10 rounded-2xl shadow-xl border border-gray-100 max-w-2xl w-full transition-all duration-300 hover:shadow-2xl">

            <?php include __DIR__ . '/../components/flash.php'; ?>

            <form action="<?= BASE_URL ?>/register" method="POST" novalidate class="space-y-5">

                <div class="text-center pb-2">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Create Your Account</h2>
                   
                </div>

                <!-- Two-column grid layout engine -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-2">
                    
                    <!-- LEFT CONTAINER ROW FIELDS -->
                    <div class="space-y-2">
                        <?php
                        $label = 'Username';
                        $name = 'username';
                        $placeholder = 'Enter username';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/input.php';
                        ?>

                        <?php
                        $label = 'Address';
                        $name = 'address';
                        $placeholder = 'Address';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/input.php';
                        ?>

                        <?php
                        $label = 'Password';
                        $name = 'password';
                        $placeholder = 'Enter password';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/password-input.php';
                        ?>

                    </div>

                    <!-- RIGHT CONTAINER ROW FIELDS -->
                    <div class="space-y-2">

                        <?php
                        $label = 'Email Address';
                        $name = 'email';
                        $type = 'email';
                        $placeholder = 'Enter email';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/input.php';
                        ?>

                        <?php
                        $label = 'Phone';
                        $name = 'phone';
                        $placeholder = 'Phone number';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/input.php';
                        ?>

                        <?php
                        $label = 'Confirm Password';
                        $name = 'confirm_password';
                        $placeholder = 'Confirm password';
                        $errors = $componentErrors; $old = $componentOld;
                        include __DIR__ . '/../components/password-input.php';
                        ?>
                    </div>
                    
                </div>

                <!-- Full-width Drop Selector -->
                <div class="mt-3">
                    <?php
                    $label = 'Role';
                    $name = 'role';
                    $options = [
                        '' => 'Choose role',
                        'farmer' => 'Farmer',
                        'expert' => 'Expert',
                        'admin' => 'Admin'
                    ];
                    $errors = $componentErrors; $old = $componentOld;
                    include __DIR__ . '/../components/select.php';
                    ?>
                </div>

                <!-- Sign-up Button Container -->
                <div class="pt-2 transition-transform duration-200 active:scale-[0.98]">
                    <?php
                    $text = 'Sign Up';
                    include __DIR__ . '/../components/button.php';
                    ?>
                </div>

            </form>

            <p class="text-center mt-6 text-xs sm:text-sm text-slate-500 font-medium">
                Already have an account?
                <a href="<?= BASE_URL ?>/login" class="text-green-700 font-bold hover:text-green-600 hover:underline transition-colors ml-1">Login</a>
            </p>

        </div>
    </div>
</main>

</body>
</html>