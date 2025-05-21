<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\MqttListener;

Artisan::command('mqtt:listen', function () {
    (new MqttListener)(); // ✅ Panggil __invoke()
});




Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

