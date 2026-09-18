<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\PDO;

use App\Application\FinancialPlan\Port\FinancialPlanRepository;
use App\Domain\FinancialPlan\Entity\FinancialPlan;
use App\Domain\FinancialPlan\ValueObject\Money;
use App\Domain\FinancialPlan\ValueObject\Years;
use PDO;

final class PdoFinancialPlanRepository implements FinancialPlanRepository
{
    public function __construct(
        private readonly PDO $connection
    ) {
    }

    public function save(FinancialPlan $plan): void
    {
        $statement = $this->connection->prepare(
            '
            INSERT INTO financial_plans (
                client_name,
                goal_name,
                target_amount,
                initial_capital,
                monthly_contribution,
                years
            ) VALUES (
                :client_name,
                :goal_name,
                :target_amount,
                :initial_capital,
                :monthly_contribution,
                :years
            )
            '
        );

        $statement->execute([
            'client_name' => $plan->clientName(),
            'goal_name' => $plan->goalName(),
            'target_amount' => $plan->targetAmount()->amount(),
            'initial_capital' => $plan->initialCapital()->amount(),
            'monthly_contribution' =>
                $plan->monthlyContribution()->amount(),
            'years' => $plan->years()->value(),
        ]);
    }

    public function findRecent(int $limit = 5): array
    {
        $statement = $this->connection->prepare(
            '
            SELECT
                client_name,
                goal_name,
                target_amount,
                initial_capital,
                monthly_contribution,
                years
            FROM financial_plans
            ORDER BY id DESC
            LIMIT :limit
            '
        );

        $statement->bindValue(
            ':limit',
            $limit,
            PDO::PARAM_INT
        );

        $statement->execute();

        $plans = [];

        foreach ($statement->fetchAll() as $row) {
            $plans[] = new FinancialPlan(
                (string) $row['client_name'],
                (string) $row['goal_name'],
                new Money((float) $row['target_amount']),
                new Money((float) $row['initial_capital']),
                new Money(
                    (float) $row['monthly_contribution']
                ),
                new Years((int) $row['years'])
            );
        }

        return $plans;
    }
}