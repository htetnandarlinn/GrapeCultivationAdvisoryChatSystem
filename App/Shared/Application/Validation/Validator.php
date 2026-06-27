<?php

namespace App\Shared\Application\Validation;

final class Validator
{
    private array $errors = [];

    private function addError(string $field, string $message): void
    {
        echo "ok";
        if (!array_key_exists($field, $this->errors)) {
            $this->errors[$field] = $message;
        }
    }

    public function required(string $field, mixed $value): self
    {
        if (
            $value === null ||
            (is_string($value) && trim($value) === '')
        ) {
            $this->addError(
                $field,
                ucfirst(str_replace('_', ' ', $field)) . ' is required.'
            );
        }

        return $this;
    }

    public function email(string $field, mixed $value): self
    {
        if ($this->hasError($field)) {
            return $this;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $this;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError(
                $field,
                'Please enter a valid email address.'
            );
        }

        return $this;
    }

    public function minLength(string $field, mixed $value, int $min): self
    {
        if ($this->hasError($field)) {
            return $this;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $this;
        }

        if (mb_strlen($value) < $min) {
            $this->addError(
                $field,
                ucfirst(str_replace('_', ' ', $field))
                . " must be at least {$min} characters."
            );
        }

        return $this;
    }

    public function digits(string $field, mixed $value): self
    {
        if ($this->hasError($field)) {
            return $this;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $this;
        }

        if (!preg_match('/^[0-9]+$/', $value)) {
            $this->addError(
                $field,
                ucfirst(str_replace('_', ' ', $field))
                . ' must contain digits only.'
            );
        }

        return $this;
    }

    public function lengthBetween(
        string $field,
        mixed $value,
        int $min,
        int $max
    ): self {
        if ($this->hasError($field)) {
            return $this;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return $this;
        }

        $length = mb_strlen($value);

        if ($length < $min || $length > $max) {
            $this->addError(
                $field,
                ucfirst(str_replace('_', ' ', $field))
                . " must be between {$min} and {$max} characters."
            );
        }

        return $this;
    }

    public function match(
        string $field,
        mixed $value,
        mixed $compare
    ): self {
        if ($this->hasError($field)) {
            return $this;
        }

        if ((string) $value !== (string) $compare) {
            $this->addError(
                $field,
                ucfirst(str_replace('_', ' ', $field))
                . ' does not match.'
            );
        }

        return $this;
    }

    public function getErrors(): array
    {
        echo "Validation errors: " . json_encode($this->errors) . "\n"; // Debugging line
        return $this->errors;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    private function hasError(string $field): bool
    {
        return isset($this->errors[$field]);
    }
}