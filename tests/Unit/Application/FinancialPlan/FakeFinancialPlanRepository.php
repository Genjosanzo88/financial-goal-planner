<?php

declare(strict_types=1);

namespace Tests\Unit\Application\FinancialPlan;

use App\Application\FinancialPlan\Port\FinancialPlanRepository;
use App\Domain\FinancialPlan\Entity\FinancialPlan;

final class FakeFinancialPlanRepository implements FinancialPlanRepository
{
    public ?FinancialPlan $savedPlan = null;

    public function save(FinancialPlan $plan): void
    {
        $this->savedPlan = $plan;
    }
}