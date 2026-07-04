<?php

namespace App\Application\ConsultationManagement\AskQuestion;

use App\Shared\Application\Validation\Validator;
use App\Shared\Exceptions\ValidationException;

final class AskQuestionRequestValidator
{
    public function validate(array $payload): AskQuestionCommand
    {
        $farmerId = (int) ($payload['farmer_id'] ?? 0);
        $categoryId = (int) ($payload['category_id'] ?? 0);
        $title = trim($payload['title'] ?? '');
        $description = trim($payload['description'] ?? '');
        $image = $payload['image'] ?? null;

        $validator = new Validator();

        $validator
            ->required('farmer_id', $payload)
            ->required('category_id', $payload)
            ->required('title', $payload)
            ->minLength('title', $title, 3)
            ->maxLength('title', $title, 255)
            ->required('description', $payload);

        if ($validator->fails()) {
            throw new ValidationException($validator->getErrors());
        }

        return new AskQuestionCommand(
            $farmerId,
            $categoryId,
            $title,
            $description,
            $image
        );
    }
}