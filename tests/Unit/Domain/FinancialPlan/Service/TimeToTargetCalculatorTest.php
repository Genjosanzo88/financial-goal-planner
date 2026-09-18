<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\Service\TimeToTargetCalculator;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use PHPUnit\Framework\TestCase;

final class TimeToTargetCalculatorTest extends TestCase
{
    public function test_it_calculates_months_needed_to_reach_target(): void
    {
        $calculator = new TimeToTargetCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $months = $calculator->calculate(
            $plan,
            new AnnualRate(5)
        );

        self::assertSame(173, $months);
    }

    public function test_it_calculates_time_without_return(): void
    {
        $calculator = new TimeToTargetCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(24000),
            new Money(12000),
            new Money(1000),
            new Years(10)
        );

        $months = $calculator->calculate(
            $plan,
            new AnnualRate(0)
        );

        self::assertSame(12, $months);
    }

    public function test_it_returns_zero_when_target_is_already_reached(): void
    {
        $calculator = new TimeToTargetCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(10000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $months = $calculator->calculate(
            $plan,
            new AnnualRate(5)
        );

        self::assertSame(0, $months);
    }

    public function test_it_returns_null_when_target_cannot_be_reached(): void
    {
        $calculator = new TimeToTargetCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(0),
            new Money(0),
            new Years(15)
        );

        $months = $calculator->calculate(
            $plan,
            new AnnualRate(0)
        );

        self::assertNull($months);
    }
}