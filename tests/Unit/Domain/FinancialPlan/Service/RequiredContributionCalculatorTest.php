<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\Service\RequiredContributionCalculator;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use PHPUnit\Framework\TestCase;

final class RequiredContributionCalculatorTest extends TestCase
{
    public function test_it_calculates_required_monthly_contribution(): void
    {
        $calculator = new RequiredContributionCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $requiredContribution = $calculator->calculate(
            $plan,
            new AnnualRate(5)
        );

        self::assertEqualsWithDelta(
            279.23,
            $requiredContribution->amount(),
            0.01
        );
    }

    public function test_it_calculates_required_contribution_without_return(): void
    {
        $calculator = new RequiredContributionCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(24000),
            new Money(12000),
            new Money(300),
            new Years(1)
        );

        $requiredContribution = $calculator->calculate(
            $plan,
            new AnnualRate(0)
        );

        self::assertSame(
            1000.0,
            $requiredContribution->amount()
        );
    }

    public function test_it_returns_zero_when_initial_capital_reaches_target(): void
    {
        $calculator = new RequiredContributionCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(10000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $requiredContribution = $calculator->calculate(
            $plan,
            new AnnualRate(5)
        );

        self::assertSame(
            0.0,
            $requiredContribution->amount()
        );
    }
}