<?php
$consultation = $consultation ?? ($data['consultation'] ?? null);
if (! $consultation) {
    echo '<div class="min-h-screen bg-slate-50 flex items-center justify-center px-4 py-8">'
       . '<div class="w-full max-w-xl bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center">'
       . '<h1 class="text-2xl font-bold text-slate-900 mb-3">Unable to load consultation</h1>'
       . '<p class="text-sm text-slate-500 mb-6">Please return to your consultations and try again.</p>'
       . '<a href="' . BASE_URL . '/consultations" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-emerald-600 text-white text-sm font-semibold">Back to Consultations</a>'
       . '</div></div>';
    return;
}
$expert = $expert ?? null;
$status = $consultation->getStatus()->getValue();
$isRenewal = $status === 'expired';
$idempotencyKey = bin2hex(random_bytes(32));
$price = 29.99;
$payeePhone = '09-777-888-999';
$kpayPhone = '09-777-888-999';
$wavepayPhone = '09-777-888-999';
$paypalEmail = 'payments@grapecultivate.com';
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-emerald-50/30 flex items-start justify-center px-4 py-6">
    <div class="w-full max-w-5xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-5">
            <a href="<?= BASE_URL ?>/consultations" class="inline-flex items-center gap-1.5 text-[10px] font-semibold text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-[9px]"></i> Back to Consultations
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow shadow-emerald-200">
                    <i class="fa-solid fa-leaf text-[11px]"></i>
                </div>
                <h1 class="text-lg font-black text-slate-900 tracking-tight">Checkout</h1>
            </div>
            <span class="text-[10px] text-slate-400"><?= $isRenewal ? 'Renewal' : 'New Payment' ?></span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">
            <!-- Left: Payment Form -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden">
                    <!-- Payment Method Selection -->
                    <div class="px-4 py-3.5 border-b border-slate-100">
                        <h2 class="text-xs font-bold text-slate-900">Payment Method</h2>
                    </div>

                    <form method="POST" action="<?= BASE_URL ?>/payment/consultation/process" id="payment-form" enctype="multipart/form-data">
                        <input type="hidden" name="consultation_id" value="<?= $consultation->getId() ?>">
                        <input type="hidden" name="idempotency_key" value="<?= $idempotencyKey ?>">

                        <!-- Method Options -->
                        <div class="px-4 py-3 space-y-2.5 border-b border-slate-50">
                            <!-- KPay -->
                            <label class="payment-method group flex items-center gap-3 px-3.5 py-2.5 rounded-xl border-2 cursor-pointer transition-all duration-150 <?= $isRenewal ? '' : 'border-emerald-500 bg-emerald-50/30' ?>" data-method="kpay">
                                <input type="radio" name="payment_method" value="kpay" <?= $isRenewal ? '' : 'checked' ?> class="payment-radio accent-emerald-600">
                                <div class="flex items-center gap-2.5 flex-1">
                                    <img src="<?= BASE_URL ?>/images/payments/kpay.svg" alt="KPay" class="w-9 h-9 rounded-lg shrink-0">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-800">KPay</p>
                                        <p class="text-[9px] text-slate-400">Instant transfer</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Recommended</span>
                            </label>

                            <!-- Wave Pay -->
                            <label class="payment-method group flex items-center gap-3 px-3.5 py-2.5 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-150 hover:border-slate-300" data-method="wavepay">
                                <input type="radio" name="payment_method" value="wavepay" class="payment-radio accent-emerald-600">
                                <div class="flex items-center gap-2.5 flex-1">
                                    <img src="<?= BASE_URL ?>/images/payments/wavepay.svg" alt="Wave Pay" class="w-9 h-9 rounded-lg shrink-0">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-800">Wave Pay</p>
                                        <p class="text-[9px] text-slate-400">Mobile money</p>
                                    </div>
                                </div>
                            </label>

                            <!-- PayPal -->
                            <label class="payment-method group flex items-center gap-3 px-3.5 py-2.5 rounded-xl border-2 border-slate-200 cursor-pointer transition-all duration-150 hover:border-slate-300" data-method="paypal">
                                <input type="radio" name="payment_method" value="paypal" class="payment-radio accent-emerald-600">
                                <div class="flex items-center gap-2.5 flex-1">
                                    <img src="<?= BASE_URL ?>/images/payments/paypal.svg" alt="PayPal" class="w-9 h-9 rounded-lg shrink-0">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-800">PayPal</p>
                                        <p class="text-[9px] text-slate-400">International payment</p>
                                    </div>
                                </div>
                            </label>

                        </div>

                        <!-- Dynamic Payment Details -->
                        <div id="payment-details" class="px-4 py-3.5 space-y-3">
                            <!-- Local Methods: KPay, Wave Pay, PayPal -->
                            <div id="local-payment-info" class="hidden">
                                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 space-y-2.5">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                                            <i class="fa-solid fa-mobile-screen-button text-[11px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-semibold text-amber-800">Send payment to:</p>
                                            <p id="payee-phone-display" class="text-sm font-black text-amber-900 tracking-wide"><?= $kpayPhone ?></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2 text-[10px] text-amber-700 bg-amber-100/50 p-2 rounded-lg">
                                        <i class="fa-solid fa-circle-info mt-0.5 text-[9px]"></i>
                                        <span>After sending payment, upload the transaction screenshot below so admin can verify.</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-slate-600 uppercase tracking-wider mb-1.5">Transaction Screenshot <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <input type="file" name="transaction_image" id="transaction-image" accept="image/*" required
                                               class="w-full text-[11px] text-slate-600 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer border border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all bg-slate-50/50">
                                    </div>
                                    <p id="file-name" class="text-[9px] text-slate-400 mt-1 hidden flex items-center gap-1">
                                        <i class="fa-solid fa-check-circle text-emerald-500"></i> <span id="file-name-text"></span>
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- Submit -->
                        <div class="px-4 py-3.5 border-t border-slate-100">
                            <button type="submit" id="pay-btn"
                                    class="w-full py-3 px-5 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl transition-all duration-200 shadow shadow-emerald-200 hover:shadow-md hover:shadow-emerald-300 active:scale-[0.98] flex items-center justify-center gap-2 text-xs">
                                <span id="pay-btn-text">
                                    <?= $isRenewal ? '<i class="fa-solid fa-arrows-rotate mr-1.5"></i> Renew — $' . number_format($price, 2) : '<i class="fa-solid fa-lock mr-1.5"></i> Pay $' . number_format($price, 2) ?>
                                </span>
                                <i id="pay-btn-spinner" class="fa-solid fa-spinner hidden animate-spin"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-slate-200/70 shadow-sm overflow-hidden sticky top-6">
                    <div class="px-4 py-3.5 border-b border-slate-100">
                        <h2 class="text-xs font-bold text-slate-900">Order Summary</h2>
                    </div>

                    <?php if ($status === 'payment_submitted'): ?>
                    <div class="mx-3.5 mt-3 px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="fa-solid fa-hourglass-half text-[9px]"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-amber-800">Payment Submitted for Review</p>
                            <p class="text-[9px] text-amber-600">Your receipt is being reviewed by admin. You'll be notified once approved.</p>
                        </div>
                    </div>
                    <?php elseif ($status === 'accepted' || $status === 'chat_started'): ?>
                    <div class="mx-3.5 mt-3 px-3 py-2 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-emerald-800">Payment Approved</p>
                            <p class="text-[9px] text-emerald-600">Your consultation is active. Go to chat to start messaging.</p>
                        </div>
                    </div>
                    <?php elseif ($isRenewal): ?>
                    <div class="mx-3.5 mt-3 px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-2">
                        <div class="w-6 h-6 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                            <i class="fa-solid fa-clock-rotate-left text-[9px]"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-amber-800">Renewal Payment</p>
                            <p class="text-[9px] text-amber-600">Your previous period ended. Renew to continue.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="px-4 py-3 space-y-2 border-b border-slate-50">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-500">Consultation</span>
                            <span class="text-[10px] font-bold text-slate-900 font-mono">#<?= $consultation->getId() ?></span>
                        </div>
                        <div class="flex items-start justify-between">
                            <span class="text-[10px] text-slate-500">Title</span>
                            <span class="text-[10px] font-semibold text-slate-800 text-right max-w-[160px]"><?= htmlspecialchars($consultation->getTitle()) ?></span>
                        </div>
                        <?php if ($expert): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-500">Expert</span>
                            <div class="flex items-center gap-1.5">
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white text-[7px] font-bold">
                                    <?= strtoupper(substr($expert->getUsername(), 0, 1)) ?>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-700"><?= htmlspecialchars($expert->getUsername()) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-slate-500">Duration</span>
                            <span class="text-[10px] font-semibold text-slate-700">30 Days</span>
                        </div>
                    </div>

                    <div class="px-4 py-3 space-y-1.5 border-b border-slate-50">
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-500">Consultation Fee</span>
                            <span class="text-slate-700">$<?= number_format($price, 2) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-500">Service Fee</span>
                            <span class="text-emerald-600 font-medium">Free</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px]">
                            <span class="text-slate-500">Tax</span>
                            <span class="text-emerald-600 font-medium">Included</span>
                        </div>
                        <div class="border-t border-slate-100 pt-2 mt-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900">Total</span>
                                <span class="text-base font-black text-emerald-600">$<?= number_format($price, 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-emerald-50/40">
                        <h3 class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider mb-2">What's Included</h3>
                        <ul class="space-y-1.5">
                            <li class="flex items-start gap-1.5 text-[10px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[8px]"></i>
                                <span>30-day access to your expert</span>
                            </li>
                            <li class="flex items-start gap-1.5 text-[10px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[8px]"></i>
                                <span>Unlimited messaging with expert</span>
                            </li>
                            <li class="flex items-start gap-1.5 text-[10px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[8px]"></i>
                                <span>Image sharing for grape diagnosis</span>
                            </li>
                            <li class="flex items-start gap-1.5 text-[10px] text-emerald-700">
                                <i class="fa-solid fa-check text-emerald-500 mt-0.5 text-[8px]"></i>
                                <span>Expert advice &amp; recommendations</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <p class="text-center text-[9px] text-slate-400 mt-4">
            <i class="fa-solid fa-lock text-emerald-400 mr-1"></i>
            Secured payment via encrypted connection. Your information is safe with us.
        </p>
    </div>
</div>

<script>
// Payment method toggle
const radios = document.querySelectorAll('.payment-radio');
const localInfo = document.getElementById('local-payment-info');
const payeeDisplay = document.getElementById('payee-phone-display');
const fileInput = document.getElementById('transaction-image');
const fileName = document.getElementById('file-name');
const fileNameText = document.getElementById('file-name-text');

const phoneMap = {
    kpay: '<?= $kpayPhone ?>',
    wavepay: '<?= $wavepayPhone ?>',
    paypal: '<?= $paypalEmail ?>'
};

function updatePaymentMethod(method) {
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('border-emerald-500', 'bg-emerald-50/30');
        el.classList.add('border-slate-200');
        if (el.dataset.method === method) {
            el.classList.remove('border-slate-200');
            el.classList.add('border-emerald-500', 'bg-emerald-50/30');
        }
    });

    localInfo.classList.remove('hidden');
    payeeDisplay.textContent = phoneMap[method] || '<?= $kpayPhone ?>';
    fileInput.setAttribute('required', '');
}

radios.forEach(r => {
    r.addEventListener('change', function() {
        if (this.checked) updatePaymentMethod(this.value);
    });
});

// Show selected method on page load
const checkedRadio = document.querySelector('.payment-radio:checked');
if (checkedRadio) updatePaymentMethod(checkedRadio.value);

// File name display
fileInput?.addEventListener('change', function() {
    if (this.files.length > 0) {
        fileNameText.textContent = this.files[0].name;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
});

// Form submit
document.getElementById('payment-form').addEventListener('submit', function(e) {
    const btn = document.getElementById('pay-btn');
    const btnText = document.getElementById('pay-btn-text');
    const spinner = document.getElementById('pay-btn-spinner');
    btn.disabled = true;
    btnText.innerHTML = 'Processing...';
    spinner.classList.remove('hidden');
    btn.classList.add('opacity-75');
});
</script>
