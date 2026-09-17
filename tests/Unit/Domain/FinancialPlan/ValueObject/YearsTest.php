<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\FinancialPlan\ValueObject;

use App\Domain\FinancialPlan\ValueObject\Years;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class YearsTest extends TestCase
{
    public function test_it_stores_years(): void
    {
        $years = new Years(15);

        self::assertSame(15, $years->value());
    }

    public function test_it_converts_years_to_months(): void
    {
        $years = new Years(15);

        self::assertSame(180, $years->months());
    }

    public function test_it_rejects_zero_years(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Years(0);
    }
}