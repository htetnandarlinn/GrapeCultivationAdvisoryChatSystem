<?php

namespace App\Presentation\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final class Permission
{
    public function __construct(
        public readonly string $key,
        public readonly string $name,
        public readonly string $description = '',
    ) {}
}
