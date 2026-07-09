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
<main class="max-w-7xl mx-auto px-4 sm:px-8 pb-8 pt-10 space-y-6 animate__animated animate__fadeIn">
    <!-- Centered Header -->
    <div class="max-w-lg mx-auto">
        <h1 class="text-xl font-black text-slate-900"><?= $mode === 'create' ? 'Create' : 'Edit' ?> Role</h1>
        <p class="text-sm text-slate-500"><?= $mode === 'create' ? 'Add a new role under the system category.' : 'Update the role name.' ?></p>
    </div>

    <!-- Centered Form Card -->
    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm max-w-lg mx-auto">
        <form action="<?= BASE_URL . $formAction ?>" method="POST">
            <?php if ($mode === 'edit' && $role): ?>
                <input type="hidden" name="id" value="<?= $role->getId() ?>">
            <?php endif; ?>

            <div class="mb-6">
                <label for="name" class="block text-[10px] uppercase font-black text-slate-400 tracking-widest mb-2">Role Name</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($label) ?>"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-4 focus:ring-emerald-50 focus:border-[#15803D] outline-none transition <?= isset($errors['name']) ? 'border-red-400' : '' ?>"
                    placeholder="e.g. Administrator">
                
                <?php if (isset($errors['name'])): ?>
                    <p class="text-xs text-red-500 mt-2 font-medium"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="flex gap-3">
                <a href="<?= BASE_URL ?>/admin/roles"
                    class="flex-1 text-center px-5 py-3 rounded-xl text-xs font-bold border border-slate-200 text-slate-600 hover:bg-slate-50 transition">Cancel</a>
                <button type="submit"
                    class="flex-1 px-5 py-3 rounded-xl text-xs font-bold bg-[#15803D] text-white hover:bg-green-900 transition shadow-sm"><?= $submitLabel ?></button>
            </div>
        </form>
    </div>
</main>
