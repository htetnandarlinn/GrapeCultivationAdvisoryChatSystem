<?php

namespace App\Application\ConsultationManagement\UpdateConsultationStatus;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;

final class UpdateConsultationStatusHandler
{
    public function __construct(private ConsultationRepositoryInterface $consultationRepository)
    {
    }

    public function handle(UpdateConsultationStatusCommand $command): void
    {
        $consultation = $this->consultationRepository->findById($command->consultationId);

        if (!$consultation) {
            throw new \RuntimeException("Consultation not found with ID: {$command->consultationId}");
        }

        switch ($command->status) {
            case 'assigned':
                $consultation->assignExpert($command->expertId);
                break;
            case 'accepted':
                $consultation->markExpertAccepted();
                $consultation->markAwaitingPayment();
                break;
            case 'rejected':
                $consultation->reject($command->rejectionNote ?? '');
                break;
            default:
                throw new \InvalidArgumentException("Invalid status: {$command->status}");
        }

        $this->consultationRepository->update($consultation);
    }
}
