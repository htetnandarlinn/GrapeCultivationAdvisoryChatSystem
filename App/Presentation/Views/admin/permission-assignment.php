<?php
$activePage = 'roles';
$allPermissions = $allPermissions ?? [];
$permissionGroups = $permissionGroups ?? [];
$assignedIds = $assignedIds ?? [];
$role = $role ?? null;
$groupId = 0;
?>
<main class="max-w-7xl mx-auto px-4 sm:px-8 pb-8 pt-10 space-y-6 animate__animated animate__fadeIn">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Permission Management</h1>
            <p class="text-sm text-slate-500">Configure permissions for <strong><?= htmlspecialchars($role?->getLabel() ?? '') ?></strong> role.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/roles" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
    </div>

    <?php if (!empty($_SESSION['role_message'])): ?>
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-700">
            <?= htmlspecialchars($_SESSION['role_message']) ?>
            <?php unset($_SESSION['role_message']); ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/roles/permissions/update" method="POST">
        <input type="hidden" name="role_id" value="<?= $role?->getId() ?>">

        <?php if (empty($allPermissions)): ?>
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-12 text-center">
                <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281zM15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-600 mb-3">No permissions registered yet.</p>
                <a href="<?= BASE_URL ?>/admin/permissions/sync" class="inline-flex items-center gap-2 bg-[#15803D] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-green-900 transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Sync Permissions from Code
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-50">
                    <?php foreach ($permissionGroups as $group): $gid = 'g-' . $groupId++; ?>
                        <div class="p-6" data-group="<?= $gid ?>">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-xs font-bold uppercase tracking-widest text-slate-400"><?= htmlspecialchars($group['label']) ?></h2>
                                <label class="flex items-center gap-2 text-[10px] font-semibold text-slate-500 cursor-pointer hover:text-slate-700 transition">
                                    <input type="checkbox" class="group-select-all rounded border-slate-300 text-[#15803D] focus:ring-[#15803D]"
                                        data-group="<?= $gid ?>">
                                    Select All
                                </label>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <?php foreach ($group['permissions'] as $perm): ?>
                                    <label class="flex items-start gap-3 p-3.5 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition">
                                        <input type="checkbox" name="permissions[]" value="<?= $perm->getId() ?>"
                                            class="group-checkbox-<?= $gid ?> mt-0.5 rounded border-slate-300 text-[#15803D] focus:ring-[#15803D]"
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
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="inline-flex items-center gap-2 bg-[#15803D] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-green-900 transition shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Save Permissions
                </button>
                <a href="<?= BASE_URL ?>/admin/permissions/sync" class="inline-flex items-center gap-2 bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                    Sync New Permissions
                </a>
            </div>
        <?php endif; ?>
    </form>
</main>

<script>
document.querySelectorAll('.group-select-all').forEach(function(master) {
    master.addEventListener('change', function() {
        var gid = this.dataset.group;
        document.querySelectorAll('.group-checkbox-' + gid).forEach(function(cb) {
            cb.checked = master.checked;
        });
    });
});
</script>
