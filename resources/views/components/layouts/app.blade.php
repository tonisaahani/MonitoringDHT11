<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#0f172a] text-white min-h-screen flex">

    {{-- SIDEBAR --}}
    <aside class="w-[260px] bg-[#1e293b]/90 shadow-2xl rounded-xl my-10 ml-[5vw] z-50 flex flex-col p-6 h-fit border border-slate-700">
        <div class="space-y-8">

            {{-- LOGO --}}
            <div class="flex items-center space-x-3 px-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                <h1 class="text-white font-bold text-xl">Dashboard</h1>
            </div>

            {{-- MENU --}}
            <nav class="space-y-2 text-[15px] font-medium">
                @php
                    $menus = [
                        ['label' => 'Home', 'url' => '/', 'icon' => 'o-home'],
                        ['label' => 'Chart', 'url' => '/admin/gauge', 'icon' => 'o-presentation-chart-line'],
                        ['label' => 'Data Monitoring', 'url' => '/admin/monitoring', 'icon' => 'o-cpu-chip'],
                        // ['label' => 'Node Admin', 'url' => '/admin/node', 'icon' => 'o-server'],
                        ['label' => 'User Admin', 'url' => '/users', 'icon' => 'o-users', 'admin' => true],
                        [
                            'label' => 'Setting',
                            'url' => '#',
                            'icon' => 'o-cog',
                            'children' => [

                                // ['label' => 'Bahasa', 'url' => '/setting/language'],
                                ['label' => 'Cara Penggunaan', 'url' => '/setting/howto']
                            ]
                        ],
                    ];
                @endphp

                @foreach ($menus as $menu)
                    @if (!isset($menu['admin']) || Auth::user()->role === 'admin')
                        @php
                            $currentPath = trim(request()->path(), '/');
                            $menuPath = trim($menu['url'], '/');
                            $isActive = $currentPath === $menuPath;
                        @endphp

                        @if (isset($menu['children']))
                            <button type="button"
                                class="flex w-full items-center px-3 py-2 space-x-3 rounded-md transition font-semibold
                                {{ request()->is('setting/*') ? 'bg-purple-500/20 backdrop-blur-sm border-l-4 border-purple-400 text-white shadow-inner'
                                    : 'hover:bg-purple-900/20 border-l-4 border-transparent hover:border-purple-400' }}"
                                onclick="toggleSubMenu('{{ Str::slug($menu['label']) }}')"
                            >
                                <x-icon name="{{ $menu['icon'] }}" class="w-5 h-5" />
                                <span class="text-white flex-1 text-left">{{ $menu['label'] }}</span>
                                <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>

                            <div id="submenu-{{ Str::slug($menu['label']) }}" class="ml-6 mt-1 hidden space-y-1">
                                @foreach ($menu['children'] as $child)
                                    <a href="{{ $child['url'] }}" class="block px-3 py-1 text-sm hover:text-purple-400">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <a href="{{ $menu['url'] }}"
                                class="flex items-center px-3 py-2 space-x-3 rounded-md transition font-semibold
                                {{ $isActive ? 'bg-purple-500/20 backdrop-blur-sm border-l-4 border-purple-400 text-white shadow-inner'
                                    : 'hover:bg-purple-900/20 border-l-4 border-transparent hover:border-purple-400' }}">
                                <x-icon name="{{ $menu['icon'] }}" class="w-5 h-5" />
                                <span class="text-white">{{ $menu['label'] }}</span>
                            </a>
                        @endif
                    @endif
                @endforeach
            </nav>

            {{-- USER --}}
            <div class="mt-6 p-3 bg-slate-800/70 rounded-lg shadow-inner border border-slate-600">
                <div class="flex items-center space-x-3">
                    @if(Auth::user()->photo)
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-9 h-9 rounded-full object-cover" />
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" class="w-9 h-9 rounded-full" />
                    @endif
                    <div class="leading-tight">
                        <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-slate-400 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                    <a href="/logout" class="ml-auto text-red-400 hover:text-red-600">
                        <x-icon name="o-power" class="w-5 h-5" />
                    </a>
                </div>
            </div>
        </div>
    </aside>

    {{-- CONTENT --}}
    <main class="flex-1 p-6 min-h-screen ml-6">
        {{ $slot }}
    </main>

    {{-- SCRIPTS --}}
    @livewireScripts
    @stack('scripts')

    {{-- Toggle Submenu Script --}}
    <script>
        function toggleSubMenu(id) {
            const submenu = document.getElementById('submenu-' + id);
            submenu.classList.toggle('hidden');
        }
    </script>
</body>

</html>
