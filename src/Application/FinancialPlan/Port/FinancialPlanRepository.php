<?php

declare(strict_types=1);

namespace App\Application\FinancialPlan\Port;

use App\Domain\FinancialPlan\Entity\FinancialPlan;

interface FinancialPlanRepository
{
    public function save(FinancialPlan $plan): void;

    /**
     * @return FinancialPlan[]
     */
    public function findRecent(int $limit = 5): array;
}