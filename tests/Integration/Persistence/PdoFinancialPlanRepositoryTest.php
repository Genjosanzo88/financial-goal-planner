<?php

declare(strict_types=1);

namespace Tests\Integration\Persistence;

use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use App\Infrastructure\Persistence\PDO\PdoFinancialPlanRepository;
use PDO;
use PHPUnit\Framework\TestCase;

final class PdoFinancialPlanRepositoryTest extends TestCase
{
    private PDO $connection;

    protected function setUp(): void
    {
        $this->connection = require dirname(__DIR__, 3)
            . '/config/database.php';

        $this->connection->exec(
            'DELETE FROM financial_plans'
        );
    }

    public function test_it_saves_a_financial_plan(): void
    {
        $repository = new PdoFinancialPlanRepository(
            $this->connection
        );

        $plan = new FinancialPlan(
            'Laura García',
            'Comprar vivienda',
            new Money(100000),
            new Money(12000),
            new Money(300),
            new Years(15)
        );

        $repository->save($plan);

        $row = $this->connection
            ->query('SELECT * FROM financial_plans')
            ->fetch();

        self::assertSame(
            'Laura García',
            $row['client_name']
        );

        self::assertSame(
            'Comprar vivienda',
            $row['goal_name']
        );

        self::assertSame(
            '100000.00',
            $row['target_amount']
        );

        self::assertSame(
            '12000.00',
            $row['initial_capital']
        );
    }
}