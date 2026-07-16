<?php

namespace App\Application\ConsultationManagement\CreateConsultation;

use App\Domain\ConsultationManagement\Entities\Consultation;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;

final class CreateConsultationHandler
{
    public function __construct(private ConsultationRepositoryInterface $consultationRepository)
    {
    }

    public function handle(CreateConsultationCommand $command): Consultation
    {
        $consultation = new Consultation(
            id: null,
            farmerId: $command->farmerId,
            title: $command->title,
            description: $command->description,
            consultationFee: $command->consultationFee,
        );

        $this->consultationRepository->save($consultation);

        return $consultation;
    }
}
