<?php
$farmerName = $farmer ? $farmer->getUsername() : 'Unknown';
?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/admin/consultations" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">&larr; Back to Consultations</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-900"><?= htmlspecialchars($consultation->getTitle()) ?></h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    By <?= htmlspecialchars($farmerName) ?> &middot; <?= $consultation->getCreatedAt()->format('M d, Y h:i A') ?>
                </p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-[10px] font-bold <?php
                $colors = ['pending' => 'bg-amber-100 text-amber-700', 'assigned' => 'bg-blue-100 text-blue-700', 'accepted' => 'bg-emerald-100 text-emerald-700', 'rejected' => 'bg-red-100 text-red-700'];
                echo $colors[$consultation->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
            ?>"><?= ucfirst($consultation->getStatus()->getValue()) ?></span>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($consultation->getDescription()) ?></p>
        </div>

        <?php if ($consultation->getStatus()->getValue() === 'pending'): ?>
        <div class="px-6 py-5 bg-slate-50 border-t border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 mb-3">Assign Expert</h3>
            <form action="<?= BASE_URL ?>/admin/consultations/assign" method="POST" class="flex items-end gap-3">
                <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                <div class="flex-grow">
                    <select name="expert_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                        <option value="">Select an expert...</option>
                        <?php foreach ($experts as $expert): ?>
                            <option value="<?= $expert->getId() ?>"><?= htmlspecialchars($expert->getUsername()) ?> (<?= htmlspecialchars($expert->getEmail()->getValue()) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors whitespace-nowrap">
                    Assign & Send
                </button>
            </form>
        </div>
        <?php elseif ($consultation->getStatus()->getValue() === 'rejected' && $consultation->getRejectionNote()): ?>
        <div class="px-6 py-5 bg-red-50 border-t border-red-100">
            <h3 class="text-xs font-bold text-red-600 uppercase mb-1">Rejection Note</h3>
            <p class="text-sm text-red-700"><?= htmlspecialchars($consultation->getRejectionNote()) ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>
