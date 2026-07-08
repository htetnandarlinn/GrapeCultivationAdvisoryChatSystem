<?php if (!empty($errors['registration'])): ?>
    <div class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-red-700">
        <?= htmlspecialchars($errors['registration']) ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-green-700">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>