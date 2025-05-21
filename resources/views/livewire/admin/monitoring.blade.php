<div wire:poll.2s class="p-4">
    <h2 class="text-xl font-bold mb-4">Monitoring Suhu</h2>
    <table class="min-w-full bg-base-100">
        <thead>
            <tr>
                <th class="px-4 py-2 text-center">Waktu</th>
                <th class="px-4 py-2 text-center">Topic</th>
                <th class="px-4 py-2 text-center">Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <tr>
                <td class="px-4 py-2 text-center">
                    {{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s') }}
                </td>
                <td class="px-4 py-2 text-center">{{ $log->topic }}</td>
                <td class="px-4 py-2 text-center">
                    @php
                        $json = json_decode($log->value, true);
                    @endphp
                    @if(is_array($json) && isset($json['suhu']) && isset($json['humidity']))
                        Suhu : {{ $json['suhu'] }}&deg;C&nbsp;&nbsp;Humidity : {{ $json['humidity'] }}%
                    @else
                        {{ $log->value }}
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4 flex justify-between items-center">
        <button wire:click="confirmDelete"
            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded shadow mr-4">
            Hapus Semua Data
        </button>
        <div class="flex-1 flex justify-center">
            {{ $logs->links() }}
        </div>
    </div>

    {{-- Modal konfirmasi --}}
    @if($showConfirmDelete)
        <div class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
            <div class="bg-base-200 rounded-xl p-6 shadow-lg w-full max-w-sm text-center">
                <h3 class="text-lg font-semibold mb-2 text-red-700">Konfirmasi Hapus Data</h3>
                <p class="mb-4">Yakin ingin menghapus <b>semua</b> data monitoring suhu?</p>
                <div class="flex justify-center gap-2">
                    <button wire:click="deleteAllLogs"
                        class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">
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
