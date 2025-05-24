<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorLog;

class MonitoringGauge extends Component
{
    public $temperature = 0;
    public $humidity = 0;
    public $gas = 0;
    public $flame = false;
    public $buzzer = false;

    public function fetchLatest(): void
    {
        $latest = SensorLog::where('value', 'not like', 'Invalid format:%')
            ->latest()
            ->first();

        if ($latest && is_string($latest->value)) {
            preg_match('/Suhu\s*:\s*([\d.]+)°C/', $latest->value, $suhuMatch);
            preg_match('/Humidity\s*:\s*([\d.]+)%/', $latest->value, $humMatch);

            $this->temperature = isset($suhuMatch[1]) ? floatval($suhuMatch[1]) : $this->temperature;
            $this->humidity = isset($humMatch[1]) ? floatval($humMatch[1]) : $this->humidity;
            $this->gas = is_numeric($latest->gas) ? floatval($latest->gas) : $this->gas;

            // 🔥 Revisi agar flame dan buzzer terdeteksi benar
            $this->flame = $latest->flame == 1;
            $this->buzzer = $latest->buzzer == 1;

        }
    }

    public function mount(): void
    {
        $this->fetchLatest();
    }

    public function render()
    {
        return view('livewire.monitoring-gauge');
    }
}
