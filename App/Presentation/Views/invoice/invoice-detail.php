<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .inv-page { max-width: 700px; margin: 0 auto; }
    .inv-back { display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #94a3b8; text-decoration: none; transition: color 0.15s; }
    .inv-back:hover { color: #475569; }
    .inv-dl { display: inline-flex; align-items: center; gap: 6px; padding: 10px 22px; background: #059669; color: #fff; font-size: 11px; font-weight: 700; border-radius: 8px; text-decoration: none; transition: background 0.15s; }
    .inv-dl:hover { background: #047857; }
    .inv-card { border: 1.5px solid #cbd5e1; }
    .inv-inner { padding: 28px 32px 20px 32px; }
    .inv-logo-cell { text-align: left; }
    .inv-logo-img { max-width: 44px; max-height: 44px; }
    .inv-cname { font-size: 14px; font-weight: 800; color: #1e293b; margin: 0 0 1px 0; }
    .inv-ctag { font-size: 8px; color: #94a3b8; margin: 0; }
    .inv-cinfo { font-size: 7.5px; color: #94a3b8; margin: 4px 0 0 0; line-height: 1.7; }
    .inv-title { font-size: 20px; font-weight: 900; color: #1e293b; letter-spacing: 2px; margin: 0; }
    .inv-no-lbl { font-size: 8px; font-weight: 700; color: #94a3b8; text-transform: uppercase; }
    .inv-no-val { font-size: 10px; font-weight: 700; color: #1e293b; }
    .inv-sep { border: none; border-top: 1.5px solid #e2e8f0; margin: 14px 0; }
    .inv-label { font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; color: #94a3b8; margin-bottom: 1px; }
    .inv-value { font-size: 10px; font-weight: 600; color: #1e293b; }
    .inv-value-mono { font-size: 7px; font-weight: 600; color: #1e293b; font-family: 'Courier New', monospace; word-wrap: break-word; }
    .inv-badge { display: inline-block; padding: 1px 10px; font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.4px; }
    .inv-badge-paid { background: #d1fae5; color: #065f46; }
    .inv-badge-pending { background: #fef3c7; color: #92400e; }
    .inv-badge-refunded { background: #fce7f3; color: #9d174d; }
    .inv-badge-rejected { background: #fee2e2; color: #991b1b; }
    .inv-th { font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; padding: 5px 8px; border-bottom: 1.5px solid #cbd5e1; text-align: left; }
    .inv-th-ar { text-align: right; }
    .inv-th-ac { text-align: center; }
    .inv-td { padding: 6px 8px; font-size: 9.5px; color: #334155; border-bottom: 1px solid #f1f5f9; }
    .inv-td-ar { text-align: right; font-weight: 600; }
    .inv-td-ac { text-align: center; }
    .inv-tbl-end tr:last-child td { border-bottom: 1.5px solid #cbd5e1; }
    .inv-tot-lbl { text-align: right; color: #64748b; padding: 2px 8px; font-size: 9.5px; }
    .inv-tot-val { text-align: right; font-weight: 600; color: #334155; padding: 2px 8px; font-size: 9.5px; }
    .inv-tot-gt-lbl { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.3px; color: #1e293b; }
    .inv-tot-gt-val { font-size: 14px; font-weight: 900; color: #1e293b; }
    .inv-tot-line { border-top: 2px solid #1e293b; margin: 2px 8px 2px auto; width: 20%; }
    .inv-footer { text-align: center; padding: 14px 0 4px 0; }
    .inv-footer .thanks { font-size: 11px; font-weight: 700; color: #1e293b; margin: 0 0 4px 0; }
    .inv-footer .sub { font-size: 9px; color: #94a3b8; margin: 0; line-height: 1.6; }
    .inv-footer .small { font-size: 8px; color: #cbd5e1; margin: 6px 0 0 0; }
    .inv-bar { text-align: center; padding: 8px 32px; border-top: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 7px; color: #94a3b8; }
</style>

<section class="inv-page">
    <div class="d-flex align-items-center justify-content-between mb-3" style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
        <?php $backUrl = ($_SESSION['user_role'] ?? '') === 'admin' ? '/admin/payments' : '/payment/history'; ?>
        <a href="<?= BASE_URL . $backUrl ?>" class="p-2.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition inline-flex">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <a href="<?= BASE_URL ?>/invoice/download?consultation_id=<?= $consultationId ?>" class="inv-dl"><i class="fa-regular fa-file-pdf"></i> Download PDF</a>
    </div>

    <?php if (!empty($error)): ?>
    <div class="mb-3" style="margin-bottom:12px;padding:10px 14px;border-radius:6px;background:#fef2f2;border:1px solid #fecaca;font-size:11px;font-weight:600;color:#dc2626;display:flex;align-items:center;gap:6px;">
        <i class="fa-solid fa-circle-exclamation" style="color:#ef4444;"></i> <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($invoice)): ?>
    <?php
        $statusClass = 'inv-badge-' . strtolower($invoice['status']);
        $genDate = date('d M Y');
    ?>
    <div class="inv-card" style="background:#fff;">
        <div class="inv-inner">
            <!-- Header with Logo -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="inv-logo-cell" width="65%" style="vertical-align:top;">
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="padding-right:10px;vertical-align:middle;">
                                    <img class="inv-logo-img" src="<?= BASE_URL ?>/assets/images/logo.png" alt="" />
                                </td>
                                <td style="vertical-align:middle;">
                                    <p class="inv-cname">Grape Cultivation Advisory Chat System</p>
                                    <p class="inv-ctag">Consultation Services</p>
                                    <p class="inv-cinfo">support@grapeadvisory.com &bull; +95 9 123456789</p>
                                    <p class="inv-cinfo">Dept of Information Technology, Your University, Myanmar</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="35%" style="text-align:right;vertical-align:top;">
                        <p class="inv-title">INVOICE</p>
                        <div style="margin-top:3px;">
                            <span class="inv-no-lbl">Invoice No: </span>
                            <span class="inv-no-val"><?= htmlspecialchars($invoice['invoiceNo']) ?></span>
                        </div>
                    </td>
                </tr>
            </table>

            <hr class="inv-sep" />

            <!-- Info Grid (3 columns) -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="33%" style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Consultation No.</div><div class="inv-value">#<?= htmlspecialchars($invoice['consultationId']) ?></div></td>
                    <td width="33%" style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Farmer</div><div class="inv-value"><?= htmlspecialchars($invoice['farmerName']) ?></div></td>
                    <td width="33%" style="vertical-align:top;padding:4px 0;"><div class="inv-label">Expert</div><div class="inv-value"><?= htmlspecialchars($invoice['expertName']) ?></div></td>
                </tr>
                <tr>
                    <td style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Consultation Topic</div><div class="inv-value"><?= htmlspecialchars($invoice['consultationTitle']) ?></div></td>
                    <td style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Payment Method</div><div class="inv-value"><?= htmlspecialchars($invoice['paymentMethod']) ?></div></td>
                    <td style="vertical-align:top;padding:4px 0;"><div class="inv-label">Transaction Reference</div><div class="inv-value-mono"><?= htmlspecialchars($invoice['transactionRef']) ?></div></td>
                </tr>
                <tr>
                    <td style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Payment Date</div><div class="inv-value"><?= htmlspecialchars($invoice['paymentDate']) ?></div></td>
                    <td style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Status</div><div class="inv-value"><span class="inv-badge <?= $statusClass ?>"><?= htmlspecialchars($invoice['status']) ?></span></div></td>
                    <td style="vertical-align:top;padding:4px 0;"><div class="inv-label">Generated By</div><div class="inv-value"><?= htmlspecialchars($invoice['generatedBy']) ?></div></td>
                </tr>
                <tr>
                    <?php if (!empty($invoice['refundStatus']) && $invoice['refundStatus'] === 'REFUNDED'): ?>
                    <td style="vertical-align:top;padding:4px 16px 4px 0;"><div class="inv-label">Refund Date</div><div class="inv-value"><?= htmlspecialchars($invoice['refundDate'] ?? '') ?></div></td>
                    <td></td>
                    <?php else: ?>
                    <td></td>
                    <td></td>
                    <?php endif; ?>
                    <td></td>
                </tr>
            </table>

            <hr class="inv-sep" />

            <!-- Items Table -->
            <table width="100%" cellpadding="0" cellspacing="0" class="inv-tbl-end">
                <thead>
                    <tr>
                        <th width="55%" class="inv-th">Description</th>
                        <th width="20%" class="inv-th inv-th-ac">Type</th>
                        <th width="10%" class="inv-th inv-th-ac">Qty</th>
                        <th width="15%" class="inv-th inv-th-ar">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="inv-td">Consultation Service &mdash; <?= htmlspecialchars($invoice['consultationTitle']) ?></td>
                        <td class="inv-td inv-td-ac">Advisory</td>
                        <td class="inv-td inv-td-ac">1</td>
                        <td class="inv-td inv-td-ar">$ <?= htmlspecialchars($invoice['formattedAmount']) ?></td>
                    </tr>
                    <tr>
                        <td class="inv-td">Platform fee &amp; expert matching</td>
                        <td class="inv-td inv-td-ac">Service</td>
                        <td class="inv-td inv-td-ac">1</td>
                        <td class="inv-td inv-td-ar">$0.00</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="inv-tot-lbl" style="width:80%;">Subtotal</td>
                    <td class="inv-tot-val" style="width:20%;">$ <?= htmlspecialchars($invoice['formattedAmount']) ?></td>
                </tr>
                <tr>
                    <td class="inv-tot-lbl">Tax (0%)</td>
                    <td class="inv-tot-val">$0.00</td>
                </tr>
                <tr><td colspan="2"><div class="inv-tot-line"></div></td></tr>
                <tr>
                    <td class="inv-tot-lbl inv-tot-gt-lbl">Total</td>
                    <td class="inv-tot-val inv-tot-gt-val">$ <?= htmlspecialchars($invoice['formattedAmount']) ?></td>
                </tr>
            </table>

            <hr class="inv-sep" />

            <!-- Footer Message -->
            <div class="inv-footer">
                <p class="thanks">Thank you for using the<br/>Grape Cultivation Advisory Chat System.</p>
                <p class="sub">This invoice serves as official proof of payment<br/>for your consultation service.</p>
                <p class="small">Generated electronically. No signature is required.</p>
            </div>
        </div>

        <div class="inv-bar">
            <?= htmlspecialchars($invoice['invoiceNo']) ?> &bull; Generated <?= $genDate ?>
        </div>
    </div>
    <?php endif; ?>
</section>
