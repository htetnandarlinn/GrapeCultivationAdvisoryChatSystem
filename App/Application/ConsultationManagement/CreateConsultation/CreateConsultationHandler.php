<?php

namespace App\Application\ConsultationManagement\CreateConsultation;

use App\Domain\ConsultationManagement\Entities\Consultation;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Events\ConsultationCreated;

final class CreateConsultationHandler
{
    public function __construct(private ConsultationRepositoryInterface $consultationRepository)
    {
    }

    public function handle(CreateConsultationCommand $command): void
    {
        $consultation = new Consultation(
            $command->id,
            $command->userId,
            $command->question,
        );

        $this->consultationRepository->save($consultation);
        // TODO: dispatch event to notify experts.
        $event = new ConsultationCreated($command->id, $command->userId, $command->question);
    }
}

