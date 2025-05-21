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
                // Coba decode JSON
                $data = json_decode($message, true);

                if (is_array($data) && isset($data['suhu']) && isset($data['humidity'])) {
                    // Simpan data sebagai JSON string agar bisa didecode di Livewire
                    SensorLog::create([
                        'topic' => $topic,
                        'value' => json_encode([
                            'suhu' => floatval($data['suhu']),
                            'humidity' => floatval($data['humidity']),
                        ]),
                    ]);

                    echo "✅ Data tersimpan: $topic | Suhu: {$data['suhu']} | Humidity: {$data['humidity']}\n";
                } else {
                    // Jika format salah (bukan JSON valid), simpan mentah
                    SensorLog::create([
                        'topic' => $topic,
                        'value' => json_encode([
                            'suhu' => 0,
                            'humidity' => 0,
                            'raw' => $message
                        ]),
                    ]);

                    echo "⚠️ Format tidak valid, disimpan mentah: $topic => $message\n";
                }
            }, 0);

            MQTT::connection()->loop(true);
        } catch (\Throwable $e) {
            echo "❌ MQTT Error: " . $e->getMessage() . "\n";
        }
    }
}
