<?php

$label ??= '';
$name ??= '';
$type ??= 'text';
$placeholder ??= '';
$maxlength ??= null;

$componentOld = $componentOld ?? [];
$componentErrors = $componentErrors ?? [];

$value = $componentOld[$name] ?? '';
$error = $componentErrors[$name] ?? null;

?>

<div class="mb-4">

    <label
        for="<?= htmlspecialchars($name) ?>"
        class="block mb-2 text-sm font-semibold text-gray-800"
    >
        <?= htmlspecialchars($label) ?>
    </label>

    <input
        id="<?= htmlspecialchars($name) ?>"
        name="<?= htmlspecialchars($name) ?>"
        type="<?= $name === 'phone' ? 'tel' : htmlspecialchars($type) ?>"
        placeholder="<?= htmlspecialchars($placeholder) ?>"
        value="<?= htmlspecialchars($value) ?>"

        <?= $name === 'phone' ? 'maxlength="11"' : ($maxlength ? 'maxlength="' . (int)$maxlength . '"' : '') ?>

        <?= $name === 'phone' ? 'inputmode="numeric"' : '' ?>

        <?= $name === 'phone' ? 'pattern="[0-9]*"' : '' ?>

        <?= $name === 'phone'
            ? 'oninput="this.value=this.value.replace(/\D/g, \'\').slice(0,11)"'
            : '' ?>

        <?= $name === 'phone'
            ? 'onkeypress="return event.charCode >= 48 && event.charCode <= 57"'
            : '' ?>

        <?= $name === 'phone'
            ? 'onpaste="setTimeout(() => { this.value = this.value.replace(/\D/g, \'\').slice(0,11); }, 0)"'
            : '' ?>

        class="w-full px-4 py-3 rounded-lg border text-sm transition-all duration-300 focus:outline-none focus:ring-2
        <?= $error
            ? 'border-red-500 focus:ring-red-500'
            : 'border-gray-300 hover:border-green-500 focus:border-green-600 focus:ring-green-600'
        ?>"
    >

    <?php if ($error): ?>
        <p class="mt-2 text-sm text-red-600">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

</div>