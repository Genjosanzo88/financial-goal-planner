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
use App\Application\FinancialPlan\DTO\ScenarioOutput;
use App\Domain\FinancialPlan\Service\ProjectionTimelineCalculator;

final class CreateFinancialPlan
{
    public function __construct(
        private readonly FinancialPlanRepository $repository,
        private readonly ProjectionCalculator $projectionCalculator,
        private readonly RequiredContributionCalculator $requiredContributionCalculator,
        private readonly TimeToTargetCalculator $timeToTargetCalculator,
        private readonly ProjectionTimelineCalculator $projectionTimelineCalculator
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

        $scenarioDefinitions = [
            ['name' => 'Conservador', 'rate' => 3.0],
            ['name' => 'Moderado', 'rate' => 5.0],
            ['name' => 'Dinámico', 'rate' => 7.0],
        ];

        $scenarios = [];

        foreach ($scenarioDefinitions as $definition) {
            $scenarioRate = new AnnualRate(
                $definition['rate']
            );

            $scenarioProjection =
                $this->projectionCalculator->calculate(
                    $plan,
                    $scenarioRate
                );

            $scenarioRequiredContribution =
                $this->requiredContributionCalculator->calculate(
                    $plan,
                    $scenarioRate
                );

            $scenarioMonths =
                $this->timeToTargetCalculator->calculate(
                    $plan,
                    $scenarioRate
                );

            $scenarioDifference = null;

            if ($scenarioMonths !== null) {
                $scenarioDifference =
                    $plan->years()->months() - $scenarioMonths;
            }

            $scenarios[] = new ScenarioOutput(
                name: $definition['name'],
                annualRate: $definition['rate'],
                finalCapital: $scenarioProjection
                    ->finalCapital()
                    ->amount(),
                totalContributed: $scenarioProjection
                    ->totalContributed()
                    ->amount(),
                estimatedReturn: $scenarioProjection
                    ->estimatedReturn()
                    ->amount(),
                requiredMonthlyContribution:
                $scenarioRequiredContribution->amount(),
                targetReached:
                $plan->isTargetReached($scenarioProjection),
                monthsToTarget: $scenarioMonths,
                timeDifferenceMonths: $scenarioDifference,
                timeline: $this->projectionTimelineCalculator
                    ->calculate($plan, $scenarioRate)
            );
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
            timeDifferenceMonths: $timeDifferenceMonths,
            scenarios: $scenarios
        );
    }
}