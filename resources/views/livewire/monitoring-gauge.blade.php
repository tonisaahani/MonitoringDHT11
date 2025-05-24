<div
    x-data="{
        temperature: @entangle('temperature').live,
        humidity: @entangle('humidity').live,
        gas: @entangle('gas').live,
        flame: @entangle('flame').live,
        buzzer: @entangle('buzzer').live,
        tempChart: null,
        humChart: null,
        gasChart: null,
        maxGas: 4095,
        createGauge(ctx, value, max, color) {
            return new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [value, Math.max(0, max - value)],
                        backgroundColor: [color, '#f1f5f9'],
                        borderWidth: 0,
                        cutout: '80%',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });
        },
        renderCharts() {
            if (this.tempChart) this.tempChart.destroy();
            if (this.humChart) this.humChart.destroy();
            if (this.gasChart) this.gasChart.destroy();

            this.tempChart = this.createGauge(this.$refs.tempChart, Number(this.temperature), 100, '#3b82f6');
            this.humChart = this.createGauge(this.$refs.humChart, Number(this.humidity), 100, '#06b6d4');
            this.gasChart = this.createGauge(this.$refs.gasChart, Number(this.gas), this.maxGas, '#f59e42');
        }
    }"
    x-init="renderCharts()"
    x-effect="renderCharts()"
    wire:poll.5s="fetchLatest"
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4 p-4"
>

    <!-- JUDUL -->
    <div class="col-span-full text-center mb-6">
        <h2 class="text-2xl font-bold text-white">Monitoring Suhu, Asap, dan Deteksi Api Ruangan</h2>
        <p class="text-gray-300 text-sm">Pantauan real-time suhu, konsentrasi asap, dan status api untuk keamanan ruangan Anda</p>
    </div>

    <!-- TEMPERATURE -->
    <div class="bg-white rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="tempChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold text-blue-600" x-text="temperature + '°C'"></span>
            <span class="text-sm font-semibold text-blue-400">Temperature</span>
        </span>
    </div>

    <!-- HUMIDITY -->
    <div class="bg-white rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="humChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold text-cyan-600" x-text="humidity + '%'"></span>
            <span class="text-sm font-semibold text-cyan-400">Humidity</span>
        </span>
    </div>

    <!-- GAS -->
    <div class="bg-white rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="gasChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold text-orange-600" x-text="gas + ' ppm'"></span>
            <span class="text-sm font-semibold text-orange-400">Gas</span>
        </span>
    </div>

<!-- FLAME -->
<div class="bg-white rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
    <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
        <span class="text-base font-bold" :class="flame ? 'text-red-600' : 'text-gray-700'" x-text="flame ? 'Api Terdeteksi' : 'Tidak Terdeteksi Api'"></span>
        <span class="text-sm font-semibold text-red-500">Flame</span>
    </span>
</div>

<!-- BUZZER -->
<div class="bg-white rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
    <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
        <span class="text-base font-bold" :class="buzzer ? 'text-indigo-600' : 'text-gray-700'" x-text="buzzer ? 'Nyala' : 'Mati'"></span>
        <span class="text-sm font-semibold text-indigo-500">Buzzer</span>
    </span>
</div>




    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
    @endonce
</div>
