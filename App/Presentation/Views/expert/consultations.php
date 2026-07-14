<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Assigned Consultations</h2>
        <p class="text-xs text-slate-500 mt-1">Review and respond to consultations assigned to you.</p>
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
            <p class="text-sm text-slate-400 font-medium">No consultations assigned to you yet.</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($consultations as $c): ?>
                <?php
                $status = $c->getStatus()->getValue();
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
                $color = $statusColors[$status] ?? 'bg-slate-100 text-slate-600';
                $statusLabel = ucwords(str_replace('_', ' ', $status));
                ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($c->getTitle()) ?></h3>
                            <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(substr($c->getDescription(), 0, 200)) ?><?= strlen($c->getDescription()) > 200 ? '...' : '' ?></p>
                            <p class="text-[10px] text-slate-400 mt-2">Farmer #<?= $c->getFarmerId() ?> &middot; <?= $c->getCreatedAt()->format('M d, Y h:i A') ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= $statusLabel ?></span>
                    </div>
                    <div class="mt-3 flex gap-2">
                        <a href="<?= BASE_URL ?>/expert/consultations/view?id=<?= $c->getId() ?>" class="px-4 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
