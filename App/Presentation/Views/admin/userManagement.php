<?php
$activePage = 'users';
$tab = $_GET['tab'] ?? 'all';
// Ensure counts are defined to avoid undefined variable notices
$farmerCount = $farmerCount ?? (isset($farmers) ? count($farmers) : 0);
$expertCount = $expertCount ?? (isset($experts) ? count($experts) : 0);
$allUsers = $allUsers ?? (isset($allUsers) ? $allUsers : (array_merge(isset($farmers) ? $farmers : [], isset($experts) ? $experts : [])));
$roles = $roles ?? [];

?>
<main class="max-w-7xl mx-auto px-4 sm:px-8 pb-8 pt-10 space-y-6 animate__animated animate__fadeIn">
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
        <?php foreach (['all' => 'All Users', 'farmers' => 'Farmers', 'experts' => 'Experts'] as $key => $label): 
            $count = ($key === 'all') ? ($farmerCount + $expertCount) : ($key === 'farmers' ? $farmerCount : $expertCount);
        ?>
            <a href="?tab=<?= $key ?>"
               class="px-4 py-2.5 text-xs font-bold rounded-t-xl transition <?= $tab === $key ? 'bg-white text-[#15803D] border border-b-0 border-slate-200 shadow-sm' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-50' ?>">
                <?= $label ?> (<?= $count ?>)
            </a>
        <?php endforeach; ?>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] uppercase tracking-widest text-slate-400 border-b border-slate-50 bg-slate-50/50">
                        <th class="py-4 pl-6">Profile</th>
                        <th class="py-4">Email</th>
                        <th class="py-4">Phone</th>
                        <th class="py-4">Role</th>
                        <th class="py-4">Status</th>
                        <th class="py-4 text-right pr-6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-slate-700">
                    <?php 
                    $users = match ($tab) {
                        'farmers' => $farmers ?? [],
                        'experts' => $experts ?? [],
                        default => $allUsers,
                    };
                    ?>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="6" class="py-12 text-center text-slate-400">No users found.</td></tr>
                    <?php else: foreach ($users as $user): 
                        $roleVal = $user->getType()->getValue();
                        $uid = $user->getId();
                    ?>
                        <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition">
                            <td class="py-4 pl-6 font-bold text-slate-900">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center border border-slate-200">
                                        <span class="text-[10px] font-bold"><?= strtoupper(substr($user->getUsername(), 0, 1)) ?></span>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?= htmlspecialchars($user->getUsername()) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4"><?= htmlspecialchars($user->getEmail()->getValue()) ?></td>
                            <td class="py-4"><?= htmlspecialchars($user->getPhoneNumber()) ?></td>
                            <td class="py-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 capitalize"><?= htmlspecialchars($roleVal) ?></span>
                            </td>
                            <td class="py-4">
                                <span class="font-bold <?= $user->getStatus()->isActive() ? 'text-emerald-600' : 'text-slate-400' ?>"><?= ucfirst($user->getStatus()->getValue()) ?></span>
                            </td>
                            <td class="py-4 text-right pr-6">
                                <?php if (can('users.role')): ?>
                                    <button onclick="openRoleModal(<?= $uid ?>, '<?= htmlspecialchars($roleVal) ?>', '<?= htmlspecialchars($user->getUsername()) ?>')" 
                                            class="bg-[#15803D] hover:bg-green-900 text-white px-4 py-1.5 rounded-lg text-xs font-bold transition">
                                        Manage
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="roleModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl border border-white/50 mx-4">
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-lg font-black text-slate-900">Change User Role</h2>
            <button onclick="closeRoleModal()" class="text-slate-400 hover:text-slate-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <p class="text-sm text-slate-500 mb-6" id="modalUserInfo"></p>
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
function openRoleModal(userId, currentRole, userName) {
    document.getElementById('modalUserId').value = userId;
    document.getElementById('modalUserInfo').textContent = userName + ' (Current: ' + currentRole + ')';
    document.querySelectorAll('#roleModal input[name="role_code"]').forEach(r => {
        r.checked = r.value.toLowerCase() === currentRole.toLowerCase();
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
