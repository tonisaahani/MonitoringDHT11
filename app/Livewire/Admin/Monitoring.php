<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SensorLog;

class Monitoring extends Component
{
    use WithPagination;

    public $showConfirmDelete = false;

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
        // Optionally add notification/toast here
    }

    public function render()
    {
        // Ambil 50 data terbaru (saja)
        $allLogs = SensorLog::orderBy('created_at', 'desc')->limit(50)->get();

        // Manual pagination
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

}
