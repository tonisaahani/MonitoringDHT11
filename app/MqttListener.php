<?php

namespace App;

use PhpMqtt\Client\Facades\MQTT;
use App\Models\SensorLog;

class MqttListener
{
    public function __invoke()
    {
        try {
            MQTT::connection()->subscribe('esp32/monitoring/suhu', function (string $topic, string $message): void {
                $data = json_decode($message, true);

                if (
                    is_array($data) &&
                    isset($data['suhu']) &&
                    isset($data['humidity']) &&
                    isset($data['gas']) &&
                    isset($data['flame']) &&
                    isset($data['buzzer'])
                ) {
                    $value = "Suhu : {$data['suhu']}°C Humidity : {$data['humidity']}%";
                    SensorLog::create([
                        'topic' => '',
                        'value' => $value,
                        'gas' => $data['gas'],
                        'flame' => $data['flame'] === 'Api Terdeteksi' ? 1 : 0,
                        'buzzer' => $data['buzzer'] === 'Nyala' ? 1 : 0,
                    ]);


                    echo "✅ Tersimpan: $value | Gas: {$data['gas']} ppm | Flame: {$data['flame']} | Buzzer: {$data['buzzer']}\n";
                } else {
                    // Tidak perlu insert row ke database untuk data invalid!
                    echo "⚠️ Format tidak valid: $message\n";
                }
            }, 0);

            MQTT::connection()->loop(true);
        } catch (\Throwable $e) {
            echo "❌ MQTT Error: " . $e->getMessage() . "\n";
        }
    }
}
