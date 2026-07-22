<?php
$label ??= '';
$name ??= '';
$placeholder ??= '';
$icon ??= 'fa-solid fa-lock-keyhole';
$componentOld = $componentOld ?? [];
$componentErrors = $componentErrors ?? [];

$value = '';
$error = $componentErrors[$name] ?? '';
$inputId = htmlspecialchars($name);
?>

<div class="field">
    <label for="<?= $inputId ?>" class="block mb-2 text-sm font-semibold text-slate-700">
        <?= htmlspecialchars($label) ?>
    </label>

    <div class="relative group">
        <?php if ($icon): ?>
        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#15803D] transition-colors duration-200">
            <i class="<?= $icon ?>"></i>
        </span>
        <?php endif; ?>

        <input
            id="<?= $inputId ?>"
            type="password"
            name="<?= $inputId ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            value="<?= htmlspecialchars($value) ?>"
            class="w-full <?= $icon ? 'pl-10' : 'px-4' ?> pr-11 py-2.5 rounded-xl border bg-slate-50/60 text-sm text-slate-800
                placeholder:text-slate-400 transition-all duration-200
                focus:outline-none focus:bg-white focus:ring-4 focus:ring-[#15803D]/15 focus:border-[#15803D]
                hover:border-[#15803D]/60
                <?= $error ? 'border-red-400 ring-2 ring-red-100' : 'border-slate-200' ?>"
        >

        <button
            type="button"
            onclick="togglePassword('<?= $inputId ?>', this)"
            class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-[#15803D] hover:bg-[#15803D]/10 active:scale-90 transition-all duration-200"
            aria-label="Toggle password visibility"
        >
            <i class="fa-solid fa-eye text-[18px]"></i>
        </button>
    </div>

    <div class="field-error mt-1" style="min-height:20px;">
    <?php if ($error): ?>
        <p class="text-xs text-red-600 flex items-center gap-1 leading-tight">
            <i class="fa-solid fa-circle-exclamation text-[10px]"></i><?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    </div>
</div>
