<?php
$label ??= '';
$name ??= '';
$placeholder ??= '';
$componentOld = $componentOld ?? [];
$componentErrors = $componentErrors ?? [];

$value = '';
$error = $componentErrors[$name] ?? '';
?>

<div class="mb-4">
    <label for="<?= $name ?>" class="block mb-1 text-sm font-semibold text-gray-800">
        <?= htmlspecialchars($label) ?>
    </label>

    <div class="relative">
        <input
            id="<?= $name ?>"
            type="password"
            name="<?= $name ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            value="<?= htmlspecialchars($value) ?>"
            class="w-full px-4 py-3 rounded-lg border text-sm
                <?= $error
                    ? 'border-red-500 focus:ring-red-500'
                    : 'border-gray-300 focus:ring-green-600'
                ?>
                focus:outline-none focus:ring-2"
        >

        <button
            type="button"
            onclick="togglePassword('<?= $name ?>', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-green-600"
        >
            👁
        </button>
    </div>

    <?php if ($error): ?>
        <p class="mt-1 text-sm text-red-600">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
</div>