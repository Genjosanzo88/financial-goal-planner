<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\Entity;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class FinancialPlanTest extends TestCase
{
    public function test_it_creates_a_financial_plan(): void
    {
        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        self::assertSame('Laura García', $plan->clientName());
        self::assertSame('Comprar vivienda', $plan->goalName());
        self::assertSame(100000.0, $plan->targetAmount()->amount());
        self::assertSame(12000.0, $plan->initialCapital()->amount());
        self::assertSame(300.0, $plan->monthlyContribution()->amount());
        self::assertSame(15, $plan->years()->value());
    }

    public function test_it_rejects_an_empty_client_name(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FinancialPlan(
            '',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );
    }

    public function test_it_rejects_an_empty_goal_name(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new FinancialPlan(
            'Laura García',
            '',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );
    }

    public function test_it_knows_when_the_target_is_reached(): void
    {
        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $projection = new \App\Domain\FinancialPlan\Projection(
            new Money(105000),
            new Money(66000),
            new Money(39000)
        );

        self::assertTrue(
            $plan->isTargetReached($projection)
        );
    }

    public function test_it_knows_when_the_target_is_not_reached(): void
    {
        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $projection = new \App\Domain\FinancialPlan\Projection(
            new Money(90000),
            new Money(66000),
            new Money(24000)
        );

        self::assertFalse(
            $plan->isTargetReached($projection)
        );
    }
}