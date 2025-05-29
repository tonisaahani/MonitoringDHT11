<div class="space-y-6 font-sans">
    {{-- Header --}}
    <div class="bg-[#1e293b] px-6 py-5 rounded-xl shadow text-white flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="bg-violet-600 p-3 rounded-full">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0h4" />
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight font-sans">Dashboard Monitoring</h1>
                <p class="text-sm text-slate-400 mt-1 font-sans">📡 Pemantauan suhu, gas, api & kelembapan secara real-time.</p>
            </div>
        </div>
        <div class="text-sm flex items-center gap-2">
            <span id="sistem-status" class="bg-green-600 text-white font-medium px-3 py-1 rounded-full">🟢 Sistem Aktif</span>
            <span id="status-mqtt" class="bg-blue-500 text-white font-medium px-3 py-1 rounded-full">📶 MQTT Connected</span>
            <span id="durasi-aktif" class="text-slate-300">⏱️ Aktif selama: --:--:--</span>
            <span class="text-slate-400 hidden md:inline">|</span>
            <span class="text-xs text-slate-300 hidden md:inline">Terakhir update <span id="update-time">--:--:--</span></span>
        </div>
    </div>


    <div class="bg-[#1e293b] p-4 rounded-xl shadow text-white">
        <h2 class="text-lg font-semibold mb-2 flex items-center gap-2">

            Ringkasan Sistem
        </h2>
        <p id="ringkasan-sistem" class="text-sm text-slate-300 mb-4">Memuat ringkasan sistem...</p>
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-6 gap-4">
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <p class="text-sm text-slate-400 text-center">Suhu Terkini</p>
                <p class="text-2xl font-bold mt-1 text-orange-400 text-center" id="suhu-terakhir">Memuat...</p>
            </div>
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <p class="text-sm text-slate-400 text-center">Kelembapan Terkini</p>
                <p class="text-2xl font-bold mt-1 text-yellow-400 text-center" id="humidity-terakhir">Memuat...</p>
            </div>
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <p class="text-sm text-slate-400 text-center">Gas Terkini (ppm)</p>
                <p class="text-2xl font-bold mt-1 text-green-400 text-center" id="gas-terakhir">Memuat...</p>

            </div>
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <p class="text-sm text-slate-400 text-center">Status Api</p>
                <p class="text-2xl font-bold mt-1 text-center" id="status-api">Memuat...</p>
            </div>
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <p class="text-sm text-slate-400 text-center">Status Sistem</p>
                <p class="text-2xl font-bold mt-1 text-center" id="status-sistem">Memuat...</p>

            </div>
            <div class="bg-[#334155] rounded-xl p-4 shadow text-white">
                <h3 class="text-sm text-slate-400 mb-1 text-center">Notifikasi Sistem</h3>
                <ul id="notifikasi-log" class="text-sm space-y-1">
                    <li>Memuat notifikasi ...</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Grafik Komparatif --}}
    <div class="bg-[#1e293b] rounded-xl p-4 shadow text-white mt-4">
        <h3 class="text-lg font-semibold mb-2">Grafik Suhu (5 Menit Terakhir)</h3>
        <div class="overflow-x-auto">
            <canvas id="multiChart" height="450" class="w-full"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/luxon@3"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1"></script>
<script>
                let startTime = null;
                let timerInterval = null;

function formatDuration(duration) {
    const hours = String(Math.floor(duration / 3600)).padStart(2, '0');
    const minutes = String(Math.floor((duration % 3600) / 60)).padStart(2, '0');
    const seconds = String(duration % 60).padStart(2, '0');
    return `${hours}:${minutes}:${seconds}`;
}

function startTimerPersistent() {
    if (!localStorage.getItem('mqtt_start_time')) {
        localStorage.setItem('mqtt_start_time', Math.floor(Date.now() / 1000));
    }

    if (!timerInterval) {
        timerInterval = setInterval(() => {
            const startTime = parseInt(localStorage.getItem('mqtt_start_time'));
            const now = Math.floor(Date.now() / 1000);
            const duration = now - startTime;
            document.getElementById('durasi-aktif').innerText = '⏱️ Aktif selama: ' + formatDuration(duration);
        }, 1000);
    }
}

function resetTimerPersistent() {
    clearInterval(timerInterval);
    timerInterval = null;
    localStorage.removeItem('mqtt_start_time');
    document.getElementById('durasi-aktif').innerText = '⏱️ Aktif selama: --:--:--';
}


    const ctx = document.getElementById('multiChart').getContext('2d');
    const multiChart = new Chart(ctx, {
        type: 'line',
        data: {
            datasets: [
                {
                    label: 'Suhu (°C)',
                    data: [],
                    borderColor: 'orange',
                    yAxisID: 'y1',
                    tension: 0.3,
                    fill: false,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    pointStyle: 'circle'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: { mode: 'index', intersect: false },
            stacked: false,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'minute',
                        tooltipFormat: 'HH:mm:ss',
                        displayFormats: {
                            minute: 'HH:mm'
                        }
                    },
                    ticks: {
                        source: 'auto',
                        autoSkip: true,
                        maxTicksLimit: 6,
                        maxRotation: 0,
                        minRotation: 0
                    },
                    title: {
                        display: true,
                        text: 'Waktu'
                    }
                },
                y1: {
                    type: 'linear',
                    position: 'left',
                    title: { display: true, text: 'Suhu (°C)' }
                }
            }
        }
    });

    function fetchStatsAndUpdate() {
        fetch('/chart-data')
            .then(res => res.json())
            .then(data => {
                const suhu = data.data || [];
                multiChart.data.datasets[0].data = suhu;
                multiChart.update();

                document.getElementById('update-time').innerText = data.last_update || '--:--:--';
                document.getElementById('suhu-terakhir').innerText = data.latest_suhu + '°C';
                document.getElementById('humidity-terakhir').innerText = data.latest_humidity + '%';

                const gasEl = document.getElementById('gas-terakhir');
                gasEl.innerText = data.latest_gas + ' ppm';
                gasEl.className = 'text-2xl font-bold mt-1 font-sans text-center ' +
                    (data.latest_gas > 1000 ? 'text-red-500' : data.latest_gas > 500 ? 'text-yellow-400' : 'text-green-400');

                const statusApi = document.getElementById('status-api');
                statusApi.innerText = data.flame ? 'Api Terdeteksi' : 'Tidak Terdeteksi';
                statusApi.className = 'text-2xl font-bold mt-1 font-sans ' + (data.flame ? 'text-red-500' : 'text-green-400');

                const statusSystem = document.getElementById('status-sistem');
                if (data.flame || data.latest_suhu > 35 || data.latest_gas > 1000) {
                    statusSystem.innerText = 'Bahaya';
                    statusSystem.className = 'text-2xl font-bold mt-1 font-sans text-center text-red-600';
                } else if (data.latest_suhu > 30 || data.latest_gas > 500) {
                    statusSystem.innerText = '⚠️ Warning';
                    statusSystem.className = 'text-2xl font-bold mt-1 font-sans text-center text-yellow-400';
                } else {
                    statusSystem.innerText = '✅ Stabil';
                    statusSystem.className = 'text-2xl font-bold mt-1 font-sans text-center text-green-400';
                }

                    const sistemStatus = document.getElementById('sistem-status');
                    const mqttStatus = document.getElementById('status-mqtt');

                    if (data.status_koneksi) {
                        sistemStatus.innerText = '🟢 Sistem Aktif';
                        sistemStatus.className = 'bg-green-600 text-white font-medium px-3 py-1 rounded-full font-sans';

                        mqttStatus.innerText = '📶 MQTT Connected';
                        mqttStatus.className = 'bg-blue-500 text-white font-medium px-3 py-1 rounded-full font-sans';

                        startTimerPersistent(); // aktifkan timer
                    } else {
                        sistemStatus.innerText = '🔴 Sistem Mati';
                        sistemStatus.className = 'bg-red-600 text-white font-medium px-3 py-1 rounded-full font-sans';

                        mqttStatus.innerText = '🔌 MQTT Disconnected';
                        mqttStatus.className = 'bg-red-500 text-white font-medium px-3 py-1 rounded-full font-sans';

                        resetTimerPersistent(); // matikan timer
                    }


                const notif = document.getElementById('notifikasi-log');
                notif.innerHTML = '';
                let adaNotif = false;
                if (data.flame) {
                    notif.innerHTML += '<li class="text-red-500 font-sans text-center">🔥 Api terdeteksi di ruangan!</li>';
                    adaNotif = true;
                }
                if (data.latest_suhu > 32) {
                    notif.innerHTML += '<li class="text-red-500 font-sans text-center">🌡️ Suhu melebihi batas normal!</li>';
                    adaNotif = true;
                }
                if (data.latest_gas > 1000) {
                    notif.innerHTML += '<li class="text-red-500 font-sans text-center">🧪 Konsentrasi gas tinggi!</li>';
                    adaNotif = true;
                }
                if (!adaNotif) {
                    notif.innerHTML = '<li class="font-sans text-center">✅ Tidak ada notifikasi saat ini.</li>';

                }

                const ringkasan = document.getElementById('ringkasan-sistem');
                if (data.flame) {
                    ringkasan.innerText = '🔥 Sistem mendeteksi adanya api! Lakukan tindakan segera.';
                } else if (data.latest_gas > 1000 || data.latest_suhu > 35) {
                    ringkasan.innerText = '⚠️ Kondisi lingkungan tidak stabil. Mohon perhatian.';
                } else {
                    ringkasan.innerText = '✅ Sistem dalam kondisi aman dan stabil.';
                }
            });
    }

    setInterval(fetchStatsAndUpdate, 5000);
    fetchStatsAndUpdate();
</script>
@endpush
