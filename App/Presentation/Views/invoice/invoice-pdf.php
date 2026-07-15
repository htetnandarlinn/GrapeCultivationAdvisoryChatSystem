<?php

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');

$dompdf = new Dompdf($options);

$logoPath = __DIR__ . '/../../../../public/assets/images/logo.png';
$logoBase64 = '';
if (file_exists($logoPath)) {
    $imageData = base64_encode(file_get_contents($logoPath));
    $logoBase64 = 'data:image/png;base64,' . $imageData;
}

$generatedDate = date('d M Y');
$statusClass = 'status-' . strtolower($data['status']);

$refundBlock = '';
if (!empty($data['refundStatus']) && $data['refundStatus'] === 'REFUNDED') {
    $refundBlock = '<div class="grid-item">
        <div class="label">Refund Date</div>
        <div class="value">' . htmlspecialchars($data['refundDate'] ?? '') . '</div>
    </div>';
}

$html = <<<HTML
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
    @page { margin: 28px 32px; }
    body {
        font-family: 'DejaVu Sans', sans-serif;
        font-size: 10px;
        color: #2d2d2d;
        margin: 0;
        padding: 0;
        line-height: 1.5;
    }
    .page {
        max-width: 700px;
        margin: 0 auto;
        border: 1.5px solid #cbd5e1;
    }
    .inner { padding: 28px 32px 20px 32px; }
    .header-row {
        width: 100%;
    }
    .header-row td { vertical-align: top; padding: 0; }
    .logo-cell { text-align: left; }
    .logo-img { max-width: 44px; max-height: 44px; }
    .company-name {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 1px 0;
    }
    .company-tag {
        font-size: 8px;
        color: #94a3b8;
        margin: 0;
    }
    .contact-info {
        font-size: 7.5px;
        color: #94a3b8;
        margin: 4px 0 0 0;
        line-height: 1.7;
    }
    .invoice-cell { text-align: right; }
    .invoice-title {
        font-size: 20px;
        font-weight: 900;
        color: #1e293b;
        letter-spacing: 2px;
        margin: 0;
    }
    .invoice-no-label {
        font-size: 8px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        display: inline-block;
        margin-top: 3px;
    }
    .invoice-no-value {
        font-size: 10px;
        font-weight: 700;
        color: #1e293b;
        display: inline-block;
        margin-left: 4px;
    }
    .sep {
        border: none;
        border-top: 1.5px solid #e2e8f0;
        margin: 14px 0;
    }
    .grid-3 {
        width: 100%;
        border-collapse: collapse;
    }
    .grid-3 td {
        vertical-align: top;
        padding: 4px 16px 4px 0;
    }
    .grid-3 td:last-child { padding-right: 0; }
    .grid-item .label {
        font-size: 7.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #94a3b8;
        margin-bottom: 1px;
    }
    .grid-item .value {
        font-size: 10px;
        font-weight: 600;
        color: #1e293b;
    }
    .grid-item .value-mono {
        font-size: 7px;
        font-weight: 600;
        color: #1e293b;
        font-family: 'DejaVu Sans Mono', monospace;
        word-wrap: break-word;
    }
    .status-badge {
        display: inline-block;
        padding: 1px 10px;
        font-size: 7.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-pending { background: #fef3c7; color: #92400e; }
    .status-refunded { background: #fce7f3; color: #9d174d; }
    .status-rejected { background: #fee2e2; color: #991b1b; }
    .item-tbl {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2px;
    }
    .item-tbl th {
        font-size: 7.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #94a3b8;
        padding: 5px 8px;
        border-bottom: 1.5px solid #cbd5e1;
        text-align: left;
    }
    .item-tbl th.ar { text-align: right; }
    .item-tbl th.ac { text-align: center; }
    .item-tbl td {
        padding: 6px 8px;
        font-size: 9.5px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }
    .item-tbl td.ar { text-align: right; font-weight: 600; }
    .item-tbl td.ac { text-align: center; }
    .item-tbl tr:last-child td { border-bottom: 1.5px solid #cbd5e1; }
    .total-tbl {
        width: 100%;
        border-collapse: collapse;
    }
    .total-tbl td {
        padding: 2px 8px;
        font-size: 9.5px;
    }
    .total-tbl .lbl { text-align: right; color: #64748b; width: 80%; }
    .total-tbl .val { text-align: right; font-weight: 600; color: #334155; width: 20%; }
    .total-tbl .gt-lbl {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #1e293b;
    }
    .total-tbl .gt-val {
        font-size: 14px;
        font-weight: 900;
        color: #1e293b;
    }
    .total-tbl .sep-line td { padding: 0; }
    .total-tbl .sep-line div {
        border-top: 2px solid #1e293b;
        margin: 2px 8px 2px auto;
        width: 20%;
    }
    .footer-msg {
        text-align: center;
        padding: 14px 0 4px 0;
    }
    .footer-msg .thanks {
        font-size: 11px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px 0;
    }
    .footer-msg .sub {
        font-size: 9px;
        color: #94a3b8;
        margin: 0;
        line-height: 1.6;
    }
    .footer-msg .small {
        font-size: 8px;
        color: #cbd5e1;
        margin: 6px 0 0 0;
    }
    .footer-bar {
        text-align: center;
        padding: 8px 32px;
        border-top: 1.5px solid #e2e8f0;
        background: #f8fafc;
        font-size: 7px;
        color: #94a3b8;
    }
</style>
</head>
<body>
<div class="page">
<div class="inner">

    <!-- Header with Logo -->
    <table class="header-row" cellpadding="0" cellspacing="0">
        <tr>
            <td class="logo-cell" width="65%">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding-right: 10px; vertical-align: middle;">
HTML;
if ($logoBase64):
$html .= <<<HTML
                            <img class="logo-img" src="{$logoBase64}" alt="" />
HTML;
endif;
$html .= <<<HTML
                        </td>
                        <td style="vertical-align: middle;">
                            <p class="company-name">Grape Cultivation Advisory Chat System</p>
                            <p class="company-tag">Consultation Services</p>
                            <p class="contact-info">support@grapeadvisory.com &bull; +95 9 123456789</p>
                            <p class="contact-info">Dept of Information Technology, Your University, Myanmar</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="invoice-cell" width="35%">
                <p class="invoice-title">INVOICE</p>
                <div style="margin-top: 3px;">
                    <span class="invoice-no-label">Invoice No:</span>
                    <span class="invoice-no-value">{$data['invoiceNo']}</span>
                </div>
            </td>
        </tr>
    </table>

    <hr class="sep" />

    <!-- Info Grid (3 columns) -->
    <table class="grid-3" cellpadding="0" cellspacing="0">
        <tr>
            <td width="33%"><div class="grid-item"><div class="label">Consultation No.</div><div class="value">#{$data['consultationId']}</div></div></td>
            <td width="33%"><div class="grid-item"><div class="label">Farmer</div><div class="value">{$data['farmerName']}</div></div></td>
            <td width="33%"><div class="grid-item"><div class="label">Expert</div><div class="value">{$data['expertName']}</div></div></td>
        </tr>
        <tr>
            <td><div class="grid-item"><div class="label">Consultation Topic</div><div class="value">{$data['consultationTitle']}</div></div></td>
            <td><div class="grid-item"><div class="label">Payment Method</div><div class="value">{$data['paymentMethod']}</div></div></td>
            <td><div class="grid-item"><div class="label">Transaction Reference</div><div class="value-mono">{$data['transactionRef']}</div></div></td>
        </tr>
        <tr>
            <td><div class="grid-item"><div class="label">Payment Date</div><div class="value">{$data['paymentDate']}</div></div></td>
            <td><div class="grid-item"><div class="label">Status</div><div class="value"><span class="status-badge {$statusClass}">{$data['status']}</span></div></div></td>
            <td><div class="grid-item"><div class="label">Generated By</div><div class="value">{$data['generatedBy']}</div></div></td>
        </tr>
        <tr>
            <td>{$refundBlock}</td>
            <td></td>
        </tr>
    </table>

    <hr class="sep" />

    <!-- Items Table -->
    <table class="item-tbl">
        <thead>
            <tr>
                <th width="55%">Description</th>
                <th width="20%" class="ac">Type</th>
                <th width="10%" class="ac">Qty</th>
                <th width="15%" class="ar">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Consultation Service &mdash; {$data['consultationTitle']}</td>
                <td class="ac">Advisory</td>
                <td class="ac">1</td>
                <td class="ar">\$ {$data['formattedAmount']}</td>
            </tr>
            <tr>
                <td>Platform fee &amp; expert matching</td>
                <td class="ac">Service</td>
                <td class="ac">1</td>
                <td class="ar">\$0.00</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <table class="total-tbl" cellpadding="0" cellspacing="0">
        <tr>
            <td class="lbl">Subtotal</td>
            <td class="val">\$ {$data['formattedAmount']}</td>
        </tr>
        <tr>
            <td class="lbl">Tax (0%)</td>
            <td class="val">\$0.00</td>
        </tr>
        <tr class="sep-line"><td colspan="2"><div></div></td></tr>
        <tr>
            <td class="lbl gt-lbl">Total</td>
            <td class="val gt-val">\$ {$data['formattedAmount']}</td>
        </tr>
    </table>

    <hr class="sep" />

    <!-- Footer Message -->
    <div class="footer-msg">
        <p class="thanks">Thank you for using the<br/>Grape Cultivation Advisory Chat System.</p>
        <p class="sub">This invoice serves as official proof of payment<br/>for your consultation service.</p>
        <p class="small">Generated electronically. No signature is required.</p>
    </div>

</div>

<div class="footer-bar">
    {$data['invoiceNo']} &bull; Generated {$generatedDate}
</div>
</div>
</body>
</html>
HTML;

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$filename = $data['invoiceNo'] . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
