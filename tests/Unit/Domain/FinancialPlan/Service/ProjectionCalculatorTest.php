<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\Service;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\Service\ProjectionCalculator;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use PHPUnit\Framework\TestCase;

final class ProjectionCalculatorTest extends TestCase
{
    public function test_it_calculates_a_projection_without_return(): void
    {
        $calculator = new ProjectionCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(1)
        );

        $projection = $calculator->calculate(
            $plan,
            new AnnualRate(0)
        );

        self::assertSame(
            15600.0,
            $projection->finalCapital()->amount()
        );

        self::assertSame(
            15600.0,
            $projection->totalContributed()->amount()
        );

        self::assertSame(
            0.0,
            $projection->estimatedReturn()->amount()
        );
    }

    public function test_it_calculates_a_projection_with_compound_return(): void
    {
        $calculator = new ProjectionCalculator();

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $projection = $calculator->calculate(
            $plan,
            new AnnualRate(5)
        );

        self::assertEqualsWithDelta(
            105551.13,
            $projection->finalCapital()->amount(),
            0.01
        );
    }
}