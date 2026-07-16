<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php $consultationFee = $consultationFee ?? 29.99; ?>
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Consultations</h2>
            <p class="text-xs text-slate-500 mt-1">Track your submitted consultations.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?= BASE_URL ?>/payment/history" class="px-4 py-2 border border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-50 transition-colors">
                <i class="fa-regular fa-credit-card mr-1"></i> Payment History
            </a>
            <a href="<?= BASE_URL ?>/consultations" class="px-4 py-2 text-emerald-600 text-xs font-bold hover:text-emerald-700 transition-colors">
                <i class="fa-regular fa-comment-dots mr-1"></i> Chat View
            </a>
            <a href="<?= BASE_URL ?>/consultation/create" class="px-4 py-2 bg-[#15803D] text-white text-xs font-bold rounded-xl hover:bg-green-800 transition-colors">
                + New Consultation
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-600"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($consultations)): ?>
        <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm text-center">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                <i class="fa-regular fa-file-lines text-xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">No consultations yet.</p>
            <a href="<?= BASE_URL ?>/consultation/create" class="inline-block mt-3 text-xs font-bold text-emerald-600 hover:text-emerald-700">Submit your first consultation</a>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($consultations as $c): ?>
                <?php
                $statusColors = [
                    'pending' => 'bg-amber-100 text-amber-700',
                    'assigned' => 'bg-blue-100 text-blue-700',
                    'expert_accepted' => 'bg-blue-100 text-blue-700',
                    'awaiting_payment' => 'bg-violet-100 text-violet-700',
                    'payment_submitted' => 'bg-amber-100 text-amber-700',
                    'accepted' => 'bg-emerald-100 text-emerald-700',
                    'chat_started' => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-blue-100 text-blue-700',
                    'closed' => 'bg-slate-100 text-slate-600',
                    'rejected' => 'bg-red-100 text-red-700',
                    'expired' => 'bg-red-100 text-red-700',
                ];
                $color = $statusColors[$c->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
                $consultationImages = $images[$c->getId()] ?? [];
                $status = $c->getStatus()->getValue();
                $statusLabel = str_replace('_', ' ', $status);
                $statusLabel = ucwords($statusLabel);
                ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow mr-4">
                            <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($c->getTitle()) ?></h3>
                            <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(mb_strimwidth($c->getDescription(), 0, 200, '...')) ?></p>
                            <p class="text-[10px] text-slate-400 mt-2">
                                <i class="fa-regular fa-calendar mr-1"></i> <?= $c->getCreatedAt()->format('M d, Y h:i A') ?>
                                <?php if ($c->getExpiresAt() && in_array($status, ['accepted', 'chat_started'])): ?>
                                    &middot; <span class="<?= $c->isExpired() ? 'text-red-500' : 'text-emerald-500' ?>">
                                        <i class="fa-regular fa-clock mr-1"></i><?= $c->isExpired() ? 'Expired' : 'Expires ' . $c->getExpiresAt()->format('M d, Y') ?>
                                    </span>
                                <?php endif; ?>
                                <?php if ($c->getRefundStatus() === 'refunded'): ?>
                                    &middot; <span class="text-rose-500"><i class="fa-solid fa-rotate-left mr-1"></i>Refunded $<?= number_format($c->getRefundAmount() ?? 0, 2) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= $statusLabel ?></span>
                            <?php if ($status === 'awaiting_payment'): ?>
                                <a href="<?= BASE_URL ?>/payment/consultation?id=<?= $c->getId() ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#15803D] text-white text-[10px] font-bold rounded-lg hover:bg-green-800 transition-colors">
                                    <i class="fa-solid fa-lock text-[8px]"></i> Pay $<?= number_format($consultationFee, 2) ?>
                                </a>
                            <?php elseif ($status === 'payment_submitted'): ?>
                                <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-lg">
                                    <i class="fa-solid fa-hourglass-half text-[8px]"></i> Pending Approval
                                </span>
                            <?php elseif ($status === 'expired'): ?>
                                <a href="<?= BASE_URL ?>/payment/consultation?id=<?= $c->getId() ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-amber-600 text-white text-[10px] font-bold rounded-lg hover:bg-amber-700 transition-colors">
                                    <i class="fa-solid fa-arrows-rotate text-[8px]"></i> Renew $<?= number_format($consultationFee, 2) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($consultationImages)): ?>
                    <div class="mt-3 flex gap-2 flex-wrap">
                        <?php foreach ($consultationImages as $img): ?>
                        <a href="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" target="_blank">
                            <img src="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Consultation image">
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($status === 'rejected' && $c->getRejectionNote()): ?>
                        <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-100">
                            <p class="text-[10px] font-bold text-red-600 uppercase">Rejection Note</p>
                            <p class="text-xs text-red-700 mt-0.5"><?= htmlspecialchars($c->getRejectionNote()) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if (in_array($status, ['accepted', 'chat_started'])): ?>
                        <div class="mt-3 pt-3 border-t border-slate-100 flex items-center gap-2">
                            <a href="<?= BASE_URL ?>/consultations" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#15803D] text-white text-xs font-bold rounded-xl hover:bg-green-800 transition-colors">
                                <i class="fa-regular fa-comment-dots"></i> Open Chat
                            </a>
                            <?php if ($c->getExpiresAt() && !$c->isExpired()): ?>
                                <span class="text-[10px] text-emerald-600 font-medium">
                                    <i class="fa-regular fa-clock"></i> Active
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
