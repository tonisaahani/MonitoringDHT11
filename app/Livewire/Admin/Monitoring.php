<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SensorLog;
use Illuminate\Support\Facades\Response;
class Monitoring extends Component
{
    use WithPagination;

    public $showConfirmDelete = false;

    // ✅ Tambahkan properti filterGas dan filterFlame
    public $filterGas = '';
    public $filterFlame = '';

    public function confirmDelete()
    {
        $this->showConfirmDelete = true;
    }

    public function cancelDelete()
    {
        $this->showConfirmDelete = false;
    }

    public function deleteAllLogs()
    {
        SensorLog::truncate();
        $this->showConfirmDelete = false;
    }

    public function render()
    {
        // Ambil data valid
        $query = SensorLog::where('value', 'not like', 'Invalid format:%');

        // ✅ Tambahkan logika filter GAZ
        if ($this->filterGas !== '') {
            if ($this->filterGas === '1') {
                $query->where('gas', '>', 800); // Gas terdeteksi
            } elseif ($this->filterGas === '0') {
                $query->where('gas', '<=', 800); // Tidak terdeteksi
            }
        }

        // ✅ Tambahkan logika filter API
        if ($this->filterFlame !== '') {
            $query->where('flame', (int) $this->filterFlame);
        }

        // Ambil 50 data terbaru
        $allLogs = $query->orderBy('created_at', 'desc')->limit(50)->get();

        // Manual pagination (tetap gunakan ini seperti aslinya)
        $perPage = 5;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $allLogs->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $logs = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allLogs->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.admin.monitoring', [
            'logs' => $logs,
            'showConfirmDelete' => $this->showConfirmDelete,
        ]);
    }
    public function exportCsv()
    {
        $query = SensorLog::where('value', 'not like', 'Invalid format:%');

        if ($this->filterGas !== '') {
            if ($this->filterGas === '1') {
                $query->where('gas', '>', 800);
            } elseif ($this->filterGas === '0') {
                $query->where('gas', '<=', 800);
            }
        }

        if ($this->filterFlame !== '') {
            $query->where('flame', (int) $this->filterFlame);
        }

        $logs = $query->latest()->take(100)->get();

        $filename = 'monitoring_export_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Waktu', 'Gas Value', 'Suhu', 'Humidity', 'Kategori Gas', 'Kategori Flame', 'Flame', 'Buzzer']);

            foreach ($logs as $log) {
                preg_match('/Suhu\s*:\s*([\d.]+)°C/', $log->value, $suhuMatch);
                preg_match('/Humidity\s*:\s*([\d.]+)%/', $log->value, $humidityMatch);

                $suhu = $suhuMatch[1] ?? '-';
                $humidity = $humidityMatch[1] ?? '-';
                $kategoriGas = $log->gas > 800 ? 'Bahaya' : 'Normal';
                $kategoriFlame = $log->flame == 1 ? 'Bahaya' : 'Normal';

                fputcsv($file, [
                    $log->created_at->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s'),
                    $log->gas,
                    $suhu,
                    $humidity,
                    $kategoriGas,
                    $kategoriFlame,
                    $log->flame == 1 ? 'Api Terdeteksi' : 'Tidak Terdeteksi',
                    $log->buzzer == 1 ? 'Nyala' : 'Mati',
                ]);
            }


            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
