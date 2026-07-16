<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-3">
            <a href="<?= BASE_URL ?>/dashboard" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Payment Management</h2>
                <p class="text-xs text-slate-500 mt-1">Manage consultation payments, review receipts, and process transactions.</p>
            </div>
        </div>
        <div class="flex items-center gap-3 text-xs text-slate-400">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Live</span>
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-database text-slate-300"></i> <?= isset($payments) ? count($payments) : 0 ?> records</span>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-700 flex items-center justify-between">
            <span class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-500"></i> <?= htmlspecialchars($_SESSION['success']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-xs font-semibold text-red-700 flex items-center justify-between">
            <span class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-red-500"></i> <?= htmlspecialchars($_SESSION['error']) ?></span>
            <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <!-- Filters -->
    <div class="flex items-center gap-3 mb-4">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Filter:</span>
        <button class="filter-btn active px-3.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-900 text-white transition-colors" data-filter="all">All</button>
        <button class="filter-btn px-3.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-filter="awaiting_payment">Awaiting Payment</button>
        <button class="filter-btn px-3.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-filter="payment_submitted">Pending Review</button>
        <button class="filter-btn px-3.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-filter="accepted">Paid</button>
        <button class="filter-btn px-3.5 py-1.5 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors" data-filter="expired">Expired</button>
    </div>

    <!-- Payout summary -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending Payouts (to experts)</p>
            <p class="text-2xl font-black text-amber-500 mt-1">$<?= number_format($totalPayoutPending ?? 0, 2) ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Released Payouts</p>
            <p class="text-2xl font-black text-[#15803D] mt-1">$<?= number_format($totalPayoutReleased ?? 0, 2) ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-200/70 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Expert Payouts</p>
            <p class="text-2xl font-black text-slate-800 mt-1">$<?= number_format(($totalPayoutPending ?? 0) + ($totalPayoutReleased ?? 0), 2) ?></p>
        </div>
    </div>

    <!-- Payments Table -->
    <?php if (empty($payments)): ?>
        <div class="bg-white p-14 rounded-2xl border border-slate-100 shadow-sm text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                <i class="fa-solid fa-credit-card text-2xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">No payments yet.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
            <div class="max-h-[470px] overflow-y-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Farmer / Consultation</th>
                            <th class="text-left px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Expert</th>
                            <th class="text-left px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Method</th>
                            <th class="text-right px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="text-center px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Receipt</th>
                            <th class="text-center px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Invoice</th>
                            <th class="text-center px-5 py-3.5 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $p):
                            $badgeMap = [
                                'awaiting_payment' => ['bg' => 'bg-violet-100 text-violet-700', 'dot' => 'bg-violet-500', 'label' => 'Awaiting Payment'],
                                'payment_submitted' => ['bg' => 'bg-amber-100 text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'Pending Review'],
                                'accepted' => ['bg' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Paid'],
                                'chat_started' => ['bg' => 'bg-emerald-100 text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Chat Active'],
                                'completed' => ['bg' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500', 'label' => 'Completed'],
                                'closed' => ['bg' => 'bg-slate-100 text-slate-600', 'dot' => 'bg-slate-400', 'label' => 'Closed'],
                                'expired' => ['bg' => 'bg-red-100 text-red-700', 'dot' => 'bg-red-500', 'label' => 'Expired'],
                            ];
                            $b = $badgeMap[$p['status']] ?? ['bg'=>'bg-slate-100 text-slate-600', 'dot'=>'bg-slate-400', 'label'=>ucfirst($p['status'])];
                            $methodIcon = [
                                'kpay' => '',
                                'wavepay' => '',
                                'paypal' => '',
                                'manual' => '',
                            ];
                            $methodColor = [
                                'kpay' => 'bg-emerald-100 text-emerald-700',
                                'wavepay' => 'bg-blue-100 text-blue-700',
                                'paypal' => 'bg-indigo-100 text-indigo-700',
                                'manual' => 'bg-slate-100 text-slate-600',
                            ];
                            $mIcon = $methodIcon[$p['payment_method']] ?? '—';
                            $mColor = $methodColor[$p['payment_method']] ?? 'bg-slate-100 text-slate-600';
                        ?>
                        <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors payment-row" data-status="<?= $p['status'] ?>">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if ($p['image']): ?>
                                        <div class="w-9 h-9 rounded-lg overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                            <img src="<?= BASE_URL . '/' . htmlspecialchars($p['image']) ?>" class="w-full h-full object-cover" alt="">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-9 h-9 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <span class="text-xs font-semibold text-slate-900"><?= htmlspecialchars($p['farmer_name']) ?></span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 truncate max-w-[180px]"><?= htmlspecialchars($p['title']) ?> · #<?= $p['id'] ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <?php if ($p['expert_name']): ?>
                                    <span class="text-xs font-medium text-slate-700"><?= htmlspecialchars($p['expert_name']) ?></span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold <?= $b['bg'] ?>">
                                    <span class="w-1.5 h-1.5 rounded-full <?= $b['dot'] ?>"></span>
                                    <?= $b['label'] ?>
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <?php if ($p['payment_method']): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-bold <?= $mColor ?>">
                                        <?= $mIcon ?> <?= ucfirst($p['payment_method']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-bold text-slate-900">$<?= number_format($p['amount'] ?? 0, 2) ?></span>
                            </td>
                            <td class="px-5 py-4 text-xs text-slate-500">
                                <?php if ($p['paid_at']): ?>
                                    <div><?= $p['paid_at']->format('M d, Y') ?></div>
                                    <div class="text-[9px] text-slate-400"><?= $p['paid_at']->format('g:i A') ?></div>
                                <?php elseif ($p['created_at']): ?>
                                    <div class="text-slate-400"><?= $p['created_at']->format('M d, Y') ?></div>
                                <?php else: ?>
                                    <span class="text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <?php if ($p['transaction_image']): ?>
                                    <button onclick="openReceipt(<?= $p['id'] ?>)" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-violet-100 hover:text-violet-700 transition-colors border border-slate-200 hover:border-violet-200">
                                        <i class="fa-regular fa-image"></i> View
                                    </button>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <?php if (in_array($p['status'], ['accepted', 'chat_started', 'completed', 'closed'])): ?>
        <a href="<?= BASE_URL ?>/invoice?consultation_id=<?= $p['id'] ?>" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg hover:bg-indigo-100 hover:text-indigo-700 transition-colors border border-slate-200 hover:border-indigo-200">
            <i class="fa-regular fa-receipt"></i> Invoice
        </a>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-300">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="<?= BASE_URL ?>/admin/consultations/view?id=<?= $p['id'] ?>&from=payments" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <?php if ($p['status'] === 'awaiting_payment' && $p['transaction_image']): ?>
                                        <button onclick="openReceipt(<?= $p['id'] ?>)" class="p-2 rounded-lg text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors" title="Review">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </button>
                                    <?php elseif ($p['status'] === 'accepted'): ?>
                                        <button onclick="openRefund(<?= $p['id'] ?>)" class="p-2 rounded-lg text-amber-500 hover:text-amber-700 hover:bg-amber-50 transition-colors" title="Refund">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>

                        <!-- Hidden receipt row -->
                        <tr id="receipt-row-<?= $p['id'] ?>" class="hidden receipt-row">
                            <td colspan="9" class="px-5 py-0 bg-slate-50/50">
                                <div class="flex items-start gap-6 py-4">
                                    <div class="w-48 h-64 rounded-xl overflow-hidden bg-slate-200 border border-slate-300 shadow-sm shrink-0">
                                        <img src="<?= BASE_URL . '/' . htmlspecialchars($p['transaction_image']) ?>" class="w-full h-full object-contain" alt="Receipt">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-slate-900 mb-2">Receipt Review</h4>
                                        <div class="space-y-1.5 text-[10px] text-slate-500 mb-3">
                                            <p><span class="font-semibold text-slate-700">Farmer:</span> <?= htmlspecialchars($p['farmer_name']) ?></p>
                                            <p><span class="font-semibold text-slate-700">Method:</span> <?= ucfirst($p['payment_method'] ?? '—') ?></p>
                                            <p><span class="font-semibold text-slate-700">Amount:</span> $<?= number_format($p['amount'] ?? 0, 2) ?></p>
                                            <p><span class="font-semibold text-slate-700">Submitted:</span> <?= $p['created_at']->format('M d, Y g:i A') ?></p>
                                        </div>
                                        <?php if (in_array($p['status'], ['payment_submitted', 'awaiting_payment'])): ?>
                                        <div class="flex items-center gap-2">
                                            <?php if ($p['transaction_image']): ?>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/approve" class="inline" onsubmit="return confirm('Approve this payment? Consultation will be activated.');">
                                                <input type="hidden" name="consultation_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#15803D] hover:bg-green-800 text-white text-[10px] font-bold rounded-lg transition-colors shadow-sm">
                                                    <i class="fa-solid fa-check"></i> Approve Payment
                                                </button>
                                            </form>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/reject" class="inline" onsubmit="return confirm('Reject this receipt? Farmer will be notified to re-upload.');">
                                                <input type="hidden" name="consultation_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-700 text-[10px] font-bold rounded-lg transition-colors">
                                                    <i class="fa-solid fa-xmark"></i> Reject
                                                </button>
                                            </form>
                                            <?php else: ?>
                                            <span class="text-[10px] text-slate-400 italic">Waiting for payment submission</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php elseif (in_array($p['status'], ['accepted', 'chat_started', 'completed', 'closed'])): ?>
                                        <div class="flex items-center gap-2">
                                            <?php if ($p['refund_status'] ?? null): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700">
                                                <i class="fa-solid fa-rotate-left"></i> Refunded $<?= number_format($p['refund_amount'] ?? 0, 2) ?>
                                            </span>
                                            <?php elseif (!empty($p['payout']) && $p['payout']->getStatus()->getValue() === 'released'): ?>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                                <i class="fa-solid fa-circle-check"></i> Payout Released $<?= number_format($p['payout']->getNetAmount(), 2) ?>
                                            </span>
                                            <?php else: ?>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/refund" class="inline" onsubmit="return confirm('Refund this payment?');">
                                                <input type="hidden" name="consultation_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-100 hover:bg-amber-200 text-amber-700 text-[10px] font-bold rounded-lg transition-colors">
                                                    <i class="fa-solid fa-rotate-left"></i> Refund Payment
                                                </button>
                                            </form>
                                            <form method="POST" action="<?= BASE_URL ?>/admin/payments/release-payout" class="inline" onsubmit="return confirm('Release the expert payout (80% of fee)?');">
                                                <input type="hidden" name="consultation_id" value="<?= $p['id'] ?>">
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#15803D] hover:bg-green-800 text-white text-[10px] font-bold rounded-lg transition-colors shadow-sm">
                                                    <i class="fa-solid fa-money-bill-transfer"></i> Release Payout
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <button onclick="closeReceipt(<?= $p['id'] ?>)" class="text-slate-400 hover:text-slate-600 p-1">
                                        <i class="fa-solid fa-xmark text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Receipt Modal -->
        <div id="receipt-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900">Receipt Preview</h3>
                    <button onclick="closeModal()" class="w-8 h-8 rounded-xl hover:bg-slate-100 flex items-center justify-center text-slate-400 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="p-5">
                    <div id="modal-receipt-image" class="w-full max-h-96 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 mb-4 flex items-center justify-center">
                        <img id="modal-receipt-img" src="" alt="Receipt" class="max-w-full max-h-96 object-contain">
                    </div>
                    <div id="modal-receipt-actions" class="flex items-center gap-2"></div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</section>

<script>
// Receipt toggle
function openReceipt(id) {
    const row = document.getElementById('receipt-row-' + id);
    const allRows = document.querySelectorAll('.receipt-row');
    allRows.forEach(r => { if (r.id !== 'receipt-row-' + id) r.classList.add('hidden'); });
    row.classList.toggle('hidden');
}

function closeReceipt(id) {
    document.getElementById('receipt-row-' + id).classList.add('hidden');
}

// Modal
function openModal(imgSrc, actionsHtml) {
    document.getElementById('modal-receipt-img').src = imgSrc;
    document.getElementById('modal-receipt-actions').innerHTML = actionsHtml;
    document.getElementById('receipt-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('receipt-modal').classList.add('hidden');
}

document.getElementById('receipt-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Refund
function openRefund(id) {
    const row = document.getElementById('receipt-row-' + id);
    const allRows = document.querySelectorAll('.receipt-row');
    allRows.forEach(r => { if (r.id !== 'receipt-row-' + id) r.classList.add('hidden'); });
    row.classList.remove('hidden');
    setTimeout(() => row.scrollIntoView({ behavior: 'smooth', block: 'center' }), 100);
}

// Filter
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-slate-900', 'text-white');
            b.classList.add('bg-slate-100', 'text-slate-600');
        });
        this.classList.remove('bg-slate-100', 'text-slate-600');
        this.classList.add('bg-slate-900', 'text-white');

        const filter = this.dataset.filter;
        document.querySelectorAll('.payment-row').forEach(row => {
            if (filter === 'all' || row.dataset.status === filter) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    });
});
</script>
