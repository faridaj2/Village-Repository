<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'SIDESA') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false, sidebarCollapsed: false }" class="min-h-screen">
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'" class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-gray-200/80 transition-all duration-300" :style="sidebarCollapsed ? 'width: 80px' : 'width: 260px'">
                <!-- Logo -->
                <div class="flex items-center h-16 px-5 border-b border-gray-100">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <img src="{{ asset('logo-tagline.png') }}" alt="SIDESA" class="h-9 w-auto" x-show="!sidebarCollapsed">
                        <img src="{{ asset('icon.png') }}" alt="SIDESA" class="h-9 w-auto" x-show="sidebarCollapsed" x-cloak>
                    </a>
                    <button @click="sidebarOpen = false" class="lg:hidden ml-auto p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Menu Utama</p>

                    @php
                        $navItems = [
                            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                            ['route' => 'penduduk.index', 'label' => 'Penduduk', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>'],
                            ['route' => 'kartu-keluarga.index', 'label' => 'Kartu Keluarga', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>'],
                            ['route' => 'rumah.index', 'label' => 'Rumah', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>'],
                            ['route' => 'peta.index', 'label' => 'Peta Desa', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>'],
                            ['route' => 'fasilitas-umum.index', 'label' => 'Fasilitas Umum', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/><circle cx="12" cy="10" r="2" stroke-width="1.5"/>'],
                            ['route' => 'wilayah.index', 'label' => 'Wilayah (RW/RT)', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                            ['route' => 'struktur-pemerintahan.index', 'label' => 'Struktur Pemerintahan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>'],
                            ['route' => 'surat.index', 'label' => 'Surat', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                            ['route' => 'pengumuman.index', 'label' => 'Pengumuman', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>'],
                            ['route' => 'media.index', 'label' => 'File / Media', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                        ];
                    @endphp

                    @foreach ($navItems as $item)
                        @php $active = request()->routeIs($item['route'] === 'dashboard' ? 'dashboard' : str_replace('.index', '.*', $item['route'])); @endphp
                        <a href="{{ route($item['route']) }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center {{ $active ? 'bg-indigo-100' : 'bg-gray-100 group-hover:bg-gray-200' }} transition-colors">
                                <svg class="w-[18px] h-[18px] {{ $active ? 'text-indigo-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $item['icon'] !!}</svg>
                            </div>
                            <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ $item['label'] }}</span>
                            @if ($active)
                                <div class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                            @endif
                        </a>
                    @endforeach

                    @if(auth()->user()->isSuperAdmin())
                    <div class="pt-4 mt-4 border-t border-gray-100">
                        <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                        @php $active = request()->routeIs('admin-settings.*'); @endphp
                        <a href="{{ route('admin-settings.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center {{ $active ? 'bg-indigo-100' : 'bg-gray-100 group-hover:bg-gray-200' }} transition-colors">
                                <svg class="w-[18px] h-[18px] {{ $active ? 'text-indigo-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <span x-show="!sidebarCollapsed" x-transition class="truncate">Pengaturan</span>
                        </a>
                        @php $active = request()->routeIs('user.*'); @endphp
                        <a href="{{ route('user.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center {{ $active ? 'bg-indigo-100' : 'bg-gray-100 group-hover:bg-gray-200' }} transition-colors">
                                <svg class="w-[18px] h-[18px] {{ $active ? 'text-indigo-600' : 'text-gray-500 group-hover:text-gray-700' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            </div>
                            <span x-show="!sidebarCollapsed" x-transition class="truncate">Kelola User</span>
                        </a>
                    </div>
                    @endif
                </nav>

                <!-- User -->
                <div class="p-3 border-t border-gray-100">
                    <div class="flex items-center gap-3 px-2 py-2">
                        <div class="flex-shrink-0 w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div x-show="!sidebarCollapsed" x-transition class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" x-show="!sidebarCollapsed">
                            @csrf
                            <button type="submit" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Keluar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main -->
            <div class="transition-all duration-300 lg:ml-[260px]" :class="{ 'lg:!ml-[80px]': sidebarCollapsed }">
                <!-- Topbar -->
                <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-gray-200/80">
                    <div class="flex items-center justify-between gap-3 px-4 sm:px-6 min-h-[4rem]">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-gray-500 hover:bg-gray-100 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                            <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:block p-2 rounded-xl text-gray-500 hover:bg-gray-100 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </button>
                            @if (isset($header))
                                <div class="min-w-0 flex-1">{{ $header }}</div>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="text-sm text-gray-500 hidden sm:block">{{ now()->format('l, d M Y') }}</span>
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
        @stack('scripts')
        @livewireScripts
    </body>
</html>
