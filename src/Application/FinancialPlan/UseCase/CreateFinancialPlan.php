<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\UseCase;

use App\Application\FinancialPlan\DTO\CreateFinancialPlanInput;
use App\Application\FinancialPlan\DTO\FinancialPlanOutput;
use App\Application\FinancialPlan\Port\FinancialPlanRepository;
use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\Service\ProjectionCalculator;
use App\Domain\FinancialPlan\Service\RequiredContributionCalculator;
use App\Domain\FinancialPlan\Service\TimeToTargetCalculator;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;

final class CreateFinancialPlan
{
    public function __construct(
        private readonly FinancialPlanRepository $repository,
        private readonly ProjectionCalculator $projectionCalculator,
        private readonly RequiredContributionCalculator $requiredContributionCalculator,
        private readonly TimeToTargetCalculator $timeToTargetCalculator
    ) {
    }

    public function execute(
        CreateFinancialPlanInput $input,
        AnnualRate $annualRate
    ): FinancialPlanOutput {
        $plan = new FinancialPlan(
            $input->clientName,
            $input->goalName,
            new Money($input->targetAmount),
            new Money($input->initialCapital),
            new Money($input->monthlyContribution),
            new Years($input->years)
        );

        $projection = $this->projectionCalculator->calculate(
            $plan,
            $annualRate
        );

        $requiredContribution =
            $this->requiredContributionCalculator->calculate(
                $plan,
                $annualRate
            );

        $monthsToTarget =
            $this->timeToTargetCalculator->calculate(
                $plan,
                $annualRate
            );

        $timeDifferenceMonths = null;

        if ($monthsToTarget !== null) {
            $timeDifferenceMonths =
                $plan->years()->months() - $monthsToTarget;
        }

        $this->repository->save($plan);

        return new FinancialPlanOutput(
            clientName: $plan->clientName(),
            goalName: $plan->goalName(),
            targetAmount: $plan->targetAmount()->amount(),
            initialCapital: $plan->initialCapital()->amount(),
            monthlyContribution: $plan->monthlyContribution()->amount(),
            years: $plan->years()->value(),
            annualRate: $annualRate->percentage(),
            finalCapital: $projection->finalCapital()->amount(),
            totalContributed: $projection->totalContributed()->amount(),
            estimatedReturn: $projection->estimatedReturn()->amount(),
            requiredMonthlyContribution: $requiredContribution->amount(),
            targetReached: $plan->isTargetReached($projection),
            monthsToTarget: $monthsToTarget,
            timeDifferenceMonths: $timeDifferenceMonths
        );
    }
}