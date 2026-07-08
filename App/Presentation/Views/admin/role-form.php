<?php
$mode = $mode ?? 'create';
$formAction = $formAction ?? '#';
$submitLabel = $submitLabel ?? 'Save';
$role = $role ?? null;

$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

$label = $mode === 'edit' && $role ? $role->getLabel() : ($old['name'] ?? '');
?>
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6">
    <div>
        <h1 class="text-xl font-black text-slate-900"><?= $mode === 'create' ? 'Create' : 'Edit' ?> Role</h1>
        <p class="text-sm text-slate-500"><?= $mode === 'create' ? 'Add a new role under the USER_TYPE category.' : 'Update the role name.' ?></p>
    </div>

    <div class="bg-white rounded-3xl p-6 border border-white/50 shadow-sm max-w-lg">
        <form action="<?= BASE_URL . $formAction ?>" method="POST">
            <?php if ($mode === 'edit' && $role): ?>
                <input type="hidden" name="id" value="<?= $role->getId() ?>">
            <?php endif; ?>

            <div class="mb-4">
                <label for="name" class="block text-xs font-bold text-slate-600 mb-1.5">Role Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($label) ?>"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-amber-200 focus:border-amber-400 outline-none transition <?= isset($errors['name']) ? 'border-red-400' : '' ?>"
                    placeholder="e.g. Sub Admin">
                <?php if (isset($errors['name'])): ?>
                    <p class="text-xs text-red-500 mt-1"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="flex gap-3">
                <a href="<?= BASE_URL ?>/admin/roles"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Cancel</a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl text-xs font-bold bg-[#15803D] text-white hover:bg-green-900 transition"><?= $submitLabel ?></button>
            </div>
        </form>
    </div>
</main>
