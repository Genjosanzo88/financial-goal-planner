<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\DTO;

final class CreateFinancialPlanInput
{
    public function __construct(
        public readonly string $clientName,
        public readonly string $goalName,
        public readonly float $targetAmount,
        public readonly float $initialCapital,
        public readonly float $monthlyContribution,
        public readonly int $years
    ) {
    }
}