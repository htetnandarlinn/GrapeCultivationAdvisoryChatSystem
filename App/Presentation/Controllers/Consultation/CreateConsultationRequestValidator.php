<?php

namespace App\Presentation\Controllers;

use App\Shared\Exceptions\ValidationException;
use App\Shared\Helpers\ValidationHelper;

final class CreateConsultationRequestValidator
{
    public function validate(array $payload): CreateConsultationRequest
    {
        $id = $payload['id'] ?? uniqid('consultation_', true);

        if (!is_string($id)) {
            throw new ValidationException('Consultation ID must be a string.');
        }

        $userId = $payload['user_id'] ?? '';
        $question = $payload['question'] ?? '';

        ValidationHelper::assertNotEmpty($userId, 'User ID is required.');
        ValidationHelper::assertNotEmpty($question, 'Question is required.');

        return new CreateConsultationRequest(
            $id,
            $userId,
            $question,
        );
    }
}
