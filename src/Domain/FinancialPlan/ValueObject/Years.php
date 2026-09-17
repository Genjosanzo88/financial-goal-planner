<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\ValueObject;

use InvalidArgumentException;

final class Years
{
    public function __construct(
        private readonly int $value
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException(
                'Years must be greater than zero.'
            );
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function months(): int
    {
        return $this->value * 12;
    }
}