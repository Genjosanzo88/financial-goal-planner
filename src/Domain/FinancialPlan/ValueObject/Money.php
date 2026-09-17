<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\ValueObject;

use InvalidArgumentException;

final class Money
{
    private readonly int $cents;

    public function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException(
                'Money amount cannot be negative.'
            );
        }

        // Guardamos dinero como céntimos para evitar errores de precisión.
        $this->cents = (int) round($amount * 100);
    }

    public function amount(): float
    {
        return $this->cents / 100;
    }

    public function cents(): int
    {
        return $this->cents;
    }
}