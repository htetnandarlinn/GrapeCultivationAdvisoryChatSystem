<?php
$type ??= 'submit';
$text ??= 'Submit';
?>

<button
    type="<?= $type ?>"
    class="w-full py-3 rounded-lg bg-green-700 text-white font-semibold text-lg
           hover:bg-green-800 transition"
>
    <?= htmlspecialchars($text) ?>
</button>