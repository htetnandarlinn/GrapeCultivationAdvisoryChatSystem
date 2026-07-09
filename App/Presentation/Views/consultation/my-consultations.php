<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">My Consultations</h2>
            <p class="text-xs text-slate-500 mt-1">Track your submitted consultations.</p>
        </div>
        <a href="<?= BASE_URL ?>/consultation/create" class="px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">
            + New Consultation
        </a>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-600"><?= htmlspecialchars($_SESSION['success']) ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (empty($consultations)): ?>
        <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm text-center">
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
                    'accepted' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-red-100 text-red-700',
                ];
                $color = $statusColors[$c->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
                $consultationImages = $images[$c->getId()] ?? [];
                ?>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div class="flex-grow">
                            <h3 class="text-sm font-bold text-slate-900"><?= htmlspecialchars($c->getTitle()) ?></h3>
                            <p class="text-xs text-slate-500 mt-1"><?= htmlspecialchars(substr($c->getDescription(), 0, 200)) ?><?= strlen($c->getDescription()) > 200 ? '...' : '' ?></p>
                            <p class="text-[10px] text-slate-400 mt-2">Submitted: <?= $c->getCreatedAt()->format('M d, Y h:i A') ?></p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= ucfirst($c->getStatus()->getValue()) ?></span>
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
                    <?php if ($c->getStatus()->getValue() === 'rejected' && $c->getRejectionNote()): ?>
                        <div class="mt-3 p-3 rounded-lg bg-red-50 border border-red-100">
                            <p class="text-[10px] font-bold text-red-600 uppercase">Rejection Note</p>
                            <p class="text-xs text-red-700 mt-0.5"><?= htmlspecialchars($c->getRejectionNote()) ?></p>
                        </div>
                    <?php endif; ?>
                    <?php if ($c->getStatus()->getValue() === 'accepted'): ?>
                        <div class="mt-3 pt-3 border-t border-slate-100">
                            <a href="<?= BASE_URL ?>/consultation/chat?id=<?= $c->getId() ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                                <i class="fa-regular fa-comment-dots"></i> Open Chat
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
