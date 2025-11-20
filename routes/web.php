<?php
// Health check endpoint
Route::get('/health', function () {
    try {
        \DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'failed: ' . $e->getMessage();
    }

    try {
        \Redis::connection()->ping();
        $redisStatus = 'connected';
    } catch (\Exception $e) {
        $redisStatus = 'failed';
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'database' => $dbStatus,
        'cache' => \Cache::has('health_check') ? 'working' : 'available',
        'redis' => $redisStatus,
    ]);
});

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
