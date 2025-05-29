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


Route::middleware(['auth', 'admin'])->group(function () {
    Volt::route('/users', 'users.index');
    Volt::route('/users/create', 'users.create');
    Volt::route('/users/{user}/edit', 'users.edit');

});

Route::get('/create-password', [AuthController::class, 'createPasswordForm'])->name('password.create');
Route::post('/create-password', [AuthController::class, 'storePassword'])->name('password.store');


use App\Livewire\Admin\Monitoring;
use App\Livewire\MonitoringGauge;

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/monitoring', Monitoring::class)->name('admin.monitoring');
    Route::get('/admin/gauge', MonitoringGauge::class)->name('admin.gauge');
});






use App\Models\SensorLog;
use Carbon\Carbon;

Route::get('/chart-data', function () {
    $logs = SensorLog::where('created_at', '>=', Carbon::now()->subMinutes(5))
        ->orderBy('created_at')
        ->get();

    $data = [];
    $prev = null;
    $threshold = 0.1; // toleransi perbedaan suhu

    foreach ($logs as $log) {
        if (preg_match('/Suhu\s*:\s*([\d.]+)°C/', $log->value, $m)) {
            $value = floatval($m[1]);
            if ($prev === null || abs($value - $prev) >= $threshold) {
                $data[] = [
                    'x' => Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->toIso8601String(),
                    'y' => $value
                ];
                $prev = $value;
            }
        }
    }



    $latest = SensorLog::latest()->first();
    preg_match('/Suhu\s*:\s*([\d.]+)°C/', $latest?->value ?? '', $suhu);
    preg_match('/Humidity\s*:\s*([\d.]+)%/', $latest?->value ?? '', $humidity);

    // 💡 Tambahkan ini untuk status koneksi ESP
    $lastUpdate = $latest?->created_at;
    $isOnline = $lastUpdate && Carbon::parse($lastUpdate)->gt(now()->subSeconds(5));    // jika update terakhir < 10 detik yang lalu = ONLINE

    return response()->json([
        'data' => $data,
        'total' => SensorLog::count(),
        'latest_suhu' => $suhu[1] ?? '-',
        'latest_humidity' => $humidity[1] ?? '-',
        'latest_gas' => $latest?->gas ?? '-',
        'flame' => (int) $latest?->flame === 1,
        'last_update' => $lastUpdate ? Carbon::parse($lastUpdate)->timezone('Asia/Jakarta')->format('H:i:s') : null,
        'status_koneksi' => $isOnline,
        'logs' => SensorLog::latest()->take(5)->get()->map(function ($log) {
            return [
                'time' => Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'value' => $log->value
            ];
        })
    ]);
});







Route::get('/setting/profile', function () {
    return view('livewire.admin.setting-profile');
})->name('setting.profile');
