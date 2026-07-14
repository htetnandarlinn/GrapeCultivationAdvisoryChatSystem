<?php
$farmerName = $farmer ? $farmer->getUsername() : 'Unknown';
$images = $images ?? [];
?>
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <a href="<?= BASE_URL ?>/expert/consultations" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">&larr; Back to Consultations</a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-600"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-black text-slate-900"><?= htmlspecialchars($consultation->getTitle()) ?></h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    By <?= htmlspecialchars($farmerName) ?> &middot; <?= $consultation->getCreatedAt()->format('M d, Y h:i A') ?>
                </p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-[10px] font-bold <?php
                $colors = ['pending' => 'bg-amber-100 text-amber-700', 'assigned' => 'bg-blue-100 text-blue-700', 'expert_accepted' => 'bg-blue-100 text-blue-700', 'awaiting_payment' => 'bg-violet-100 text-violet-700', 'payment_submitted' => 'bg-amber-100 text-amber-700', 'accepted' => 'bg-emerald-100 text-emerald-700', 'chat_started' => 'bg-emerald-100 text-emerald-700', 'completed' => 'bg-blue-100 text-blue-700', 'closed' => 'bg-slate-100 text-slate-600', 'rejected' => 'bg-red-100 text-red-700', 'expired' => 'bg-red-100 text-red-700'];
                echo $colors[$consultation->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
            ?>"><?= ucwords(str_replace('_', ' ', $consultation->getStatus()->getValue())) ?></span>
        </div>

        <div class="px-6 py-5">
            <p class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($consultation->getDescription()) ?></p>
        </div>

        <?php if (!empty($images)): ?>
        <div class="px-6 py-4 border-t border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Attached Images</p>
            <div class="flex gap-2 flex-wrap">
                <?php foreach ($images as $img): ?>
                <a href="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" target="_blank">
                    <img src="<?= BASE_URL . '/' . htmlspecialchars($img['image_path']) ?>" class="w-24 h-24 rounded-xl object-cover border border-slate-200 hover:opacity-90 transition-opacity" alt="Consultation image">
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php $status = $consultation->getStatus()->getValue(); ?>
        <?php if ($status === 'assigned'): ?>
        <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 space-y-3">
            <div class="flex gap-2">
                <form action="<?= BASE_URL ?>/expert/consultations/accept" method="POST" class="inline">
                    <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                        Accept Consultation
                    </button>
                </form>

                <button type="button" onclick="showRejectForm()" class="px-5 py-2.5 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition-colors">
                    Reject
                </button>
            </div>

            <div id="rejectForm" class="hidden">
                <form action="<?= BASE_URL ?>/expert/consultations/reject" method="POST" class="space-y-3">
                    <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Rejection Note</label>
                        <textarea name="rejection_note" rows="3" required
                                  class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition-all resize-y"
                                  placeholder="Explain why you cannot take this consultation..."></textarea>
                    </div>
                    <button type="submit" class="px-5 py-2.5 bg-red-600 text-white text-xs font-bold rounded-xl hover:bg-red-700 transition-colors">
                        Submit Rejection
                    </button>
                </form>
            </div>
        </div>
        <?php elseif (in_array($status, ['accepted', 'chat_started'])): ?>
        <div class="px-6 py-5 bg-emerald-50 border-t border-emerald-100">
            <a href="<?= BASE_URL ?>/expert/consultations/chat?id=<?= $consultation->getId() ?>" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                <i class="fa-regular fa-comment-dots"></i> Chat with Farmer
            </a>
        </div>
        <?php elseif (in_array($status, ['awaiting_payment', 'expert_accepted'])): ?>
        <div class="px-6 py-5 bg-violet-50 border-t border-violet-100">
            <p class="text-xs text-violet-700"><strong>Awaiting Payment</strong> &mdash; The farmer needs to complete payment before the consultation period starts.</p>
        </div>
        <?php elseif ($status === 'payment_submitted'): ?>
        <div class="px-6 py-5 bg-amber-50 border-t border-amber-100">
            <p class="text-xs text-amber-700"><strong>Payment Submitted</strong> &mdash; The farmer has submitted payment. Waiting for admin approval.</p>
        </div>
        <?php elseif ($status === 'expired'): ?>
        <div class="px-6 py-5 bg-red-50 border-t border-red-100">
            <p class="text-xs text-red-700"><strong>Expired</strong> &mdash; The consultation period has ended. The farmer may renew to continue.</p>
        </div>
        <?php elseif ($status === 'rejected' && $consultation->getRejectionNote()): ?>
        <div class="px-6 py-5 bg-red-50 border-t border-red-100">
            <h3 class="text-xs font-bold text-red-600 uppercase mb-1">Rejection Note</h3>
            <p class="text-sm text-red-700"><?= htmlspecialchars($consultation->getRejectionNote()) ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<script>
function showRejectForm() {
    document.getElementById('rejectForm').classList.toggle('hidden');
}
</script>
