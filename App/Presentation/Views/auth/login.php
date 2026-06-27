<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Grape Cultivation Advisory Chat System</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>

<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">

    <div class="hero-left">

        <h1>
            Welcome Back!<br>
            Let's Grow Better
            <span class="accent">Grapes</span>
        </h1>

        <hr class="divider">

        <p>
            Log in to continue your smart farming journey
            and receive cultivation guidance, disease management
            recommendations, and farming support.
        </p>

    </div>

    <div class="login-wrapper">

        <!-- Success Message -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form
            action="<?= BASE_URL ?>/index.php?page=login&action=authenticate"
            method="POST"
            class="login-card"
            novalidate
        >

            <h2>Login to Your Account</h2>

            <p class="subtitle">
                Welcome back! Please login to continue.
            </p>

            <!-- Username or Email -->
            <div class="form-group">

                <label for="username_or_email">
                    Username or Email
                </label>

                <input
                    id="username_or_email"
                    type="text"
                    name="username_or_email"
                    placeholder="Enter your username or email"
                    value="<?= htmlspecialchars($old['username_or_email'] ?? '') ?>"
                    class="<?= isset($errors['username_or_email']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['username_or_email'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['username_or_email']) ?></small>
                <?php endif; ?>

            </div>

            <!-- Password -->
            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    class="<?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                    
                >
                <?php if (isset($errors['password'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['password']) ?></small>
                <?php endif; ?>

            </div>

            <div class="row-between">

                <label class="remember-me">
                    <input
                        type="checkbox"
                        name="remember_me"
                        value="1"
                    >
                    Remember Me
                </label>

                <a href="<?= BASE_URL ?>/forgot-password" class="forgot-link">
                    Forgot Password?
                </a>

            </div>

            <button type="submit" class="btn-submit">
                Login
            </button>

        </form>

        <p class="signup-link">
            Don't have an account?
            <a href="<?= BASE_URL ?>/index.php?page=register">
                Sign Up
            </a>
        </p>

    </div>

</section>
</body>
</html>