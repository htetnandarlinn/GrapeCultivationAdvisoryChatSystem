<?php
$activePage = 'roles';
$roles = $roles ?? [];
?>
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Role Management</h1>
            <p class="text-sm text-slate-500">Manage user roles under the USER_TYPE category.</p>
        </div>
        <div class="flex gap-2">
            <a href="<?= BASE_URL ?>/admin/permissions/sync" class="bg-slate-100 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Sync Permissions</a>
            <?php if (can('admin.roles.create')): ?>
                <a href="<?= BASE_URL ?>/admin/roles/create" class="bg-[#15803D] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-green-900 transition">+ Add New Role</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-700">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-3xl p-6 border border-white/50 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] uppercase tracking-widest text-slate-400 border-b border-slate-50">
                        <th class="pb-4 pl-2">ID</th>
                        <th class="pb-4">Name</th>
                        <th class="pb-4 text-right pr-2">Permissions</th>
                        <th class="pb-4 text-right pr-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-700">
                        <?php if (empty($roles)): ?>
                            <tr class="border-b border-slate-50 last:border-0">
                                <td colspan="4" class="py-8 text-center text-slate-400">No roles found.</td>
                            </tr>
                        <?php else: ?>
                            <?php $i = 1; ?>
                            <?php foreach ($roles as $role): ?>
                                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition">
                                    <td class="py-5 pl-2 font-bold text-slate-900"><?= $i++ ?></td>
                                    <td class="py-5 font-semibold text-slate-900"><?= htmlspecialchars($role->getLabel()) ?></td>
                                    <td class="py-5 text-right pr-2">
                                        <div class="flex justify-end">
                                            <?php if (can('admin.permissions.assign')): ?>
                                                <a href="<?= BASE_URL ?>/admin/roles/permissions?role_id=<?= $role->getId() ?>" class="text-xs font-bold text-amber-600 hover:text-amber-700 transition">Manage</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-5 text-right pr-2">
                                        <div class="flex justify-end gap-3">
                                            <?php if (can('admin.roles.edit')): ?>
                                                <a href="<?= BASE_URL ?>/admin/roles/edit?id=<?= urlencode((string) $role->getId()) ?>" class="text-slate-400 hover:text-amber-600 transition" title="Edit role">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (can('admin.roles.delete')): ?>
                                                <form action="<?= BASE_URL ?>/admin/roles/delete" method="POST" class="inline-block" onsubmit="return confirm('Delete this role?');">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($role->getId()) ?>">
                                                    <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Delete role">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
