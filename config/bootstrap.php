<?php

declare(strict_types=1);

use App\Application\FinancialPlan\UseCase\CreateFinancialPlan;
use App\Domain\FinancialPlan\Service\ProjectionCalculator;
use App\Domain\FinancialPlan\Service\RequiredContributionCalculator;
use App\Infrastructure\Persistence\PDO\PdoFinancialPlanRepository;
use App\Presentation\Http\Controller\CreateFinancialPlanController;

$connection = require __DIR__ . '/database.php';

$repository = new PdoFinancialPlanRepository(
    $connection
);

$useCase = new CreateFinancialPlan(
    $repository,
    new ProjectionCalculator(),
    new RequiredContributionCalculator()
);

return new CreateFinancialPlanController(
    $useCase
);