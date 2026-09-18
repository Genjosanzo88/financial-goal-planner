<?php

declare(strict_types=1);

namespace App\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;

final class TimeToTargetCalculator
{
    public function calculate(
        FinancialPlan $plan,
        AnnualRate $annualRate
    ): ?int {
        $initialCapital = $plan->initialCapital()->amount();
        $monthlyContribution = $plan->monthlyContribution()->amount();
        $targetAmount = $plan->targetAmount()->amount();

        // El objetivo ya está alcanzado desde el inicio.
        if ($initialCapital >= $targetAmount) {
            return 0;
        }

        $monthlyRate = $annualRate->decimal() / 12;

        // Sin rentabilidad, el tiempo depende solo de las aportaciones.
        if ($monthlyRate === 0.0) {
            if ($monthlyContribution === 0.0) {
                return null;
            }

            return (int) ceil(
                ($targetAmount - $initialCapital)
                / $monthlyContribution
            );
        }

        // Sin capital ni aportaciones, el dinero nunca puede crecer.
        if (
            $initialCapital === 0.0
            && $monthlyContribution === 0.0
        ) {
            return null;
        }

        /*
         * Resolvemos la fórmula de capitalización para obtener
         * directamente el número de meses necesarios.
         */
        $contributionFactor =
            $monthlyContribution / $monthlyRate;

        $ratio =
            ($targetAmount + $contributionFactor)
            /
            ($initialCapital + $contributionFactor);

        $months =
            log($ratio)
            /
            log(1 + $monthlyRate);

        return (int) ceil($months);
    }
}