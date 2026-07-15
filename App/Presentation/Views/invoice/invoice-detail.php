<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center justify-between mb-5">
        <?php $backUrl = ($_SESSION['user_role'] ?? '') === 'admin' ? '/admin/payments' : '/payment/history'; ?>
        <a href="<?= BASE_URL . $backUrl ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-slate-600 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <a href="<?= BASE_URL ?>/invoice/download?consultation_id=<?= $consultationId ?>"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
            <i class="fa-regular fa-file-pdf"></i> Download PDF
        </a>
    </div>

    <?php if (!empty($error)): ?>
    <div class="mb-5 p-3.5 rounded-lg bg-red-50 border border-red-200 text-xs font-semibold text-red-700 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-red-500"></i> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($invoice)): ?>
    <!-- Invoice Document -->
    <div class="bg-white rounded-lg border border-slate-300 shadow-sm">

        <!-- Header with Logo -->
        <div class="px-9 pt-9 pb-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo" class="w-12 h-12 object-contain" />
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Grape Cultivation Advisory Chat System</h2>
                        <p class="text-[10px] text-slate-400 mt-0.5">Consultation Services</p>
                        <div class="mt-2.5 text-[9px] text-slate-400 leading-relaxed">
                            <p>support@grapeadvisory.com &bull; +95 9 123456789</p>
                            <p>Dept of Information Technology, Your University, Myanmar</p>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xl font-black text-slate-800 tracking-[2px]">INVOICE</p>
                    <div class="mt-1.5 flex items-baseline justify-end gap-2 text-[11px]">
                        <span class="text-[9px] font-semibold text-slate-400 uppercase">Invoice No:</span>
                        <span class="font-bold text-slate-700"><?= htmlspecialchars($invoice['invoiceNo']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-slate-200 mx-9">

        <!-- Info Grid -->
        <div class="px-9 py-5">
            <div class="grid grid-cols-4 gap-5 gap-y-3 text-xs">
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Consultation No.</p>
                    <p class="font-bold text-slate-800">#<?= $invoice['consultationId'] ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Farmer</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['farmerName']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Expert</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['expertName']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Consultation Topic</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['consultationTitle']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Payment Method</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['paymentMethod']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Transaction Reference</p>
                    <p class="font-semibold text-slate-800 font-mono text-[7px] break-all leading-relaxed"><?= htmlspecialchars($invoice['transactionRef']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Payment Date</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['paymentDate']) ?></p>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                    <?php
                        $scMap = [
                            'Paid' => 'bg-emerald-100 text-emerald-700',
                            'Pending' => 'bg-amber-100 text-amber-700',
                            'Refunded' => 'bg-pink-100 text-pink-700',
                            'Rejected' => 'bg-red-100 text-red-700',
                        ];
                        $sc = $scMap[$invoice['status']] ?? 'bg-slate-100 text-slate-600';
                    ?>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase <?= $sc ?>"><?= htmlspecialchars($invoice['status']) ?></span>
                </div>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Generated By</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['generatedBy']) ?></p>
                </div>
                <?php if (!empty($invoice['refundStatus']) && $invoice['refundStatus'] === 'REFUNDED'): ?>
                <div class="col-span-1">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Refund Date</p>
                    <p class="font-semibold text-slate-800"><?= htmlspecialchars($invoice['refundDate'] ?? '') ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <hr class="border-slate-200 mx-9">

        <!-- Items -->
        <div class="px-9 py-5">
            <table class="w-full text-[11px]">
                <thead>
                    <tr class="border-b border-slate-300">
                        <th class="text-left pb-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider w-[60%]">Description</th>
                        <th class="text-left pb-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider w-[20%]">Type</th>
                        <th class="text-center pb-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider w-[10%]">Qty</th>
                        <th class="text-right pb-2.5 text-[9px] font-bold text-slate-400 uppercase tracking-wider w-[10%]">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="py-3 text-slate-700">Consultation Service — <?= htmlspecialchars($invoice['consultationTitle'])?></td>
                        <td class="py-3 text-slate-500">Advisory</td>
                        <td class="py-3 text-center text-slate-700 font-semibold">1</td>
                        <td class="py-3 text-right font-semibold text-slate-700">$<?= htmlspecialchars($invoice['formattedAmount']) ?></td>
                    </tr>
                    <tr>
                        <td class="py-3 text-slate-700 border-t border-slate-100">Platform fee &amp; expert matching</td>
                        <td class="py-3 text-slate-500 border-t border-slate-100">Service</td>
                        <td class="py-3 text-center text-slate-700 font-semibold border-t border-slate-100">1</td>
                        <td class="py-3 text-right font-semibold text-slate-700 border-t border-slate-100">$0.00</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="px-9 pb-5">
            <div class="ml-auto w-56">
                <div class="flex justify-between py-1 text-[11px] text-slate-500">
                    <span>Subtotal</span>
                    <span class="font-semibold text-slate-700">$<?= htmlspecialchars($invoice['formattedAmount']) ?></span>
                </div>
                <div class="flex justify-between py-1 text-[11px] text-slate-500">
                    <span>Tax (0%)</span>
                    <span class="font-semibold text-slate-700">$0.00</span>
                </div>
                <div class="border-t border-slate-300 my-1.5"></div>
                <div class="flex justify-between pt-1">
                    <span class="text-xs font-bold text-slate-800">Total</span>
                    <span class="text-sm font-bold text-slate-800">$<?= htmlspecialchars($invoice['formattedAmount']) ?></span>
                </div>
            </div>
        </div>

        <hr class="border-slate-200 mx-9">

        <!-- Footer Message -->
        <div class="px-9 py-6 text-center">
            <p class="text-xs font-semibold text-slate-700">Thank you for using the<br/>Grape Cultivation Advisory Chat System.</p>
            <p class="text-[10px] text-slate-400 mt-2 leading-relaxed">This invoice serves as official proof of payment<br/>for your consultation service.</p>
            <p class="text-[9px] text-slate-300 mt-3">Generated electronically. No signature is required.</p>
        </div>

        <div class="bg-slate-50 border-t border-slate-200 px-9 py-3 text-center text-[9px] text-slate-400">
            <?= htmlspecialchars($invoice['invoiceNo']) ?> &bull; Generated <?= htmlspecialchars($invoice['generatedDate']) ?>
        </div>
    </div>
    <?php endif; ?>
</section>
