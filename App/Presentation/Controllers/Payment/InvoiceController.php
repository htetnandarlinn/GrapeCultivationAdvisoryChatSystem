<?php

namespace App\Presentation\Controllers\Payment;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;
use PDO;

class InvoiceController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private PDO $connection,
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

        $stmt = $this->connection->prepare('SELECT * FROM payments WHERE consultation_id = :cid ORDER BY id DESC LIMIT 1');
        $stmt->execute([':cid' => $consultationId]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        $farmer = $this->userRepository->findById($farmerId);
        $expert = $expertId ? $this->userRepository->findById($expertId) : null;

        $paymentDate = null;
        $paidAt = $consultation->getVerifiedAt();
        if ($paidAt) {
            $paymentDate = $paidAt;
        } elseif ($payment && !empty($payment['payment_date'])) {
            $paymentDate = new \DateTimeImmutable($payment['payment_date']);
        }

        $invoiceDate = $paymentDate ? $paymentDate->format('Ymd') : date('Ymd');
        $invoiceNo = sprintf('INV-%s-%05d', $invoiceDate, $consultationId);

        $transactionRef = null;
        if ($payment && !empty($payment['transaction_reference'])) {
            $transactionRef = $payment['transaction_reference'];
        }
        if (!$transactionRef) {
            $transactionRef = $consultation->getIdempotencyKey();
        }

        $paymentStatus = 'Pending';
        $consultationStatus = $consultation->getStatus()->getValue();
        if (in_array($consultationStatus, ['accepted', 'chat_started', 'completed'], true)) {
            $paymentStatus = 'Paid';
        } elseif ($payment) {
            $ps = $payment['payment_status'] ?? '';
            $paymentStatus = match ($ps) {
                'PAID', 'SUBMITTED' => 'Paid',
                'PENDING' => 'Pending',
                'REJECTED' => 'Rejected',
                'REFUNDED' => 'Refunded',
                default => 'Pending',
            };
        }

        $amount = 29.99;

        $refundStatus = null;
        $refundDate = null;
        if ($payment && !empty($payment['refund_status'])) {
            $refundStatus = $payment['refund_status'];
            if (!empty($payment['refund_date'])) {
                $refundDate = (new \DateTimeImmutable($payment['refund_date']))->format('d M Y');
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
        if ($payment && !empty($payment['verified_by'])) {
            $admin = $this->userRepository->findById((int) $payment['verified_by']);
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
