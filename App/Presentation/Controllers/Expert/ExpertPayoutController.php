<?php

namespace App\Presentation\Controllers\Expert;

use App\Domain\ConsultationManagement\Repositories\ExpertPayoutRepositoryInterface;
use App\Presentation\Views\View;

class ExpertPayoutController
{
    public function __construct(
        private ExpertPayoutRepositoryInterface $expertPayoutRepository,
    ) {}

    public function index(): void
    {
        $expertId = (int) ($_SESSION['user']['id'] ?? 0);

        $payouts = $this->expertPayoutRepository->findByExpertId($expertId);

        $totalEarned = 0.0;
        $totalPending = 0.0;
        $totalReleased = 0.0;
        foreach ($payouts as $payout) {
            $totalEarned += $payout->getNetAmount();
            if ($payout->isReleased()) {
                $totalReleased += $payout->getNetAmount();
            } else {
                $totalPending += $payout->getNetAmount();
            }
        }

        View::render('expert/payouts', [
            'payouts' => $payouts,
            'totalEarned' => $totalEarned,
            'totalPending' => $totalPending,
            'totalReleased' => $totalReleased,
            'activePage' => 'expert-payouts',
        ], 'dashboard');
    }
}
