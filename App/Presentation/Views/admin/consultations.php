<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with filter tabs -->
    <div class="mb-8">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Consultation Management</h2>
                <p class="text-xs text-slate-500 mt-1">Manage and assign farmer consultations to experts.</p>
            </div>
            <a href="<?= BASE_URL ?>/admin/payments" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                <i class="fa-solid fa-credit-card text-[10px]"></i> View Payments
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
                <i class="fa-regular fa-comment-dots text-xl"></i>
            </div>
            <p class="text-sm text-slate-400 font-medium">No consultations found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="max-h-[550px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Image</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Title</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Farmer</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Payment</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="text-center px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($consultations as $c): ?>
                            <?php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'assigned' => 'bg-blue-100 text-blue-700',
                                'awaiting_payment' => 'bg-violet-100 text-violet-700',
                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'expired' => 'bg-red-100 text-red-700',
                            ];
                            $color = $statusColors[$c->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
                            $paymentLabels = [
                                'pending' => ['text' => '—', 'class' => 'text-slate-300'],
                                'assigned' => ['text' => '—', 'class' => 'text-slate-300'],
                                'awaiting_payment' => ['text' => 'Pending $29.99', 'class' => 'text-violet-600 font-semibold'],
                                'accepted' => ['text' => 'Paid $29.99', 'class' => 'text-emerald-600 font-semibold'],
                                'rejected' => ['text' => '—', 'class' => 'text-slate-300'],
                                'expired' => ['text' => 'Expired', 'class' => 'text-red-600 font-semibold'],
                            ];
                            $pmt = $paymentLabels[$c->getStatus()->getValue()] ?? ['text' => '—', 'class' => 'text-slate-300'];
                            $imgPath = $consultationImages[$c->getId()] ?? null;
                            ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3">
                                    <?php if ($imgPath): ?>
                                        <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                            <img src="<?= BASE_URL . '/' . htmlspecialchars($imgPath) ?>" class="w-full h-full object-cover" alt="">
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
                                        <p class="text-xs font-semibold text-slate-900 truncate max-w-[200px]"><?= htmlspecialchars($c->getTitle()) ?></p>
                                        <p class="text-[10px] text-slate-400 font-mono">#<?= $c->getId() ?></p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-xs font-semibold text-slate-700">Farmer #<?= $c->getFarmerId() ?></td>
                                <td class="px-4 py-3"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= ucfirst(str_replace('_', ' ', $c->getStatus()->getValue())) ?></span></td>
                                <td class="px-4 py-3"><span class="text-xs <?= $pmt['class'] ?>"><?= $pmt['text'] ?></span></td>
                                <td class="px-4 py-3 text-[10px] text-slate-400"><?= $c->getCreatedAt()->format('M d, Y') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="<?= BASE_URL ?>/admin/consultations/view?id=<?= $c->getId() ?>" class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View">
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
