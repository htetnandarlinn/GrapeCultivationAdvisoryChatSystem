<?php

namespace App\Presentation\Controllers\Payment;

use App\Application\ConsultationManagement\Payment\PricingService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;

class InvoiceController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private PaymentRepositoryInterface $paymentRepository,
        private PricingService $pricingService,
    ) {}

    public function view(): void
    {
        $invoice = null;
        $error = null;
        $consultationId = 0;

        try {
            $result = $this->resolveInvoiceData();
            if (isset($result['error'])) {
                $error = $result['error'];
            } else {
                $invoice = $result['data'];
                $consultationId = $invoice['consultationId'];
            }
        } catch (\Exception $e) {
            $error = 'Unable to load invoice: ' . $e->getMessage();
        }

        $userRole = $_SESSION['user_role'] ?? '';
        $layout = ($userRole === 'farmer') ? 'app' : null;

        View::render('invoice/invoice-detail', [
            'invoice' => $invoice,
            'error' => $error,
            'consultationId' => $consultationId,
        ], $layout);
    }

    public function downloadPdf(): void
    {
        try {
            $result = $this->resolveInvoiceData();
            if (isset($result['error'])) {
                http_response_code(400);
                echo $result['error'];
                return;
            }
            $data = $result['data'];
        } catch (\Exception $e) {
            http_response_code(500);
            echo 'Unable to generate invoice: ' . $e->getMessage();
            return;
        }

        require __DIR__ . '/../../Views/invoice/invoice-pdf.php';
    }

    private function resolveInvoiceData(): array
    {
        $consultationId = (int) ($_GET['consultation_id'] ?? 0);
        if (!$consultationId) {
            return ['error' => 'Missing consultation_id'];
        }

        $consultation = $this->consultationRepository->findById($consultationId);
        if (!$consultation) {
            return ['error' => 'Consultation not found'];
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $userRole = $_SESSION['user_role'] ?? '';

        $farmerId = $consultation->getFarmerId();
        $expertId = $consultation->getExpertId();

        if ($userRole === 'farmer' && $userId !== $farmerId) {
            return ['error' => 'Access denied'];
        }
        if ($userRole === 'expert' && $userId !== $expertId) {
            return ['error' => 'Access denied'];
        }

        $payment = $this->paymentRepository->findLatestByConsultationId($consultationId);

        $farmer = $this->userRepository->findById($farmerId);
        $expert = $expertId ? $this->userRepository->findById($expertId) : null;

        $paymentDate = null;
        $paidAt = $consultation->getVerifiedAt();
        if ($paidAt) {
            $paymentDate = $paidAt;
        } elseif ($payment && $payment->getPaymentDate()) {
            $paymentDate = $payment->getPaymentDate();
        }

        $invoiceDate = $paymentDate ? $paymentDate->format('Ymd') : date('Ymd');
        $invoiceNo = sprintf('INV-%s-%05d', $invoiceDate, $consultationId);

        $transactionRef = null;
        if ($payment && $payment->getTransactionReference()) {
            $transactionRef = $payment->getTransactionReference();
        }
        if (!$transactionRef) {
            $transactionRef = $consultation->getIdempotencyKey();
        }

        $paymentStatus = 'Pending';
        $consultationStatus = $consultation->getStatus()->getValue();
        if (in_array($consultationStatus, ['accepted', 'chat_started', 'completed'], true)) {
            $paymentStatus = 'Paid';
        } elseif ($payment) {
            $ps = $payment->getStatus()->getValue();
            $paymentStatus = match ($ps) {
                'PAID', 'SUBMITTED' => 'Paid',
                'PENDING' => 'Pending',
                'REJECTED' => 'Rejected',
                'REFUNDED' => 'Refunded',
                default => 'Pending',
            };
        }

        $amount = $payment ? (float) $payment->getAmount() : $this->pricingService->getConsultationFee();

        $refundStatus = null;
        $refundDate = null;
        if ($payment && $payment->getRefundStatus()) {
            $refundStatus = $payment->getRefundStatus();
            if ($payment->getRefundDate()) {
                $refundDate = $payment->getRefundDate()->format('d M Y');
            }
        }
        if (!$refundStatus) {
            $refundStatus = $consultation->getRefundStatus();
            $refundDt = $consultation->getRefundDate();
            if ($refundStatus && $refundDt) {
                $refundDate = $refundDt->format('d M Y');
            }
        }

        $adminName = 'System';
        if ($payment && $payment->getVerifiedBy()) {
            $admin = $this->userRepository->findById((int) $payment->getVerifiedBy());
            if ($admin) {
                $adminName = $admin->getUsername();
            }
        }

        return ['data' => [
            'invoiceNo' => $invoiceNo,
            'consultationId' => $consultationId,
            'farmerName' => $farmer ? $farmer->getUsername() : 'Unknown',
            'expertName' => $expert ? $expert->getUsername() : 'N/A',
            'consultationTitle' => $consultation->getTitle(),
            'paymentMethod' => $consultation->getPaymentMethod() ? ucfirst(strtolower($consultation->getPaymentMethod())) : 'N/A',
            'transactionRef' => $transactionRef ?? 'N/A',
            'status' => $paymentStatus,
            'paymentDate' => $paymentDate ? $paymentDate->format('d M Y') : 'N/A',
            'generatedDate' => date('d M Y'),
            'generatedBy' => $adminName,
            'amount' => $amount,
            'formattedAmount' => number_format($amount, 2),
            'refundStatus' => $refundStatus,
            'refundDate' => $refundDate,
        ]];
    }
}
