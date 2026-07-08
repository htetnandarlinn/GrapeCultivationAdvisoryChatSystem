<?php
$label ??= '';
$name ??= '';
$options ??= [];

$value = $old[$name] ?? '';
$error = $errors[$name] ?? '';
?>

<div class="mb-4">
    <label for="<?= $name ?>" class="block mb-1 text-sm font-semibold text-gray-800">
        <?= htmlspecialchars($label) ?>
    </label>

    <select
        id="<?= $name ?>"
        name="<?= $name ?>"
        class="w-full px-4 py-3 rounded-lg border text-sm bg-white
            <?= $error
                ? 'border-red-500 focus:ring-red-500'
                : 'border-gray-300 focus:ring-green-600'
            ?>
            focus:outline-none focus:ring-2"
    >
        <?php foreach ($options as $val => $text): ?>
            <option value="<?= $val ?>" <?= $value == $val ? 'selected' : '' ?>>
                <?= $text ?>
            </option>
        <?php endforeach; ?>
    </select>

    <?php if ($error): ?>
        <p class="mt-1 text-sm text-red-600">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
</div>