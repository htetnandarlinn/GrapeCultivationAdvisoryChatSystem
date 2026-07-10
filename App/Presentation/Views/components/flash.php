<?php if (!empty($errors)): ?>
    <?php $errorMessages = is_array($errors) ? $errors : [$errors]; ?>
    <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
        <?php foreach ($errorMessages as $error): ?>
            <?php if (is_string($error) && $error !== ''): ?>
                <p class="mb-2"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 p-4 text-sm text-green-700">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>