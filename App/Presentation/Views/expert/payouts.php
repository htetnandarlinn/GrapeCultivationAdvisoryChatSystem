<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Earnings</h2>
        <p class="text-xs text-slate-500 mt-1">Track the payments you've earned from completed consultations.</p>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-600"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Summary cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Earned</p>
            <p class="text-2xl font-black text-[#15803D] mt-1">$<?= number_format($totalEarned ?? 0, 2) ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pending Payout</p>
            <p class="text-2xl font-black text-amber-500 mt-1">$<?= number_format($totalPending ?? 0, 2) ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Released</p>
            <p class="text-2xl font-black text-slate-800 mt-1">$<?= number_format($totalReleased ?? 0, 2) ?></p>
        </div>
    </div>

    <?php if (empty($payouts)): ?>
        <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm text-center">
            <p class="text-sm text-slate-400 font-medium">No earnings yet. Earnings are recorded when a farmer's payment is approved.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            <th class="px-5 py-3">Consultation</th>
                            <th class="px-5 py-3">Gross</th>
                            <th class="px-5 py-3">Platform Fee</th>
                            <th class="px-5 py-3">Your Earnings</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Released At</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($payouts as $payout): ?>
                            <?php
                            $status = $payout->getStatus()->getValue();
                            $statusColor = $status === 'released'
                                ? 'bg-emerald-100 text-emerald-700'
                                : 'bg-amber-100 text-amber-700';
                            $statusLabel = $status === 'released' ? 'Released' : 'Pending';
                            ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-5 py-3 font-medium text-slate-700">#<?= $payout->getConsultationId() ?></td>
                                <td class="px-5 py-3 text-slate-600">$<?= number_format($payout->getGrossAmount(), 2) ?></td>
                                <td class="px-5 py-3 text-slate-600">$<?= number_format($payout->getPlatformFee(), 2) ?></td>
                                <td class="px-5 py-3 font-bold text-[#15803D]">$<?= number_format($payout->getNetAmount(), 2) ?></td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>"><?= $statusLabel ?></span>
                                </td>
                                <td class="px-5 py-3 text-slate-500 text-xs">
                                    <?php if ($payout->getReleasedAt()): ?>
                                        <?= $payout->getReleasedAt()->format('Y-m-d H:i') ?>
                                        <span class="block text-slate-400">by <?= htmlspecialchars($payout->getReleasedBy() ?? 'Admin') ?></span>
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
