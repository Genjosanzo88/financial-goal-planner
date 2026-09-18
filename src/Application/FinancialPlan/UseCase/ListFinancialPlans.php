<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\UseCase;

use App\Application\FinancialPlan\Port\FinancialPlanRepository;

final class ListFinancialPlans
{
    public function __construct(
        private readonly FinancialPlanRepository $repository
    ) {
    }

    public function execute(int $limit = 5): array
    {
        return $this->repository->findRecent($limit);
    }
}