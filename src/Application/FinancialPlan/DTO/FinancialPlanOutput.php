<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\DTO;

final class FinancialPlanOutput
{
    public function __construct(
        public readonly string $clientName,
        public readonly string $goalName,
        public readonly float $targetAmount,
        public readonly float $initialCapital,
        public readonly float $monthlyContribution,
        public readonly int $years,
        public readonly float $annualRate,
        public readonly float $finalCapital,
        public readonly float $totalContributed,
        public readonly float $estimatedReturn,
        public readonly float $requiredMonthlyContribution,
        public readonly bool $targetReached
    ) {
    }
}