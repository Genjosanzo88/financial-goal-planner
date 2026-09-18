<?php

declare(strict_types=1);

use App\Application\FinancialPlan\UseCase\CreateFinancialPlan;
use App\Application\FinancialPlan\UseCase\ListFinancialPlans;
use App\Domain\FinancialPlan\Service\ProjectionCalculator;
use App\Domain\FinancialPlan\Service\ProjectionTimelineCalculator;
use App\Domain\FinancialPlan\Service\RequiredContributionCalculator;
use App\Domain\FinancialPlan\Service\TimeToTargetCalculator;
use App\Infrastructure\Persistence\PDO\PdoFinancialPlanRepository;
use App\Presentation\Http\Controller\CreateFinancialPlanController;
use App\Presentation\Http\Controller\ListFinancialPlansController;

$connection = require __DIR__ . '/database.php';

$repository = new PdoFinancialPlanRepository(
    $connection
);

$createFinancialPlan = new CreateFinancialPlan(
    $repository,
    new ProjectionCalculator(),
    new RequiredContributionCalculator(),
    new TimeToTargetCalculator(),
    new ProjectionTimelineCalculator()
);

$listFinancialPlans = new ListFinancialPlans(
    $repository
);

return [
    'create' => new CreateFinancialPlanController(
        $createFinancialPlan
    ),

    'list' => new ListFinancialPlansController(
        $listFinancialPlans
    ),
];