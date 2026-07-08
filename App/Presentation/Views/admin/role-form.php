<?php
$activePage = 'roles';
$role = $role ?? [];
$mode = $mode ?? 'create';
$formAction = $formAction ?? '/admin/roles/store';
$submitLabel = $submitLabel ?? 'Save';

$oldName = $_SESSION['old']['name'] ?? (method_exists($role, 'getLabel') ? $role->getLabel() : '');
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['old'], $_SESSION['errors']);
?>
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6">
    <div class="flex items-center gap-4 mb-2">
        <a href="<?= BASE_URL ?>/admin/roles" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-[#15803D] hover:border-[#15803D] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-black text-slate-900"><?= $mode === 'create' ? 'New Role' : 'Edit Role' ?></h1>
            <p class="text-sm text-slate-500"><?= $mode === 'create' ? 'Add a new role to the platform.' : 'Update the role details.' ?></p>
        </div>
    </div>

    <div class="max-w-lg">
        <form action="<?= BASE_URL . htmlspecialchars($formAction) ?>" method="POST" class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm space-y-5">
            <?php if ($mode === 'edit'): ?>
                <input type="hidden" name="id" value="<?= (int) $role->getId() ?>">
            <?php endif; ?>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($oldName) ?>" placeholder="e.g. Manager"
                       class="w-full rounded-xl border <?= isset($errors['name']) ? 'border-red-400' : 'border-slate-200' ?> bg-white px-4 py-2.5 text-sm text-slate-900 outline-none focus:border-[#15803D] focus:ring-2 focus:ring-[#15803D]/20 transition">
                <?php if (isset($errors['name'])): ?>
                    <p class="mt-1 text-xs text-red-500"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit" class="bg-[#15803D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-green-900 transition"><?= htmlspecialchars($submitLabel) ?></button>
                <a href="<?= BASE_URL ?>/admin/roles" class="text-sm text-slate-500 hover:text-slate-700 transition">Cancel</a>
            </div>
        </form>
    </div>
</main>
