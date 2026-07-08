<?php
/** @var \App\Domain\UserManagement\Entities\User $farmer */
?>

<style>
    /* Custom Keyframes for Modern Entrance Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<main class="flex-grow px-4 sm:px-6 pb-10 pt-20 md:pt-24 max-w-3xl mx-auto w-full">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 opacity-0 animate-fade-in-down">
        <div class="space-y-1">
            <h1 class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">Farmer Details</h1>
            <p class="text-xs md:text-sm text-slate-500">Review farmer profile information and account status.</p>
        </div>
        
        <a href="<?= BASE_URL ?>/admin/farmers" class="group flex items-center gap-1.5 text-xs font-semibold text-[#15803D] hover:text-green-900 transition-colors duration-300 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-full">
            <span class="transform transition-transform duration-300 group-hover:-translate-x-1">&larr;</span> 
            Back to list
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-shadow duration-500 opacity-0 animate-fade-in-up" style="animation-delay: 100ms;">
        
        <div class="flex flex-col sm:flex-row items-start gap-4 mb-6">
            <div class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                <?php if (!empty($farmer->getProfileImage())): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($farmer->getProfileImage()) ?>" alt="<?= htmlspecialchars($farmer->getUsername()) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-lg font-semibold text-slate-500"><?= htmlspecialchars(strtoupper(substr($farmer->getUsername(), 0, 1))) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Profile</h2>
                <p class="text-lg font-bold text-slate-900"><?= htmlspecialchars($farmer->getUsername()) ?></p>
                <p class="text-sm text-slate-600 mt-1"><?= htmlspecialchars($farmer->getAddress() ?: 'No address provided') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
            <div class="space-y-6">
                <div class="group">
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Name</h2>
                    <p class="text-lg font-bold text-slate-900 group-hover:text-[#15803D] transition-colors duration-300"><?= htmlspecialchars($farmer->getUsername()) ?></p>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Email</h2>
                    <p class="text-sm text-slate-600 truncate"><a href="mailto:<?= htmlspecialchars($farmer->getEmail()->getValue()) ?>" class="hover:text-[#15803D] transition-colors"><?= htmlspecialchars($farmer->getEmail()->getValue()) ?></a></p>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Phone</h2>
                    <p class="text-sm text-slate-600"><a href="tel:<?= htmlspecialchars($farmer->getPhoneNumber()) ?>" class="hover:text-[#15803D] transition-colors"><?= htmlspecialchars($farmer->getPhoneNumber()) ?></a></p>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Address</h2>
                    <p class="text-sm text-slate-600 leading-relaxed"><?= htmlspecialchars($farmer->getAddress() ?? 'Not provided') ?></p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Role</h2>
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold capitalize">
                        <?= htmlspecialchars($farmer->getType()->getValue()) ?>
                    </div>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</h2>
                    <div class="inline-flex items-center gap-1.5">
                        <span class="relative flex h-2.5 w-2.5">
                            <?php if($farmer->getStatus()->isActive()): ?>
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#15803D] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#15803D]"></span>
                            <?php elseif($farmer->getStatus()->isBlocked()): ?>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                            <?php else: ?>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                            <?php endif; ?>
                        </span>
                        <p class="text-sm font-bold <?= $farmer->getStatus()->isActive() ? 'text-[#15803D]' : ($farmer->getStatus()->isBlocked() ? 'text-rose-600' : 'text-amber-600') ?> capitalize">
                            <?= htmlspecialchars($farmer->getStatus()->getValue()) ?>
                        </p>
                    </div>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Created At</h2>
                    <p class="text-sm text-slate-600"><?= htmlspecialchars($farmer->getCreatedAt()->format('F d, Y • H:i')) ?></p>
                </div>
                <div>
                    <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Last Updated</h2>
                    <p class="text-sm text-slate-600"><?= $farmer->getUpdatedAt() ? htmlspecialchars($farmer->getUpdatedAt()->format('F d, Y • H:i')) : 'Never updated' ?></p>
                </div>
            </div>
        </div>

        <hr class="my-6 border-slate-100">

        <div class="flex flex-col sm:flex-row items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/farmers" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border-2 border-slate-200 px-5 py-2 text-sm font-bold text-slate-600 hover:text-slate-900 hover:border-slate-300 hover:bg-slate-50 transform hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-slate-100 outline-none">
                Return to list
            </a>
            
            <form action="<?= BASE_URL ?>/admin/farmers/delete" method="POST" class="w-full sm:w-auto inline-block" id="deleteFarmerForm">
                <input type="hidden" name="id" value="<?= htmlspecialchars($farmer->getId()) ?>">
                <button type="button" onclick="confirmDeletion()" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg bg-rose-600 px-5 py-2 text-sm font-bold text-white shadow-sm hover:bg-rose-700 hover:shadow-md transform hover:-translate-y-0.5 transition-all duration-300 focus:ring-4 focus:ring-rose-200 outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Farmer
                </button>
            </form>
        </div>
    </div>
</main>

<script>
    function confirmDeletion() {
        if (confirm('Are you sure you want to delete this farmer permanently? This action cannot be undone.')) {
            document.getElementById('deleteFarmerForm').submit();
        }
    }
</script>