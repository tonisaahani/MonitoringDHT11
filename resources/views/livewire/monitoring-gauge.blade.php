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
                        backgroundColor: [color, '#334155'],
                        borderWidth: 0,
                        cutout: '80%',
                    }]
                },
                options: {
                    animation: {
                        duration: 1000,
                        easing: 'easeOutBounce'
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            callbacks: {
                                label: function(context) {
                                    return `Nilai: ${context.raw}`;
                                }
                            }
                        }
                    }
                }
            });
        },
        gasLevel() {
            if (this.gas < 300) return 'Aman';
            if (this.gas < 1000) return 'Sedang';
            return 'Bahaya';
        },
        gasColor() {
            if (this.gas < 300) return 'text-green-400';
            if (this.gas < 1000) return 'text-yellow-400';
            return 'text-red-500';
        },
        renderCharts() {
            if (this.tempChart) this.tempChart.destroy();
            if (this.humChart) this.humChart.destroy();
            if (this.gasChart) this.gasChart.destroy();

            this.tempChart = this.createGauge(this.$refs.tempChart, Number(this.temperature), 100, '#3b82f6');
            this.humChart = this.createGauge(this.$refs.humChart, Number(this.humidity), 100, '#fbbf24');
            this.gasChart = this.createGauge(this.$refs.gasChart, Number(this.gas), this.maxGas, '#f97316');
        }
    }"
    x-init="renderCharts()"
    x-effect="renderCharts()"
    wire:poll.5s="fetchLatest"
    class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6 p-6"
>

    <!-- JUDUL -->
    <div class="col-span-full text-center mb-8">
        <h2 class="text-3xl font-bold text-white">Monitoring Suhu, Asap, dan Deteksi Api Ruangan</h2>
        <p class="text-slate-400 text-base mt-2">Pantauan real-time suhu, konsentrasi asap, dan status api untuk keamanan ruangan Anda</p>
    </div>

    <!-- TEMPERATURE -->
    <div class="bg-[#1e293b] rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="tempChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold text-blue-500" x-text="temperature + '°C'"></span>
            <span class="text-sm font-semibold text-slate-300">Temperature</span>
        </span>
    </div>

    <!-- HUMIDITY -->
    <div class="bg-[#1e293b] rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="humChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold text-yellow-400" x-text="humidity + '%'"></span>
            <span class="text-sm font-semibold text-slate-300">Humidity</span>
        </span>
    </div>

    <!-- GAS -->
    <div class="bg-[#1e293b] rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative">
        <canvas x-ref="gasChart" width="160" height="160"></canvas>
        <span class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="text-base font-bold" :class="gasColor()" x-text="gas + ' ppm'"></span>
            <span class="text-sm font-semibold text-slate-300">Gas (<span x-text="gasLevel()"></span>)</span>
        </span>
    </div>

    <!-- FLAME -->
<div class="bg-[#1e293b] rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative group cursor-pointer transition-all hover:scale-[1.02] duration-200 ease-in-out">
    <div class="absolute inset-0 rounded-2xl bg-transparent group-hover:bg-white/5 transition-all duration-200"></div>
    <div class="z-10 flex flex-col items-center justify-center text-center">
        <span class="text-base font-bold" :class="flame ? 'text-red-500' : 'text-slate-300'" x-text="flame ? 'Api Terdeteksi' : 'Tidak Terdeteksi Api'"></span>
        <span class="text-sm font-semibold text-slate-300">Flame</span>
    </div>
</div>

<!-- BUZZER -->
<div class="bg-[#1e293b] rounded-2xl p-4 shadow h-44 w-full max-w-[230px] mx-auto flex items-center justify-center relative group cursor-pointer transition-all hover:scale-[1.02] duration-200 ease-in-out">
    <div class="absolute inset-0 rounded-2xl bg-transparent group-hover:bg-white/5 transition-all duration-200"></div>
    <div class="z-10 flex flex-col items-center justify-center text-center">
        <span class="text-base font-bold" :class="buzzer ? 'text-indigo-500' : 'text-slate-300'" x-text="buzzer ? 'Nyala' : 'Mati'"></span>
        <span class="text-sm font-semibold text-slate-300">Buzzer</span>
    </div>
</div>



    @once
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @endpush
    @endonce
</div>
