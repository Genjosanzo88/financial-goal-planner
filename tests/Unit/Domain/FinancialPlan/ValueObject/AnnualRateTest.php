<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\ValueObject;

use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class AnnualRateTest extends TestCase
{
    public function test_it_stores_a_percentage(): void
    {
        $rate = new AnnualRate(5);

        self::assertSame(5.0, $rate->percentage());
    }

    public function test_it_converts_percentage_to_decimal(): void
    {
        $rate = new AnnualRate(5);

        self::assertSame(0.05, $rate->decimal());
    }

    public function test_it_rejects_negative_rates(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new AnnualRate(-1);
    }
}