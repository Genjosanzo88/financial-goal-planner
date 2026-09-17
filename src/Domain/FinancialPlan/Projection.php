<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan;

use App\Domain\FinancialPlan\ValueObject\Money;

final class Projection
{
    public function __construct(
        private readonly Money $finalCapital,
        private readonly Money $totalContributed,
        private readonly Money $estimatedReturn
    ) {
    }

    public function finalCapital(): Money
    {
        return $this->finalCapital;
    }

    public function totalContributed(): Money
    {
        return $this->totalContributed;
    }

    public function estimatedReturn(): Money
    {
        return $this->estimatedReturn;
    }
}