<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\ValueObject;

use App\Domain\FinancialPlan\ValueObject\Money;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function test_it_stores_a_valid_amount(): void
    {
        $money = new Money(12000);

        self::assertSame(12000.0, $money->amount());
    }

    public function test_it_rejects_negative_amounts(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Money(-100);
    }
}