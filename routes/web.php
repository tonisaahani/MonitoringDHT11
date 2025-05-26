<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\SocialiteController;

Volt::route('/register', 'register');

// Users will be redirected to this route if not logged in
Volt::route('/login', 'login')->name('login');





// Define the logout
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});


Route::controller(SocialiteController::class)->group(function () {
    Route::group([
        'prefix' => '/auth/',
        'as' => 'socialite.',
    ], function () {
        Route::get('{provider}/redirect', 'redirect')->name('redirect');
        Route::get('{provider}/callback', 'callback')->name('callback');
    });
});


Route::get('/admin/node', App\Livewire\Admin\Node\Idx::class)->name('admin.node');


Route::middleware('auth')->group(function () {
    // Route default ke halaman welcome
    Route::view('/', 'welcome')->name('welcome');


    Route::get('/huun', [HomeController::class, 'index'])->name('home');
    Volt::route('/', 'index');
});



// Protected routes here
Route::middleware(['auth', 'admin'])->group(function () {
    Volt::route('/users', 'users.index');
    Volt::route('/users/create', 'users.create');
    Volt::route('/users/{user}/edit', 'users.edit');
    // ... more
});

Route::get('/create-password', [AuthController::class, 'createPasswordForm'])->name('password.create');
Route::post('/create-password', [AuthController::class, 'storePassword'])->name('password.store');


// use App\Livewire\Admin\Monitoring\Idx;
// use App\Livewire\Admin\Monitoring\Idx as MonitoringIdx;

// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/monitoring', MonitoringIdx::class)->name('admin.monitoring');
// });


// routes/web.php
use App\Livewire\Admin\Monitoring;
use App\Livewire\MonitoringGauge;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/monitoring', Monitoring::class)->name('admin.monitoring');
    Route::get('/admin/gauge', MonitoringGauge::class)->name('admin.gauge');
});






use App\Models\SensorLog;
use Carbon\Carbon;

Route::get('/chart-data', function () {
    $data = SensorLog::where('created_at', '>=', Carbon::now()->subMinutes(5))
        ->orderBy('created_at')
        ->get()
        ->map(function ($log) {
            if (preg_match('/Suhu\s*:\s*([\d.]+)°C/', $log->value, $m)) {
                return [
                    'x' => Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->toIso8601String(),
                    'y' => floatval($m[1])
                ];
            }
            return null;
        })->filter()->values();

    return response()->json([
        'data' => $data,
        'total' => SensorLog::count(),
        'latest_suhu' => optional(SensorLog::latest()->first(), function ($log) {
            preg_match('/Suhu\s*:\s*([\d.]+)°C/', $log->value, $m);
            return $m[1] ?? '-';
        }),
        'flame' => SensorLog::orderBy('created_at', 'desc')->first()?->flame == 1,
        'flame' => (int) SensorLog::orderBy('created_at', 'desc')->first()?->flame === 1,



        'logs' => SensorLog::latest()->take(5)->get()->map(function ($log) {
            return [
                'time' => Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'value' => $log->value
            ];
        })
    ]);
});

