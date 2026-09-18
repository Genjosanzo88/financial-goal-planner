<?php

declare(strict_types=1);

namespace Tests\Unit\Application\FinancialPlan;

use App\Application\FinancialPlan\DTO\CreateFinancialPlanInput;
use App\Application\FinancialPlan\UseCase\CreateFinancialPlan;
use App\Domain\FinancialPlan\Service\ProjectionCalculator;
use App\Domain\FinancialPlan\Service\RequiredContributionCalculator;
use App\Domain\FinancialPlan\Service\TimeToTargetCalculator;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use PHPUnit\Framework\TestCase;

final class CreateFinancialPlanTest extends TestCase
{
    public function test_it_creates_and_saves_a_financial_plan(): void
    {
        $repository = new FakeFinancialPlanRepository();

        $useCase = new CreateFinancialPlan(
            $repository,
            new ProjectionCalculator(),
            new RequiredContributionCalculator(),
            new TimeToTargetCalculator()
        );

        $input = new CreateFinancialPlanInput(
            clientName: 'Laura García',
            goalName: 'Comprar vivienda',
            targetAmount: 100000,
            initialCapital: 12000,
            monthlyContribution: 300,
            years: 15
        );

        $result = $useCase->execute(
            $input,
            new AnnualRate(5)
        );

        self::assertSame(
            'Laura García',
            $result->clientName
        );

        self::assertSame(
            'Comprar vivienda',
            $result->goalName
        );

        self::assertEqualsWithDelta(
            105551.13,
            $result->finalCapital,
            0.01
        );

        self::assertEqualsWithDelta(
            279.23,
            $result->requiredMonthlyContribution,
            0.01
        );

        self::assertTrue($result->targetReached);

        self::assertSame(
            173,
            $result->monthsToTarget
        );

        self::assertSame(
            7,
            $result->timeDifferenceMonths
        );

        self::assertNotNull(
            $repository->savedPlan
        );
    }
}