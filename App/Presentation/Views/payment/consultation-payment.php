<?php
$status = $consultation->getStatus()->getValue();
$isRenewal = $status === 'expired';
$idempotencyKey = bin2hex(random_bytes(32));
$price = 29.99;
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50/30 flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-5xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="<?= BASE_URL ?>/consultations" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors mb-3">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Back to Consultations
            </a>
            <div class="flex items-center justify-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fa-solid fa-leaf text-sm"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Checkout</h1>
            </div>
            <p class="text-sm text-slate-500">Complete your payment to <?= $isRenewal ? 'renew' : 'activate' ?> your consultation</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Left: Payment Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm overflow-hidden">
                    <!-- Form Header -->
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-900">Payment Method</h2>
                        <p class="text-[11px] text-slate-400 mt-0.5">Choose or enter your payment details</p>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="px-6 py-4 border-b border-slate-50">
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-3 px-4 py-3 rounded-xl border-2 border-emerald-500 bg-emerald-50/30 cursor-pointer flex-1">
                                <input type="radio" name="payment_method" value="card" checked class="accent-emerald-600">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-credit-card text-emerald-600 text-base"></i>
                                    <span class="text-xs font-semibold text-slate-700">Credit/Debit Card</span>
                                </div>
                            </label>
                            <div class="flex items-center gap-1.5 px-3">
                                <i class="fa-brands fa-cc-visa text-xl text-blue-600"></i>
                                <i class="fa-brands fa-cc-mastercard text-xl text-red-500"></i>
                                <i class="fa-brands fa-cc-amex text-xl text-blue-400"></i>
                                <i class="fa-solid fa-credit-card text-xl text-slate-300"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Card Form -->
                    <form method="POST" action="<?= BASE_URL ?>/payment/consultation/process" id="payment-form" class="p-6 space-y-5">
                        <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                        <input type="hidden" name="idempotency_key" value="<?= $idempotencyKey ?>">

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Card Number</label>
                            <div class="relative">
                                <input type="text" inputmode="numeric" pattern="[0-9\s]{13,19}" maxlength="19" placeholder="4242 4242 4242 4242" required
                                       class="w-full px-4 py-3 pr-11 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50/50 font-mono tracking-wider">
                                <i class="fa-regular fa-credit-card absolute right-4 top-1/2 -translate-y-1/2 text-slate-300 text-sm"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Expiry Date</label>
                                <input type="text" placeholder="MM/YY" maxlength="5" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50/50">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">CVC</label>
                                <input type="text" inputmode="numeric" pattern="[0-9]{3,4}" maxlength="4" placeholder="123" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50/50 font-mono">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Cardholder Name</label>
                            <input type="text" placeholder="John Doe" required
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50/50">
                        </div>

                        <!-- Security Badges -->
                        <div class="flex items-center gap-4 pt-2">
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-400">
                                <i class="fa-solid fa-lock text-emerald-500"></i>
                                <span>Secured with SSL</span>
                            </div>
                            <div class="flex items-center gap-1.5 text-[10px] text-slate-400">
                                <i class="fa-solid fa-shield-halved text-emerald-500"></i>
                                <span>256-bit encrypted</span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="pay-btn"
                                class="w-full py-4 px-6 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-2xl transition-all duration-200 shadow-lg shadow-emerald-200 hover:shadow-xl hover:shadow-emerald-300 active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                            <span id="pay-btn-text">
                                <?= $isRenewal ? '<i class="fa-solid fa-arrows-rotate mr-1.5"></i> Renew Payment — $' . number_format($price, 2) : '<i class="fa-solid fa-lock mr-1.5"></i> Pay $' . number_format($price, 2) ?>
                            </span>
                            <i id="pay-btn-spinner" class="fa-solid fa-spinner hidden animate-spin"></i>
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                        <p class="text-[10px] text-slate-400 text-center">
                            <i class="fa-regular fa-circle-check text-emerald-500 mr-1"></i>
                            Your payment is processed securely. You will have full access for 30 days.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-200/70 shadow-sm overflow-hidden sticky top-8">
                    <!-- Summary Header -->
                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-900">Order Summary</h2>
                    </div>

                    <?php if ($isRenewal): ?>
                    <!-- Renewal Notice -->
                    <div class="mx-5 mt-4 px-4 py-3 rounded-2xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-amber-800">Renewal Payment</p>
                            <p class="text-[10px] text-amber-600 mt-0.5">Your previous consultation period has ended. Renew to continue.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Consultation Info -->
                    <div class="px-6 py-4 space-y-3.5 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Consultation</span>
                            <span class="text-xs font-bold text-slate-900 font-mono">#<?= $consultation->getId() ?></span>
                        </div>
                        <div class="flex items-start justify-between">
                            <span class="text-xs text-slate-500">Title</span>
                            <span class="text-xs font-semibold text-slate-800 text-right max-w-[180px]"><?= htmlspecialchars($consultation->getTitle()) ?></span>
                        </div>
                        <?php if ($expert): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Expert</span>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-[8px] font-bold">
                                    <?= strtoupper(substr($expert->getUsername(), 0, 1)) ?>
                                </div>
                                <span class="text-xs font-semibold text-slate-700"><?= htmlspecialchars($expert->getUsername()) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Duration</span>
                            <span class="text-xs font-semibold text-slate-700"><i class="fa-regular fa-calendar text-emerald-500 mr-1"></i> 30 Days</span>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="px-6 py-4 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Consultation Fee</span>
                            <span class="text-slate-700">$<?= number_format($price, 2) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Service Fee</span>
                            <span class="text-emerald-600 font-medium">Free</span>
                        </div>
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Tax</span>
                            <span class="text-emerald-600 font-medium">Included</span>
                        </div>
                        <div class="border-t border-slate-100 pt-2.5 mt-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-900">Total</span>
                                <span class="text-lg font-black text-emerald-600">$<?= number_format($price, 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- What's Included -->
                    <div class="px-6 py-4 bg-emerald-50/40 border-t border-emerald-100/50">
                        <h3 class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mb-3">What's Included</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-2 text-[11px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                                <span>30-day access to your assigned expert</span>
                            </li>
                            <li class="flex items-start gap-2 text-[11px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                                <span>Unlimited messaging with expert</span>
                            </li>
                            <li class="flex items-start gap-2 text-[11px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                                <span>Image sharing for grape diagnosis</span>
                            </li>
                            <li class="flex items-start gap-2 text-[11px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[10px]"></i>
                                <span>Expert advice and recommendations</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('pay-btn');
    const btnText = document.getElementById('pay-btn-text');
    const spinner = document.getElementById('pay-btn-spinner');
    btn.disabled = true;
    btnText.innerHTML = 'Processing Payment...';
    spinner.classList.remove('hidden');
    btn.classList.add('opacity-75');
});

// Auto-format card number with spaces
document.querySelector('[placeholder="4242 4242 4242 4242"]')?.addEventListener('input', function(e) {
    let val = this.value.replace(/\D/g, '').substring(0, 16);
    val = val.replace(/(.{4})/g, '$1 ').trim();
    this.value = val;
});

// Auto-format expiry
document.querySelector('[placeholder="MM/YY"]')?.addEventListener('input', function(e) {
    let val = this.value.replace(/[^\d]/g, '').substring(0, 4);
    if (val.length >= 3) {
        val = val.substring(0, 2) + '/' + val.substring(2);
    }
    this.value = val;
});
</script>
