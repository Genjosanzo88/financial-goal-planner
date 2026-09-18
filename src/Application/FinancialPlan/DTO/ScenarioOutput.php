<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\DTO;

final class ScenarioOutput
{
    public function __construct(
        public readonly string $name,
        public readonly float $annualRate,
        public readonly float $finalCapital,
        public readonly float $totalContributed,
        public readonly float $estimatedReturn,
        public readonly float $requiredMonthlyContribution,
        public readonly bool $targetReached,
        public readonly ?int $monthsToTarget,
        public readonly ?int $timeDifferenceMonths,
        public readonly array $timeline
    ) {
    }
}