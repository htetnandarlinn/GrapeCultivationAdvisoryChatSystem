<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment History</h2>
            <p class="text-xs text-slate-500 mt-1">Record of all your consultation payments.</p>
        </div>
        <a href="<?= BASE_URL ?>/consultations" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-sm shadow-emerald-200">
            <i class="fa-regular fa-comment-dots"></i> Chat View
        </a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3.5 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-700 flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-red-500"></i> <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php
        $totalSpent = 0;
        foreach ($consultations as $c) {
            $s = $c->getStatus()->getValue();
            if (in_array($s, ['accepted', 'chat_started', 'completed', 'closed'], true) || ($s === 'expired' && $c->getPaidAt())) {
                $pay = $paymentRecords[$c->getId()] ?? null;
                $totalSpent += $pay ? (float) $pay->getAmount() : 0.0;
            }
        }
    ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Spent</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">$<?= number_format($totalSpent, 2) ?></p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-500 group-hover:bg-emerald-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-lg"></i>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between group hover:shadow-md transition-all duration-200">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Consultations</p>
                <p class="text-2xl font-black text-slate-900 mt-1"><?= count($consultations) ?></p>
                <p class="text-[10px] text-slate-400 mt-0.5">With payment records</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-500 group-hover:bg-amber-100 group-hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
        </div>
    </div>

    <?php if (empty($consultations)): ?>
        <div class="bg-white p-12 rounded-2xl border border-slate-100 shadow-sm text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                <i class="fa-regular fa-credit-card text-2xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">No payment records yet.</p>
            <a href="<?= BASE_URL ?>/consultation/create" class="inline-block mt-3 text-xs font-bold text-emerald-600 hover:text-emerald-700">Submit your first consultation</a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Consultation</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Method</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Paid Date</th>
                            <th class="text-left px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Verified By</th>
                            <th class="text-center px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Receipt</th>
                            <th class="text-center px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                            <th class="text-center px-4 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Refund</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $c):
                            $s = $c->getStatus()->getValue();
                            $cid = $c->getId();
                            $pay = $paymentRecords[$cid] ?? null;
                            $statusColors = [
                                'awaiting_payment' => 'bg-violet-100 text-violet-700',
                                'payment_submitted' => 'bg-amber-100 text-amber-700',
                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                'chat_started' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'closed' => 'bg-slate-100 text-slate-600',
                                'expired' => 'bg-red-100 text-red-700',
                            ];
                            $statusLabels = [
                                'awaiting_payment' => 'Awaiting Payment',
                                'payment_submitted' => 'Pending Review',
                                'accepted' => 'Paid',
                                'chat_started' => 'Chat Active',
                                'completed' => 'Completed',
                                'closed' => 'Closed',
                                'expired' => 'Expired',
                            ];
                            $color = $statusColors[$s] ?? 'bg-slate-100 text-slate-600';
                            $label = $statusLabels[$s] ?? ucfirst($s);
                            $methodLabels = [
                                'kpay' => 'KPay',
                                'wavepay' => 'Wave Pay',
                                'paypal' => 'PayPal',
                            ];
                            $methodName = $methodLabels[$c->getPaymentMethod()] ?? ($c->getPaymentMethod() ? ucfirst($c->getPaymentMethod()) : '—');
                            $hasReceipt = $c->getTransactionImage() !== null;
                            $paidStatus = in_array($s, ['accepted', 'chat_started', 'completed', 'closed']);
                            $refunded = $c->getRefundStatus() === 'refunded';
                            $invoiceNum = 'INV-' . str_pad($cid, 6, '0', STR_PAD_LEFT);
                        ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-900 truncate max-w-[160px]"><?= htmlspecialchars($c->getTitle()) ?></p>
                                    <p class="text-[10px] text-slate-400 font-mono">#<?= $cid ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-[10px] font-mono font-bold text-slate-700"><?= $invoiceNum ?></span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold <?= $color ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= str_replace('text-', 'bg-', explode(' ', $color)[1]) ?>"></span>
                                    <?= $label ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-medium text-slate-700"><?= $methodName ?></span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-xs font-bold text-slate-800">$<?= $pay ? number_format($pay->getAmount(), 2) : '—' ?></span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500">
                                <?php if ($c->getVerifiedAt()): ?>
                                    <div><?= $c->getVerifiedAt()->format('M d, Y') ?></div>
                                    <div class="text-[9px] text-slate-400"><?= $c->getVerifiedAt()->format('g:i A') ?></div>
                                <?php elseif ($c->getPaidAt()): ?>
                                    <div><?= $c->getPaidAt()->format('M d, Y') ?></div>
                                    <div class="text-[9px] text-slate-400"><?= $c->getPaidAt()->format('g:i A') ?></div>
                                <?php else: ?>
                                    <span class="text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($c->getVerifiedBy()): ?>
                                    <span class="text-xs font-medium text-slate-700"><?= htmlspecialchars($c->getVerifiedBy()) ?></span>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($hasReceipt): ?>
                                    <a href="<?= BASE_URL . '/' . htmlspecialchars($c->getTransactionImage()) ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-emerald-50 hover:text-emerald-600 transition-colors border border-slate-200 hover:border-emerald-200">
                                        <i class="fa-regular fa-image"></i> View
                                    </a>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($paidStatus || $refunded): ?>
        <a href="<?= BASE_URL ?>/invoice?consultation_id=<?= $cid ?>" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-200 hover:border-emerald-300">
            <i class="fa-regular fa-receipt"></i> Invoice
        </a>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($refunded): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700">
                                        <i class="fa-solid fa-rotate-left"></i> Refunded
                                    </span>
                                    <?php if ($c->getRefundDate()): ?>
                                    <div class="text-[9px] text-slate-400 mt-0.5"><?= $c->getRefundDate()->format('M d, Y') ?></div>
                                    <?php endif; ?>
                                <?php elseif ($paidStatus): ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
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