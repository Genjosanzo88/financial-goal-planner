<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controller;

use App\Application\FinancialPlan\DTO\CreateFinancialPlanInput;
use App\Application\FinancialPlan\UseCase\CreateFinancialPlan;
use App\Domain\FinancialPlan\ValueObject\AnnualRate;
use App\Presentation\Http\JsonResponse;
use InvalidArgumentException;

final class CreateFinancialPlanController
{
    public function __construct(
        private readonly CreateFinancialPlan $createFinancialPlan
    ) {
    }

    public function __invoke(array $data): JsonResponse
    {
        $requiredFields = [
            'clientName',
            'goalName',
            'targetAmount',
            'initialCapital',
            'monthlyContribution',
            'years',
            'annualRate',
        ];

        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $data)) {
                return new JsonResponse(
                    ['error' => "Missing field: {$field}"],
                    422
                );
            }
        }

        try {
            $input = new CreateFinancialPlanInput(
                clientName: (string) $data['clientName'],
                goalName: (string) $data['goalName'],
                targetAmount: (float) $data['targetAmount'],
                initialCapital: (float) $data['initialCapital'],
                monthlyContribution: (float) $data['monthlyContribution'],
                years: (int) $data['years']
            );

            $result = $this->createFinancialPlan->execute(
                $input,
                new AnnualRate((float) $data['annualRate'])
            );
        } catch (InvalidArgumentException $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage()],
                422
            );
        }

        return new JsonResponse(
            [
                'clientName' => $result->clientName,
                'goalName' => $result->goalName,
                'targetAmount' => $result->targetAmount,
                'initialCapital' => $result->initialCapital,
                'monthlyContribution' => $result->monthlyContribution,
                'years' => $result->years,
                'annualRate' => $result->annualRate,
                'finalCapital' => $result->finalCapital,
                'totalContributed' => $result->totalContributed,
                'estimatedReturn' => $result->estimatedReturn,
                'requiredMonthlyContribution' =>
                    $result->requiredMonthlyContribution,
                'targetReached' => $result->targetReached,
            ],
            201
        );
    }
}