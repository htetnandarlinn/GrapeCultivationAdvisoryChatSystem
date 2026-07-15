<section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center gap-3 mb-8">
        <a href="<?= BASE_URL ?>/dashboard" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Consulting Farmers</h2>
            <p class="text-xs text-slate-500 mt-1">Farmers you are currently consulting with.</p>
        </div>
    </div>

    <?php if (empty($farmers)): ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
        <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        </div>
        <p class="text-sm font-semibold text-slate-600">No farmers yet</p>
        <p class="text-xs text-slate-400 mt-1">Farmers will appear here once consultations are assigned to you.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($farmers as $farmer):
            $farmerConsultations = array_filter($consultations, fn($c) => $c->getFarmerId() === $farmer->getId());
            $latestConsultation = !empty($farmerConsultations) ? reset($farmerConsultations) : null;
            $initial = strtoupper(substr($farmer->getUsername(), 0, 1));
        ?>
        <div class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">
            <div class="p-5">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center text-white font-bold text-lg shadow-sm overflow-hidden shrink-0">
                        <?php $pfp = $farmer->getProfileImage(); if ($pfp): ?>
                            <img src="<?= BASE_URL . htmlspecialchars($pfp) ?>" alt="" class="w-full h-full object-cover">
                        <?php else: ?>
                            <?= $initial ?>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-bold text-slate-900 truncate"><?= htmlspecialchars($farmer->getUsername()) ?></p>
                        <p class="text-[10px] text-slate-400"><?= count($farmerConsultations) ?> consultation<?= count($farmerConsultations) !== 1 ? 's' : '' ?></p>
                    </div>
                </div>
                <?php if ($latestConsultation): ?>
                <div class="mt-4 pt-4 border-t border-slate-50">
                    <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Last Consultation</p>
                    <p class="text-xs font-semibold text-slate-700 mt-1 truncate"><?= htmlspecialchars($latestConsultation->getTitle()) ?></p>
                    <p class="text-[10px] text-slate-400 mt-0.5"><?= $latestConsultation->getCreatedAt()->format('M d, Y') ?></p>
                </div>
                <?php endif; ?>
            </div>
            <a href="<?= BASE_URL ?>/expert/consultations/hub?id=<?= $latestConsultation?->getId() ?>" class="block px-5 py-3 bg-slate-50/80 text-center text-[10px] font-bold text-emerald-600 hover:bg-emerald-50 transition-colors">
                View Consultation
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</section>
