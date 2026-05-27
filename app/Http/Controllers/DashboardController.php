<?php

namespace App\Http\Controllers;

use App\Services\DashboardAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardAnalyticsService $dashboardAnalyticsService
    ) {
    }

    public function index(Request $request): View
    {
        $analytics = $this->dashboardAnalyticsService->getAnalytics($request);

        return view('dashboard.index', $analytics);
    }
}