<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Entity;

use App\Domain\FinancialPlan\Projection;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use InvalidArgumentException;

final class FinancialPlan
{
    public function __construct(
        private readonly string $clientName,
        private readonly string $goalName,
        private readonly Money $targetAmount,
        private readonly Money $initialCapital,
        private readonly Money $monthlyContribution,
        private readonly Years $years
    ) {
        if (trim($clientName) === '') {
            throw new InvalidArgumentException(
                'Client name cannot be empty.'
            );
        }

        if (trim($goalName) === '') {
            throw new InvalidArgumentException(
                'Goal name cannot be empty.'
            );
        }
    }

    public function clientName(): string
    {
        return $this->clientName;
    }

    public function goalName(): string
    {
        return $this->goalName;
    }

    public function targetAmount(): Money
    {
        return $this->targetAmount;
    }

    public function initialCapital(): Money
    {
        return $this->initialCapital;
    }

    public function monthlyContribution(): Money
    {
        return $this->monthlyContribution;
    }

    public function years(): Years
    {
        return $this->years;
    }

    public function isTargetReached(Projection $projection): bool
    {
        return $projection->finalCapital()->cents()
            >= $this->targetAmount->cents();
    }
}