<?php
$activePage = 'users';
$tab = $_GET['tab'] ?? 'all';
?>
<main class="flex-grow px-4 sm:px-8 pb-8 pt-28 space-y-6 animate__animated animate__fadeIn">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl font-black text-slate-900">User Management</h1>
            <p class="text-sm text-slate-500">Manage all farmers and expert accounts.</p>
        </div>
    </div>

    <?php if (!empty($_SESSION['admin_message'])): ?>
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4 text-sm text-emerald-700">
            <?= htmlspecialchars($_SESSION['admin_message']) ?>
        </div>
    <?php unset($_SESSION['admin_message']); endif; ?>

    <div class="flex gap-1 border-b border-slate-200">
        <a href="?tab=all"
           class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition <?= $tab === 'all' ? 'bg-white text-[#15803D] border border-b-0 border-slate-200 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' ?>">
            All Users (<?= $farmerCount + $expertCount ?>)
        </a>
        <a href="?tab=farmers"
           class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition <?= $tab === 'farmers' ? 'bg-white text-[#15803D] border border-b-0 border-slate-200 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' ?>">
            Farmers (<?= $farmerCount ?>)
        </a>
        <a href="?tab=experts"
           class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition <?= $tab === 'experts' ? 'bg-white text-[#15803D] border border-b-0 border-slate-200 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' ?>">
            Experts (<?= $expertCount ?>)
        </a>
    </div>

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
                    <?php
                    $users = match ($tab) {
                        'farmers' => $farmers,
                        'experts' => $experts,
                        default => $allUsers,
                    };
                    ?>
                    <?php if (empty($users)): ?>
                        <tr class="border-b border-slate-50 last:border-0">
                            <td colspan="7" class="py-8 text-center text-slate-400">No users found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <?php
                            $roleColors = [
                                'farmer' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'expert' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'admin' => 'bg-purple-50 text-purple-700 border-purple-200',
                            ];
                            $roleVal = $user->getType()->getValue();
                            $colorClass = $roleColors[$roleVal] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                            $uid = $user->getId();
                            ?>
                            <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50/50 transition">
                                <td class="py-5 pl-2 font-bold text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                            <?php if (!empty($user->getProfileImage())): ?>
                                                <img src="<?= BASE_URL . htmlspecialchars($user->getProfileImage()) ?>" alt="<?= htmlspecialchars($user->getUsername()) ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <span class="text-xs font-semibold text-slate-500"><?= htmlspecialchars(strtoupper(substr($user->getUsername(), 0, 1))) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900"><?= htmlspecialchars($user->getUsername()) ?></div>
                                            <div class="text-[11px] text-slate-500"><?= htmlspecialchars($user->getAddress() ?: 'No address provided') ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5"><?= htmlspecialchars($user->getEmail()->getValue()) ?></td>
                                <td class="py-5"><?= htmlspecialchars($user->getPhoneNumber()) ?></td>
                                <td class="py-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border <?= $colorClass ?> capitalize">
                                        <?= htmlspecialchars($roleVal) ?>
                                    </span>
                                </td>
                                <td class="py-5 text-slate-600"><?= htmlspecialchars($user->getCreatedAt()->format('F d, Y')) ?></td>
                                <td class="py-5">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-1.5 h-1.5 rounded-full <?= $user->getStatus()->isActive() ? 'bg-emerald-500' : ($user->getStatus()->isBlocked() ? 'bg-red-500' : 'bg-yellow-500') ?>"></div>
                                        <span class="font-bold <?= $user->getStatus()->isActive() ? 'text-emerald-600' : ($user->getStatus()->isBlocked() ? 'text-red-600' : 'text-amber-600') ?>"><?= htmlspecialchars(ucfirst($user->getStatus()->getValue())) ?></span>
                                    </div>
                                </td>
                                <td class="py-5 text-right pr-2">
                                    <button onclick="openRoleModal(<?= $uid ?>, '<?= htmlspecialchars($roleVal) ?>')" class="bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-amber-100 transition">
                                        Manage
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="roleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl border border-white/50 mx-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-black text-slate-900">Change User Role</h2>
            <button onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="<?= BASE_URL ?>/admin/users/role" method="POST">
            <input type="hidden" name="user_id" id="modalUserId" value="">
            <div class="space-y-3">
                <?php foreach ($roles as $r): ?>
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 has-[:checked]:border-amber-400 has-[:checked]:bg-amber-50/50 cursor-pointer hover:border-slate-300 transition">
                        <input type="radio" name="role_code" value="<?= $r->getCode() ?>" class="accent-amber-500 w-4 h-4 flex-shrink-0">
                        <div>
                            <div class="text-sm font-bold text-slate-900"><?= htmlspecialchars($r->getLabel()) ?></div>
                            <div class="text-[11px] text-slate-500"><?= htmlspecialchars($r->getCode()) ?></div>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 bg-[#15803D] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-green-900 transition">Save Changes</button>
                <button type="button" onclick="closeRoleModal()" class="flex-1 border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRoleModal(userId, currentRole) {
    document.getElementById('modalUserId').value = userId;
    document.querySelectorAll('#roleModal input[name="role_code"]').forEach(r => {
        r.checked = r.value === currentRole;
    });
    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.getElementById('roleModal').classList.remove('flex');
}

document.getElementById('roleModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeRoleModal();
});
</script>
