<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment Management</h2>
        <p class="text-xs text-slate-500 mt-1">Overview of all consultation payments and transactions.</p>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Revenue</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">$<?= number_format($totalRevenue, 2) ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-lg"></i>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Completed Payments</p>
                <p class="text-2xl font-black text-slate-900 mt-1"><?= $paymentCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-lg"></i>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Awaiting Payment</p>
                <p class="text-2xl font-black text-violet-600 mt-1"><?= $awaitingCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-violet-50 text-violet-500 group-hover:bg-violet-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-lg"></i>
            </div>
        </div>
        <div class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Expired</p>
                <p class="text-2xl font-black text-red-600 mt-1"><?= $expiredCount ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-red-50 text-red-500 group-hover:bg-red-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fa-solid fa-clock text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <?php if (empty($payments)): ?>
        <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm text-center">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                <i class="fa-solid fa-credit-card text-xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">No payments yet.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="max-h-[550px] overflow-y-auto overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Image</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Title</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Farmer</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expert</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Paid At</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expires</th>
                            <th class="text-center px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p): ?>
                        <?php
                            $statusBadges = [
                                'awaiting_payment' => 'bg-violet-100 text-violet-700',
                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                'expired' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'awaiting_payment' => 'Awaiting Payment',
                                'accepted' => 'Paid',
                                'expired' => 'Expired',
                            ];
                            $badgeClass = $statusBadges[$p['status']] ?? 'bg-slate-100 text-slate-600';
                            $label = $statusLabels[$p['status']] ?? ucfirst($p['status']);
                        ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3">
                                <?php if ($p['image']): ?>
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                        <img src="<?= BASE_URL . '/' . htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover" alt="">
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900 truncate max-w-[180px]"><?= htmlspecialchars($p['title']) ?></p>
                                    <p class="text-[10px] text-slate-400 font-mono">#<?= $p['id'] ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-700 text-[9px] font-bold shrink-0">
                                        <?= strtoupper(substr($p['farmer_name'], 0, 1)) ?>
                                    </div>
                                    <span class="text-xs font-medium text-slate-700"><?= htmlspecialchars($p['farmer_name']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($p['expert_name']): ?>
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-700 text-[9px] font-bold shrink-0">
                                        <?= strtoupper(substr($p['expert_name'], 0, 1)) ?>
                                    </div>
                                    <span class="text-xs font-medium text-slate-700"><?= htmlspecialchars($p['expert_name']) ?></span>
                                </div>
                                <?php else: ?>
                                <span class="text-xs text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $badgeClass ?>"><?= $label ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if ($p['status'] === 'accepted' || ($p['status'] === 'expired' && $p['paid_at'])): ?>
                                    <span class="text-xs font-bold text-slate-800">$29.99</span>
                                <?php elseif ($p['status'] === 'awaiting_payment'): ?>
                                    <span class="text-xs font-semibold text-slate-400">$29.99</span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                <?= $p['paid_at'] ? $p['paid_at']->format('M d, Y g:i A') : '<span class="text-slate-300">—</span>' ?>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                <?= $p['expires_at'] ? $p['expires_at']->format('M d, Y') : '<span class="text-slate-300">—</span>' ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1.5">
                                    <a href="<?= BASE_URL ?>/admin/consultations/view?id=<?= $p['id'] ?>" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


    <?php endif; ?>
</section>
