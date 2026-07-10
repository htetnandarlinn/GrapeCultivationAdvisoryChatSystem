<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Consultation Management</h2>
        <p class="text-xs text-slate-500 mt-1">Manage and assign farmer consultations to experts.</p>
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
            <p class="text-sm text-slate-400 font-medium">No consultations found.</p>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="max-h-[470px] overflow-y-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">ID</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Farmer</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Title</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Status</th>
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
                                'accepted' => 'bg-emerald-100 text-emerald-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                            $color = $statusColors[$c->getStatus()->getValue()] ?? 'bg-slate-100 text-slate-600';
                            ?>
                            <tr class="border-b border-slate-50 hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-xs font-mono text-slate-500">#<?= $c->getId() ?></td>
                                <td class="px-4 py-3 text-xs font-semibold text-slate-700">Farmer #<?= $c->getFarmerId() ?></td>
                                <td class="px-4 py-3 text-xs font-medium text-slate-900 max-w-[200px] truncate"><?= htmlspecialchars($c->getTitle()) ?></td>
                                <td class="px-4 py-3"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold <?= $color ?>"><?= ucfirst($c->getStatus()->getValue()) ?></span></td>
                                <td class="px-4 py-3 text-[10px] text-slate-400"><?= $c->getCreatedAt()->format('M d, Y') ?></td>
                                <td class="px-4 py-3 text-center">
                                    <a href="<?= BASE_URL ?>/admin/consultations/view?id=<?= $c->getId() ?>" class="inline-block px-3 py-1.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-lg hover:bg-indigo-100 transition-colors">
                                        View & Assign
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>
