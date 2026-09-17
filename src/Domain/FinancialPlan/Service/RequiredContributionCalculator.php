<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;

final class RequiredContributionCalculator
{
    public function calculate(
        FinancialPlan $plan,
        AnnualRate $annualRate
    ): Money {
        $months = $plan->years()->months();

        $initialCapital = $plan->initialCapital()->amount();
        $targetAmount = $plan->targetAmount()->amount();

        $monthlyRate = $annualRate->decimal() / 12;

        // Sin rentabilidad, repartimos lo que falta entre los meses.
        if ($monthlyRate === 0.0) {
            $requiredContribution =
                ($targetAmount - $initialCapital) / $months;

            return new Money(
                max(0, $requiredContribution)
            );
        }

        $growthFactor = (1 + $monthlyRate) ** $months;

        // Calculamos cuánto crecería solo el capital inicial.
        $futureInitialCapital =
            $initialCapital * $growthFactor;

        // Si el capital inicial ya alcanzaría el objetivo,
        // no hace falta aportar mensualmente.
        if ($futureInitialCapital >= $targetAmount) {
            return new Money(0);
        }

        $requiredContribution =
            (($targetAmount - $futureInitialCapital) * $monthlyRate)
            / ($growthFactor - 1);

        return new Money($requiredContribution);
    }
}