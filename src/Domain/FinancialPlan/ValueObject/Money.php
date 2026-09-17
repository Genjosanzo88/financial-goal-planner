<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\ValueObject;

use InvalidArgumentException;

final class Money
{
    public function __construct(
        private readonly float $amount
    ) {
        // En un plan financiero no aceptamos cantidades negativas.
        if ($amount < 0) {
            throw new InvalidArgumentException(
                'Money amount cannot be negative.'
            );
        }
    }

    public function amount(): float
    {
        return $this->amount;
    }
}