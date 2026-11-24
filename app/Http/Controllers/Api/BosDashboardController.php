<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BosDashboardResource;
use App\Services\Finance\BosDashboardService;
use Illuminate\Http\Request;

class BosDashboardController extends Controller
{
    public function __construct(
        protected BosDashboardService $dashboardService
    ) {
    }

    public function show(Request $request, int $bosPeriodId): BosDashboardResource
    {
        $data = $this->dashboardService->getDashboardData(
            bosPeriodId: $bosPeriodId,
            filters: $request->only(['category', 'search', 'per_page', 'page'])
        );

        return new BosDashboardResource($data);
    }
}