<?php
/** @var \App\Domain\UserManagement\Entities\User $expert */
?>

<main class="flex-grow px-4 sm:px-6 pb-12 pt-20 max-w-4xl mx-auto space-y-5">
    
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0F172A] tracking-tight">Farmer Details</h1>
            <p class="text-sm text-slate-500 mt-1">Review farmer profile information and account status.</p>
        </div>
        <a href="<?= BASE_URL ?>/admin/experts" class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#16A34A] bg-[#DCFCE7] hover:bg-[#BBF7D0] px-3 py-1.5 rounded-full transition-all duration-200">
            <span>←</span> Back to list
        </a>
    </div>

    <div class="bg-white rounded-[1.5rem] p-6 sm:p-8 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-100">
        
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0">
                <?php if (!empty($expert->getProfileImage())): ?>
                    <img src="<?= BASE_URL . htmlspecialchars($expert->getProfileImage()) ?>" alt="<?= htmlspecialchars($expert->getUsername()) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <span class="text-xl font-bold text-slate-400"><?= htmlspecialchars(strtoupper(substr($expert->getUsername(), 0, 1))) ?></span>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Profile</h2>
                <p class="text-base font-bold text-[#0F172A] mt-0.5"><?= htmlspecialchars($expert->getUsername()) ?></p>
                <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($expert->getAddress() ?: 'Nay Pyi Taw') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-5">
            
            <div class="space-y-4">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Name</h3>
                    <p class="mt-1 text-sm font-bold text-[#0F172A]"><?= htmlspecialchars($expert->getUsername()) ?></p>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600 break-all"><?= htmlspecialchars($expert->getEmail()->getValue()) ?></p>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Phone</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600"><?= htmlspecialchars($expert->getPhoneNumber()) ?></p>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Address</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600"><?= htmlspecialchars($expert->getAddress() ?: 'Nay Pyi Taw') ?></p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Role</h3>
                    <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold bg-[#F1F5F9] text-[#475569] capitalize">
                        <?= htmlspecialchars($expert->getType()->getValue()) ?>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</h3>
                    <div class="mt-1 inline-flex items-center gap-1.5 text-sm font-bold text-[#16A34A] capitalize">
                        <span class="h-2 w-2 rounded-full bg-[#16A34A]"></span>
                        <?= htmlspecialchars($expert->getStatus()->getValue()) ?>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Created At</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600"><?= htmlspecialchars($expert->getCreatedAt()->format('F d, Y • H:i')) ?></p>
                </div>
                
                <div>
                    <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Last Updated</h3>
                    <p class="mt-1 text-sm font-medium text-slate-600"><?= $expert->getUpdatedAt() ? htmlspecialchars($expert->getUpdatedAt()->format('F d, Y • H:i')) : 'N/A' ?></p>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-5 border-t border-slate-100 flex flex-wrap items-center gap-3">
            <a href="<?= BASE_URL ?>/admin/experts" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition active:scale-98">
                Return to list
            </a>
            
            <form id="delete-expert-form" action="<?= BASE_URL ?>/admin/experts/delete" method="POST" class="inline-block">
                <input type="hidden" name="id" value="<?= htmlspecialchars($expert->getId()) ?>">
                <button type="button" onclick="confirmDelete()" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-[#E11D48] hover:bg-[#BE123C] px-5 py-2.5 text-xs font-bold text-white transition active:scale-98 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete Farmer
                </button>
            </form>
        </div>
    </div>
</main>

<script>
    function confirmDelete() {
        if (confirm('Delete this expert permanently?')) {
            document.getElementById('delete-expert-form').submit();
        }
    }
</script>