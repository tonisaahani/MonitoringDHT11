@php
    use Carbon\Carbon;
@endphp

<div class="space-y-6">
    <div class="flex flex-col items-center justify-center text-center px-4 py-6 bg-[#1e293b] rounded-xl shadow text-white">
        <h1 class="text-2xl font-bold flex items-center gap-2 justify-center">
            <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
            </svg>
            Dashboard
        </h1>
        <p class="text-sm text-slate-400 mt-1">Pantauan Sistem Monitoring Lingkungan Ruangan</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white">
            <p class="text-sm text-slate-400">Total Data Log</p>
            <p class="text-2xl font-bold mt-1" id="total-log">{{ \App\Models\SensorLog::count() }}</p>
        </div>
        <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white overflow-hidden select-none focus:outline-none">
            <p class="text-sm text-slate-400">Suhu Terakhir</p>
            <p class="text-2xl font-bold mt-1 text-orange-400" id="suhu-terakhir">
                @php
                    preg_match('/Suhu\s*:\s*([\d.]+)°C/', optional(\App\Models\SensorLog::latest()->first())->value ?? '', $suhu);
                @endphp
                {{ $suhu[1] ?? '-' }}°C
            </p>
        </div>
        <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white overflow-hidden select-none focus:outline-none">
            <p class="text-sm text-slate-400">Status Api</p>
            <p id="status-api" class="text-2xl font-bold mt-1">
                Memuat...
            </p>

        </div>
    </div>

    {{-- Mini Chart --}}
    <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white mt-4">
        <h3 class="text-lg font-semibold mb-2">Grafik Ringan Suhu</h3>
        <canvas id="miniChart" height="100"></canvas>
    </div>

    {{-- Log Terakhir --}}
    <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white mt-4">
        <h3 class="text-lg font-semibold mb-2">5 Log Terakhir</h3>
        <ul class="text-sm space-y-1" id="log-terakhir">
            @foreach(\App\Models\SensorLog::latest()->take(5)->get() as $log)
                <li>
                    📌 {{ \Carbon\Carbon::parse($log->created_at)->timezone('Asia/Jakarta')->format('Y-m-d H:i:s') }} — {{ $log->value }}
                </li>
            @endforeach
        </ul>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon@3"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1"></script>

<script>
    const ctx = document.getElementById('miniChart').getContext('2d');

    const miniChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [{
                label: 'Suhu (°C)',
                data: [],
                borderColor: 'orange',
                backgroundColor: 'rgba(255,165,0,0.1)',
                tension: 0.3,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        tooltipFormat: 'HH:mm:ss',
                        displayFormats: {
                            second: 'HH:mm:ss',
                            minute: 'HH:mm'
                        }
                    },
                    min: luxon.DateTime.now().minus({ minutes: 5 }).toISO(),
                    max: luxon.DateTime.now().toISO(),
                    title: {
                        display: true,
                        text: 'Waktu (5 Menit Terakhir)'
                    },
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                },
                y: {
                    beginAtZero: false,
                    min: 25,
                    max: 33,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value + '°C';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Suhu (°C)'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + '°C';
                        }
                    }
                }
            }
        }
    });

    function fetchStatsAndUpdate() {
        fetch('/chart-data')
            .then(res => res.json())
            .then(data => {
                miniChart.data.datasets[0].data = data.data;
                miniChart.options.scales.x.min = luxon.DateTime.now().minus({ minutes: 5 }).toISO();
                miniChart.options.scales.x.max = luxon.DateTime.now().toISO();
                miniChart.update();

                document.getElementById('total-log').innerText = data.total;
                document.getElementById('suhu-terakhir').innerText = data.latest_suhu + '°C';

                const statusApi = document.getElementById('status-api');
                statusApi.innerText = data.flame ? 'Api Terdeteksi' : 'Tidak Terdeteksi';
                statusApi.className = data.flame
                    ? 'text-2xl font-bold mt-1 text-red-500'
                    : 'text-2xl font-bold mt-1 text-green-400';

                const ul = document.getElementById('log-terakhir');
                ul.innerHTML = '';
                data.logs.forEach(log => {
                    const li = document.createElement('li');
                    li.innerText = `📌 ${log.time} — ${log.value}`;
                    ul.appendChild(li);
                });
            });
    }

    setInterval(fetchStatsAndUpdate, 5000);
    fetchStatsAndUpdate();
</script>
@endpush
