<?php

$label ??= '';
$name ??= '';
$type ??= 'text';
$placeholder ??= '';
$maxlength ??= null;
$icon ??= '';

$componentOld = $componentOld ?? [];
$componentErrors = $componentErrors ?? [];

$value = $componentOld[$name] ?? '';
$error = $componentErrors[$name] ?? null;
$inputId = htmlspecialchars($name);

if (!isset($GLOBALS['__field_equalizer_emitted'])) {
    $GLOBALS['__field_equalizer_emitted'] = false;
}

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
            name="<?= $inputId ?>"
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

            class="w-full <?= $icon ? 'pl-10' : 'px-4' ?> pr-4 py-2.5 rounded-xl border bg-slate-50/60 text-sm text-slate-800
                placeholder:text-slate-400 transition-all duration-200
                focus:outline-none focus:bg-white focus:ring-4 focus:ring-[#15803D]/15 focus:border-[#15803D]
                hover:border-[#15803D]/60
                <?= $error ? 'border-red-400 ring-2 ring-red-100' : 'border-slate-200' ?>"
        >
    </div>

    <div class="field-error mt-1" style="min-height:20px;">
    <?php if ($error): ?>
        <p class="text-xs text-red-600 flex items-center gap-1 leading-tight">
            <i class="fa-solid fa-circle-exclamation text-[10px]"></i><?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    </div>
</div>

<?php if (!$GLOBALS['__field_equalizer_emitted']): ?>
<?php $GLOBALS['__field_equalizer_emitted'] = true; ?>
<script>
(function(){
    function equalizeFields(){
        document.querySelectorAll('.grid').forEach(function(grid){
            var cols = Array.from(grid.children).filter(function(child){
                return child.querySelectorAll('.field').length > 0;
            });
            if(cols.length < 2) return;
            var colA = cols[0].querySelectorAll('.field');
            var colB = cols[1].querySelectorAll('.field');
            var count = Math.min(colA.length, colB.length);
            for(var i = 0; i < count; i++){
                colA[i].style.removeProperty('min-height');
                colB[i].style.removeProperty('min-height');
                var hA = colA[i].offsetHeight;
                var hB = colB[i].offsetHeight;
                var max = Math.max(hA, hB);
                colA[i].style.minHeight = max + 'px';
                colB[i].style.minHeight = max + 'px';
            }
        });
    }
    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', equalizeFields);
    } else {
        equalizeFields();
    }
    window.addEventListener('resize', equalizeFields);
})();
</script>
<?php endif; ?>
