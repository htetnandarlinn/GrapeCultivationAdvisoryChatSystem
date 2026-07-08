<?php
$activePage = 'roles';
$allPermissions = $allPermissions ?? [];
$permissionGroups = $permissionGroups ?? [];
$assignedIds = $assignedIds ?? [];
$role = $role ?? null;
?>
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Permissions for "<?= htmlspecialchars($role?->getLabel() ?? '') ?>"</h1>
            <p class="text-sm text-slate-500">Assign or revoke permissions for this user role.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/roles" class="text-slate-400 hover:text-slate-600 transition text-sm font-semibold">&larr; Back to Roles</a>
    </div>

    <form action="<?= BASE_URL ?>/admin/roles/permissions/update" method="POST" class="bg-white rounded-3xl p-6 border border-white/50 shadow-sm space-y-6">
        <input type="hidden" name="role_id" value="<?= $role?->getId() ?>">

        <?php if (empty($allPermissions)): ?>
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm">No permissions registered yet.</p>
                <a href="<?= BASE_URL ?>/admin/permissions/sync" class="inline-block mt-3 bg-[#15803D] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-green-900 transition">Sync Permissions from Code</a>
            </div>
        <?php else: ?>
            <?php foreach ($permissionGroups as $group): ?>
                <div class="space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400 border-b border-slate-100 pb-2"><?= htmlspecialchars($group['label']) ?></h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        <?php foreach ($group['permissions'] as $perm): ?>
                            <label class="flex items-start gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition">
                                <input type="checkbox" name="permissions[]" value="<?= $perm->getId() ?>"
                                    class="mt-0.5 rounded border-slate-300 text-[#15803D] focus:ring-[#15803D]"
                                    <?= in_array($perm->getId(), $assignedIds, true) ? 'checked' : '' ?>>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($perm->getName()) ?></p>
                                    <p class="text-xs text-slate-400 font-mono mt-0.5"><?= htmlspecialchars($perm->getKey()) ?></p>
                                    <?php if ($perm->getDescription() !== ''): ?>
                                        <p class="text-xs text-slate-500 mt-0.5"><?= htmlspecialchars($perm->getDescription()) ?></p>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-[#15803D] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-green-900 transition">Save Permissions</button>
                <a href="<?= BASE_URL ?>/admin/permissions/sync" class="bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Sync New Permissions</a>
            </div>
        <?php endif; ?>
    </form>
</main>
