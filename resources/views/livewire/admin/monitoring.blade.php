<div wire:poll.2s class="p-4">
    <h2 class="text-xl font-bold mb-4">History Data</h2>

    {{-- Filter dan Ekspor --}}
    <div class="flex flex-wrap gap-2 items-center mb-4">
        <select wire:model="filterGas" class="select select-bordered w-full md:w-52">
            <option value="">Semua Status Gas</option>
            <option value="1">Gas Terdeteksi</option>
            <option value="0">Tidak Terdeteksi Gas</option>
        </select>

        <select wire:model="filterFlame" class="select select-bordered w-full md:w-52">
            <option value="">Semua Status Api</option>
            <option value="1">Api Terdeteksi</option>
            <option value="0">Tidak Terdeteksi Api</option>
        </select>

        <button wire:click="exportCsv"
            class="btn bg-green-500 hover:bg-green-600 text-white font-semibold rounded">
            🗂️ Export ke CSV
        </button>
    </div>

    {{-- Tabel --}}
    <table class="min-w-full bg-base-100">
        <thead>
            <tr>
                <th class="px-4 py-2 text-center">Waktu</th>
                <th class="px-4 py-2 text-center">Gas Value</th>
                <th class="px-4 py-2 text-center">Value</th>
                <th class="px-4 py-2 text-center">Kategori Gas</th>
                <th class="px-4 py-2 text-center">Kategori Flame</th>
                <th class="px-4 py-2 text-center">Flame</th>
                <th class="px-4 py-2 text-center">Buzzer</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
                @php
                    $kategoriGas = $log->gas > 800 ? 'Bahaya' : 'Normal';
                    $kategoriFlame = $log->flame == 1 ? 'Bahaya' : 'Normal';
                    $json = json_decode($log->value, true);
                @endphp
                <tr class="{{ $kategoriGas == 'Bahaya' || $kategoriFlame == 'Bahaya' ? 'bg-orange-500/40 text-white backdrop-blur-sm' : '' }}">

                    <td class="px-4 py-2 text-center">
                        {{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $log->gas !== null ? "Gas Value: {$log->gas} ppm" : '-' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        @if(is_array($json) && isset($json['suhu']) && isset($json['humidity']))
                            Suhu: {{ $json['suhu'] }}°C&nbsp;&nbsp;Humidity: {{ $json['humidity'] }}%
                        @else
                            {{ $log->value }}
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">{{ $kategoriGas }}</td>
                    <td class="px-4 py-2 text-center">{{ $kategoriFlame }}</td>
                    <td class="px-4 py-2 text-center">
                        {{ $log->flame == 1 ? 'Api Terdeteksi' : 'Tidak Terdeteksi Api' }}
                    </td>
                    <td class="px-4 py-2 text-center">
                        {{ $log->buzzer == 1 ? 'Nyala' : 'Mati' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination dan Delete --}}
    <div class="mt-4 flex justify-between items-center">
        <button wire:click="confirmDelete"
            class="bg-red-500/90 hover:bg-red-500/90 text-white font-semibold py-2 px-4 rounded shadow mr-4">
            Hapus Semua Data
        </button>
        <div class="flex-1 flex justify-center">
            {{ $logs->links() }}
        </div>
    </div>

    {{-- Modal Hapus --}}
    @if($showConfirmDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-base-200 rounded-xl p-6 shadow-lg w-full max-w-sm text-center">
                <h3 class="text-lg font-semibold mb-2 text-red-500/90">Konfirmasi Hapus Data</h3>
                <p class="mb-4">Yakin ingin menghapus <b>semua</b> data monitoring sensor?</p>
                <div class="flex justify-center gap-2">
                    <button wire:click="deleteAllLogs"
                        class="bg-red-500/90 hover:bg-red-500/90 text-white py-2 px-4 rounded">
                        Ya, Hapus Semua
                    </button>
                    <button wire:click="cancelDelete"
                        class="bg-gray-300 hover:bg-gray-400 text-black py-2 px-4 rounded">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
