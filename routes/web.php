<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

// Health check endpoint
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'failed: ' . $e->getMessage();
    }

    try {
        Redis::connection()->ping();
        $redisStatus = 'connected';
    } catch (\Exception $e) {
        $redisStatus = 'failed: ' . $e->getMessage();
    }

    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'app' => config('app.name'),
        'environment' => config('app.env'),
        'database' => $dbStatus,
        'cache' => Cache::has('health_check') ? 'working' : 'available',
        'redis' => $redisStatus,
    ]);
});

Route::get('/', function () {
    return view('welcome');
});
