<?php
$farmerName = $farmer ? $farmer->getUsername() : 'Unknown';
$images = $images ?? [];
$statusColors = [
    'pending' => ['bg' => 'bg-amber-50 border-amber-200', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
    'assigned' => ['bg' => 'bg-blue-50 border-blue-200', 'text' => 'text-blue-700', 'dot' => 'bg-blue-500', 'label' => 'Assigned'],
    'awaiting_payment' => ['bg' => 'bg-violet-50 border-violet-200', 'text' => 'text-violet-700', 'dot' => 'bg-violet-500', 'label' => 'Awaiting Payment'],
    'accepted' => ['bg' => 'bg-emerald-50 border-emerald-200', 'text' => 'text-emerald-700', 'dot' => 'bg-emerald-500', 'label' => 'Active'],
    'rejected' => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'label' => 'Rejected'],
    'expired' => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'label' => 'Expired'],
];
$status = $consultation->getStatus()->getValue();
$theme = $statusColors[$status] ?? $statusColors['pending'];
?>
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <?php $fromPayments = ($_GET['from'] ?? '') === 'payments'; ?>
            <a href="<?= BASE_URL . ($fromPayments ? '/admin/payments' : '/admin/consultations') ?>" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition mb-2 inline-flex">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight"><?= htmlspecialchars($consultation->getTitle()) ?></h1>
            <p class="text-sm text-slate-500 mt-1">Submitted by <strong class="text-slate-700"><?= htmlspecialchars($farmerName) ?></strong> &middot; <?= $consultation->getCreatedAt()->format('M d, Y \a\t g:i A') ?></p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold border <?= $theme['bg'] ?> <?= $theme['text'] ?>">
            <span class="w-2 h-2 rounded-full <?= $theme['dot'] ?>"></span>
            <?= $theme['label'] ?>
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Consultation Details</h2>
                        <p class="text-[10px] text-slate-400">Full description from the farmer</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($consultation->getDescription()) ?></p>
                </div>
            </div>

            <!-- Images Card -->
            <?php if (!empty($images)): ?>
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Attached Images</h2>
                        <p class="text-[10px] text-slate-400"><?= count($images) ?> image<?= count($images) > 1 ? 's' : '' ?> submitted by the farmer</p>
                    </div>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        <?php foreach ($images as $img): ?>
                        <a href="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" target="_blank" class="group relative aspect-square rounded-xl overflow-hidden border border-slate-200 bg-slate-50 hover:border-emerald-300 hover:shadow-md transition-all">
                            <img src="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Consultation image">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                                <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Farmer Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Farmer Information</h3>
                </div>
                <div class="px-5 py-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm shrink-0">
                        <?= strtoupper(substr($farmerName, 0, 1)) ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate"><?= htmlspecialchars($farmerName) ?></p>
                        <p class="text-[10px] text-slate-400">Consultation Requester</p>
                    </div>
                </div>
                <?php if ($farmer && $farmer->getEmail()): ?>
                <div class="px-5 py-3 border-t border-slate-50 flex items-center gap-2.5">
                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                    <span class="text-xs text-slate-600 truncate"><?= htmlspecialchars($farmer->getEmail()->getValue()) ?></span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Payment Info Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Payment Details</h3>
                </div>
                <div class="px-5 py-4 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Amount</span>
                        <span class="font-bold text-slate-800">$<?= number_format($consultationFee ?? 29.99, 2) ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $theme['bg'] ?> <?= $theme['text'] ?>"><?= $theme['label'] ?></span>
                    </div>
                    <?php if ($consultation->getPaymentMethod()): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Method</span>
                        <span class="font-medium text-slate-700"><?= ucfirst($consultation->getPaymentMethod()) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($consultation->getTransactionImage()): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Receipt</span>
                        <a href="<?= BASE_URL . '/' . htmlspecialchars($consultation->getTransactionImage()) ?>" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded-lg hover:bg-emerald-100 transition-colors">
                            <i class="fa-regular fa-image"></i> View
                        </a>
                    </div>
                    <?php endif; ?>
                    <?php if ($consultation->getPaidAt()): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Paid At</span>
                        <span class="font-medium text-slate-700"><?= $consultation->getPaidAt()->format('M d, Y g:i A') ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($consultation->getExpiresAt()): ?>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500">Expires</span>
                        <span class="font-medium text-slate-700 <?= $consultation->isExpired() ? 'text-red-600 font-bold' : 'text-emerald-600' ?>">
                            <?= $consultation->isExpired() ? 'Expired' : $consultation->getExpiresAt()->format('M d, Y') ?>
                        </span>
                    </div>
                    <?php if (!$consultation->isExpired()): ?>
                    <div class="pt-1">
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <?php
                            $now = new DateTimeImmutable();
                            $total = $consultation->getExpiresAt()->getTimestamp() - ($consultation->getPaidAt()?->getTimestamp() ?? $now->getTimestamp());
                            $elapsed = $now->getTimestamp() - ($consultation->getPaidAt()?->getTimestamp() ?? $now->getTimestamp());
                            $pct = $total > 0 ? max(0, min(100, ($elapsed / $total) * 100)) : 0;
                            ?>
                            <div class="h-full rounded-full <?= $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-emerald-500') ?>" style="width: <?= $pct ?>%"></div>
                        </div>
                        <p class="text-[9px] text-slate-400 mt-1"><?= round(100 - $pct) ?>% remaining</p>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!$consultation->getPaidAt() && !$consultation->getExpiresAt()): ?>
                    <div class="px-3 py-3 rounded-xl bg-slate-50 border border-slate-100">
                        <p class="text-[11px] text-slate-500 text-center">No payment recorded yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Actions</h3>
                </div>

                <?php if ($status === 'pending'): ?>
                <div class="px-5 py-4 space-y-4">
                    <p class="text-xs text-slate-600">Assign this consultation to an expert for review and response.</p>
                    <form action="<?= BASE_URL ?>/admin/consultations/assign" method="POST" class="space-y-3">
                        <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Select Expert</label>
                            <select name="expert_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-white">
                                <option value="">Choose an expert...</option>
                                <?php foreach ($experts as $expert): ?>
                                <option value="<?= $expert->getId() ?>"><?= htmlspecialchars($expert->getUsername()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-[#15803D] hover:bg-green-800 text-white text-xs font-bold rounded-xl transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Assign & Send
                        </button>
                    </form>
                </div>

                <?php elseif ($status === 'assigned'): ?>
                <div class="px-5 py-4">
                    <div class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl bg-blue-50 border border-blue-100">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-700 font-medium">Assigned to an expert — awaiting payment.</p>
                    </div>
                </div>

                <?php elseif ($status === 'awaiting_payment'): ?>
                <div class="px-5 py-4">
                    <div class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl bg-violet-50 border border-violet-100">
                        <svg class="w-4 h-4 text-violet-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-violet-700 font-medium">Awaiting payment from farmer to activate.</p>
                    </div>
                </div>

                <?php elseif ($status === 'expired'): ?>
                <div class="px-5 py-4">
                    <div class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl bg-red-50 border border-red-100">
                        <svg class="w-4 h-4 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-red-700 font-medium">Consultation expired. Farmer can renew.</p>
                    </div>
                </div>

                <?php elseif ($status === 'accepted'): ?>
                <div class="px-5 py-4">
                    <div class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl bg-emerald-50 border border-emerald-100">
                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-emerald-700 font-medium">Accepted — expert is chatting with the farmer.</p>
                    </div>
                </div>

                <?php elseif ($status === 'rejected'): ?>
                <div class="px-5 py-4 space-y-3">
                    <?php if ($consultation->getRejectionNote()): ?>
                    <div class="px-3.5 py-3 rounded-xl bg-red-50 border border-red-100">
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Rejection Note</p>
                        <p class="text-xs text-red-700"><?= htmlspecialchars($consultation->getRejectionNote()) ?></p>
                    </div>
                    <?php endif; ?>
                    <form action="<?= BASE_URL ?>/admin/consultations/assign" method="POST" class="space-y-3">
                        <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Reassign to Expert</label>
                            <select name="expert_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-white">
                                <option value="">Choose an expert...</option>
                                <?php foreach ($experts as $expert): ?>
                                <option value="<?= $expert->getId() ?>"><?= htmlspecialchars($expert->getUsername()) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-2.5 bg-[#15803D] hover:bg-green-800 text-white text-xs font-bold rounded-xl transition-colors">
                            Reassign
                        </button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
