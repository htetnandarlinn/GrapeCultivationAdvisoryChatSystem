<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Grape Cultivation Advisory Chat System</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>

<body>

<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="hero">

    <div class="hero-left">

        <h1>
            Grape Cultivation Advisory <br>
            for Healthy
            <span class="accent">Chat System</span>
        </h1>

        <hr class="divider">

        <p>
            Get expert advice, disease solutions,
            and cultivation tips to grow better grapes
            and increase your yield.
        </p>

        <div class="grape-illustration">
            <div class="circle-bg"></div>
            <!-- SVG unchanged -->
        </div>

    </div>

    <div class="signup-wrapper">

        <!-- Global registration error -->
        <?php if (!empty($errors['registration'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors['registration']) ?>
            </div>
        <?php endif; ?>

        <!-- Success message -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form
            action="<?= BASE_URL ?>/index.php?page=register&action=store"
            method="POST"
            class="signup-card"
            novalidate
        >

            <h2>Create Your Account</h2>
            <p class="subtitle">Fill in the details below to get started.</p>

           <!-- Username -->
            <div class="form-group">
                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    placeholder="Enter your username"
                    value="<?= htmlspecialchars($old['username'] ?? '') ?>"
                    class="<?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['username'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['username']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>Email Address</label>
                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    class="<?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['email']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label>Phone Number</label>
                <input
                    type="text"
                    name="phone"
                    placeholder="Enter phone number"
                    maxlength="11"
                    value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                    class="<?= isset($errors['phone']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['phone'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['phone']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label>Address</label>
                <input
                    type="text"
                    name="address"
                    placeholder="Enter your address"
                    value="<?= htmlspecialchars($old['address'] ?? '') ?>"
                    class="<?= isset($errors['address']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['address'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['address']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="Enter password"
                    class="<?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['password'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['password']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label>Confirm Password</label>
                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Confirm password"
                    class="<?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>"
                >
                <?php if (isset($errors['confirm_password'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['confirm_password']) ?></small>
                <?php endif; ?>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label>Select Role</label>
                <select
                    name="role"
                    class="<?= isset($errors['role']) ? 'is-invalid' : '' ?>"
                >
                    <option value="">Choose Role</option>
                    <option value="farmer" <?= (($old['role'] ?? '') === 'farmer') ? 'selected' : '' ?>>Farmer</option>
                    <option value="expert" <?= (($old['role'] ?? '') === 'expert') ? 'selected' : '' ?>>Expert</option>
                    <option value="admin" <?= (($old['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
                </select>
                <?php if (isset($errors['role'])): ?>
                    <small class="text-danger"><?= htmlspecialchars($errors['role']) ?></small>
                <?php endif; ?>
            </div>

            <button class="btn-signup" type="submit">
                Sign Up
            </button>

        </form>

        <p class="login-link">
            Already have an account?
            <a href="<?= BASE_URL ?>/index.php?page=login">Login</a>
        </p>

    </div>

</section>
</body>
</html>