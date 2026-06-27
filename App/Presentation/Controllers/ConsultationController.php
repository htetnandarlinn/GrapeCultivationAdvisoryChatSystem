<?php

namespace App\Presentation\Controllers;

use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationCommand;
use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationHandler;
use App\Presentation\Controllers\CreateConsultationRequest;

final class ConsultationController
{
    public function __construct(private CreateConsultationHandler $createHandler)
    {
    }

    public function create(CreateConsultationRequest $request): void
    {
        $command = new CreateConsultationCommand(
            $request->id,
            $request->userId,
            $request->question,
        );

        $this->createHandler->handle($command);
    }
}
