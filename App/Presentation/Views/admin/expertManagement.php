<?php
/** @var array<\App\Domain\UserManagement\Entities\User> $experts */
$activePage = 'experts';
?>

<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6 animate__animated animate__fadeIn">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">Expert Management</h1>
            <p class="text-sm text-slate-500">Manage expert accounts and review their status.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/experts/create" class="bg-[#15803D] text-white px-5 py-2 rounded-xl text-xs font-bold hover:bg-green-900 transition">+ Add New Expert</a>
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
                        <th class="pb-4 pl-2">Profile</th>
                        <th class="pb-4">Email</th>
                        <th class="pb-4">Phone</th>
                        <th class="pb-4">Role</th>
                        <th class="pb-4">Created</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4 text-right pr-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-700">
                    <?php if (empty($experts)): ?>
                        <tr class="border-b border-slate-50 last:border-0">
                            <td colspan="7" class="py-8 text-center text-slate-400">No experts are registered yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($experts as $expert): ?>
                            <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition">
                                <td class="py-5 pl-2 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                            <?php if (!empty($expert->getProfileImage())): ?>
                                                <img src="<?= BASE_URL . htmlspecialchars($expert->getProfileImage()) ?>" alt="<?= htmlspecialchars($expert->getUsername()) ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span class="text-xs font-semibold text-slate-500"><?= htmlspecialchars(strtoupper(substr($expert->getUsername(), 0, 1))) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900"><?= htmlspecialchars($expert->getUsername()) ?></div>
                                            <div class="text-[11px] text-slate-500"><?= htmlspecialchars($expert->getAddress() ?: 'No address provided') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5"><?= htmlspecialchars($expert->getEmail()->getValue()) ?></td>
                                <td class="py-5"><?= htmlspecialchars($expert->getPhoneNumber()) ?></td>
                                <td class="py-5 text-slate-600 capitalize"><?= htmlspecialchars($expert->getType()->getValue()) ?></td>
                                <td class="py-5 text-slate-600"><?= htmlspecialchars($expert->getCreatedAt()->format('F d, Y')) ?></td>
                                <td class="py-5">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full <?= $expert->getStatus()->isActive() ? 'bg-emerald-500' : ($expert->getStatus()->isBlocked() ? 'bg-red-500' : 'bg-yellow-500') ?>"></div>
                                        <span class="font-bold <?= $expert->getStatus()->isActive() ? 'text-emerald-600' : ($expert->getStatus()->isBlocked() ? 'text-red-600' : 'text-amber-600') ?>"><?= htmlspecialchars(ucfirst($expert->getStatus()->getValue())) ?></span>
                                    </div>
                                </td>
                                <td class="py-5 text-right pr-2">
                                    <div class="flex justify-end gap-3">
                                        <a href="<?= BASE_URL ?>/admin/experts/view?id=<?= urlencode((string) $expert->getId()) ?>" class="text-slate-400 hover:text-[#15803D] transition" title="View expert details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="<?= BASE_URL ?>/admin/experts/edit?id=<?= urlencode((string) $expert->getId()) ?>" class="text-slate-400 hover:text-amber-600 transition" title="Edit expert">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="<?= BASE_URL ?>/admin/experts/delete" method="POST" class="inline-block" onsubmit="return confirm('Delete this expert?');">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($expert->getId()) ?>">
                                            <button type="submit" class="text-slate-400 hover:text-red-600 transition" title="Delete expert">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
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
