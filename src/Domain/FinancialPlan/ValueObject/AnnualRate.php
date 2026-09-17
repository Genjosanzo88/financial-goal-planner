<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\ValueObject;

use InvalidArgumentException;

final class AnnualRate
{
    public function __construct(
        private readonly float $percentage
    ) {
        // En este simulador trabajamos únicamente con escenarios de crecimiento.
        if ($percentage < 0) {
            throw new InvalidArgumentException(
                'Annual rate cannot be negative.'
            );
        }
    }

    public function percentage(): float
    {
        return $this->percentage;
    }

    public function decimal(): float
    {
        return $this->percentage / 100;
    }
}