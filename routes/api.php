<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SavApiController;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes — JR Computer ERP
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // SAV Technicien
    Route::prefix('tech')->group(function () {
        Route::get('/tickets',                   [SavApiController::class, 'getMyTickets']);
        Route::patch('/tickets/{id}',            [SavApiController::class, 'updateStatus']);
        Route::post('/tickets/{id}/close',       [SavApiController::class, 'closeTicket']);
        Route::get('/parts',                     [SavApiController::class, 'getSpareParts']);
    });

    // Analytics BI
    Route::get('/analytics/data', function (Request $request) {
    $filters = $request->only(['date_from', 'date_to', 'category', 'supplier']);
    
    // Normalisation des dates
    if (!empty($filters['date_from'])) {
        $filters['date_from'] = Carbon::parse($filters['date_from'])->startOfDay();
    }
    if (!empty($filters['date_to'])) {
        $filters['date_to'] = Carbon::parse($filters['date_to'])->endOfDay();
    }
    
    $analytics = app(AnalyticsService::class)->getDashboardData($filters);
    
    // Transformation des données pour le frontend
    $analytics['salesEvolution'] = $analytics['salesEvolution']->map(fn($item) => [
        'month' => $item->month,
        'total' => (float) $item->total,
    ]);
    
    $analytics['categoryDistribution'] = $analytics['categoryDistribution']->map(fn($item) => [
        'category' => $item['category'],
        'total' => (float) $item['total'],
    ]);
    
    $analytics['topProducts'] = $analytics['topProducts']->map(fn($item) => [
        'name' => $item->name,
        'category' => $item->category,
        'qty' => (int) $item->qty,
        'total' => (float) $item->total,
    ]);
    
    $analytics['savPerformance']['technicians'] = $analytics['savPerformance']['technicians']->map(fn($item) => [
        'technician_name' => $item['technician_name'],
        'tickets' => $item['tickets'],
        'completion_rate' => $item['completion_rate'],
    ]);
    
    return response()->json($analytics);
})->name('api.analytics.data');
});