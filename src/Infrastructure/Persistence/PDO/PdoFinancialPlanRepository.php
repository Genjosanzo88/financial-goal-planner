<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\PDO;

use App\Application\FinancialPlan\Port\FinancialPlanRepository;
use App\Domain\FinancialPlan\Entity\FinancialPlan;
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
            'monthly_contribution' => $plan->monthlyContribution()->amount(),
            'years' => $plan->years()->value(),
        ]);
    }
}