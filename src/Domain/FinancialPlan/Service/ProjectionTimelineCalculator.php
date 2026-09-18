<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;

final class ProjectionTimelineCalculator
{
    public function calculate(
        FinancialPlan $plan,
        AnnualRate $annualRate
    ): array {
        $capital = $plan->initialCapital()->amount();

        $monthlyContribution =
            $plan->monthlyContribution()->amount();

        $monthlyRate =
            $annualRate->decimal() / 12;

        $totalMonths = $plan->years()->months();

        $timeline = [
            [
                'year' => 0,
                'capital' => round($capital, 2),
            ],
        ];

        for ($month = 1; $month <= $totalMonths; $month++) {
            // Primero crece el capital y después se realiza
            // la aportación al final del mes.
            $capital *= 1 + $monthlyRate;
            $capital += $monthlyContribution;

            if ($month % 12 === 0) {
                $timeline[] = [
                    'year' => (int) ($month / 12),
                    'capital' => round($capital, 2),
                ];
            }
        }

        return $timeline;
    }
}