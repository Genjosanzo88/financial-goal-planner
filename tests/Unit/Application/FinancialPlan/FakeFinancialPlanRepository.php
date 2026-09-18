<?php

declare(strict_types=1);

namespace Tests\Unit\Application\FinancialPlan;

use App\Application\FinancialPlan\Port\FinancialPlanRepository;
use App\Domain\FinancialPlan\Entity\FinancialPlan;

final class FakeFinancialPlanRepository implements FinancialPlanRepository
{
    public ?FinancialPlan $savedPlan = null;

    /**
     * @var FinancialPlan[]
     */
    private array $plans = [];

    public function save(FinancialPlan $plan): void
    {
        $this->savedPlan = $plan;

        $this->plans[] = $plan;
    }

    public function findRecent(int $limit = 5): array
    {
        return array_slice(
            array_reverse($this->plans),
            0,
            $limit
        );
    }
}