<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controller;

use App\Application\FinancialPlan\UseCase\ListFinancialPlans;
use App\Presentation\Http\JsonResponse;

final class ListFinancialPlansController
{
    public function __construct(
        private readonly ListFinancialPlans $listFinancialPlans
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $plans = $this->listFinancialPlans->execute();

        return new JsonResponse([
            'items' => array_map(
                static fn ($plan): array => [
                    'clientName' =>
                        $plan->clientName(),

                    'goalName' =>
                        $plan->goalName(),

                    'targetAmount' =>
                        $plan->targetAmount()->amount(),

                    'initialCapital' =>
                        $plan->initialCapital()->amount(),

                    'monthlyContribution' =>
                        $plan->monthlyContribution()->amount(),

                    'years' =>
                        $plan->years()->value(),
                ],
                $plans
            ),
        ]);
    }
}