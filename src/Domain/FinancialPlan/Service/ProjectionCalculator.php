<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\Projection;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;

final class ProjectionCalculator
{
    public function calculate(
        FinancialPlan $plan,
        AnnualRate $annualRate
    ): Projection {
        $months = $plan->years()->months();

        $monthlyRate = $annualRate->decimal() / 12;

        $totalContributed =
            $plan->initialCapital()->amount()
            + ($plan->monthlyContribution()->amount() * $months);

        // Sin rentabilidad solo tenemos que sumar las aportaciones.
        if ($monthlyRate === 0.0) {
            $finalCapital = $totalContributed;
        } else {
            $growthFactor = (1 + $monthlyRate) ** $months;

            $finalCapital =
                ($plan->initialCapital()->amount() * $growthFactor)
                +
                (
                    $plan->monthlyContribution()->amount()
                    * (($growthFactor - 1) / $monthlyRate)
                );
        }

        $estimatedReturn = $finalCapital - $totalContributed;

        return new Projection(
            new Money($finalCapital),
            new Money($totalContributed),
            new Money($estimatedReturn)
        );
    }
}