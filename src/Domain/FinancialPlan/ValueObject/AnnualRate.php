<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\ValueObject;

use InvalidArgumentException;

final class AnnualRate
{
    public function __construct(
        private readonly float $percentage
    ) {
        // Una rentabilidad no puede ser inferior a -100 %.
        if ($percentage < -100) {
            throw new InvalidArgumentException(
                'Annual rate cannot be lower than -100%.'
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