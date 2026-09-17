<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Projection;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;

final class ProjectionCalculator
{
    public function calculate(
        Money $initialCapital,
        Money $monthlyContribution,
        Years $years,
        AnnualRate $annualRate
    ): Projection {
        $months = $years->months();

        $monthlyRate = $annualRate->decimal() / 12;

        $totalContributed =
            $initialCapital->amount()
            + ($monthlyContribution->amount() * $months);

        // Sin rentabilidad solo tenemos que sumar las aportaciones.
        if ($monthlyRate === 0.0) {
            $finalCapital = $totalContributed;
        } else {
            $growthFactor = (1 + $monthlyRate) ** $months;

            $finalCapital =
                ($initialCapital->amount() * $growthFactor)
                +
                (
                    $monthlyContribution->amount()
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