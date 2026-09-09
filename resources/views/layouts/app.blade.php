<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JDIH') - Kejaksaan RI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap&family=Google+Sans+Flex:opsz,wght@6..144,1..1000" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink-900 font-sans antialiased">

    <nav
        class="bg-ink-700 text-paper border-b-4 border-brass sticky top-0 z-30"
        x-data="{ open: false }"
    >
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">

                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <span class="font-display font-semibold text-lg tracking-wide">
                        Mini
                        <span class="text-brass group-hover:text-brass/90 transition-colors">
                            JDIH
                        </span>
                    </span>
                </a>

                {{-- Desktop menu --}}
                <div class="hidden md:flex items-center gap-1 text-sm font-bold">
                    <a href="{{ route('home') }}"
                    class="px-3 py-2 rounded-md transition-colors
                            {{ request()->routeIs('home') ? 'bg-white/10 text-brass' : 'hover:bg-white/10 hover:text-brass' }}">
                        Beranda
                    </a>
                    <a href="{{ route('peraturan.index') }}"
                    class="px-3 py-2 rounded-md transition-colors
                            {{ request()->routeIs('peraturan.*') ? 'bg-white/10 text-brass' : 'hover:bg-white/10 hover:text-brass' }}">
                        Peraturan
                    </a>

                    @auth
                        @if (Auth::user()->role === 'admin')
                            <div class="relative" 
                                x-data="{ open: false }" 
                                @mouseenter="open = true" 
                                @mouseleave="open = false">
                                
                                <button
                                    type="button"
                                    class="flex items-center gap-1.5 px-3 py-2 rounded-md transition-colors
                                        {{ request()->routeIs('admin.*') ? 'bg-white/10 text-brass' : 'hover:bg-white/10 hover:text-brass' }}"
                                >
                                    Kelola Data
                                    <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 -translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-100"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-1"
                                    class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-lg border border-ink-900/10 py-1.5 text-ink-900 overflow-hidden"
                                >
                                    <a href="{{ route('admin.peraturan.index') }}"
                                    @click="open = false"
                                    class="block px-4 py-2.5 text-sm hover:bg-paper-alt transition-colors
                                            {{ request()->routeIs('admin.peraturan.*') ? 'bg-paper-alt' : '' }}">
                                        Kelola Peraturan
                                    </a>
                                    <a href="{{ route('admin.kategori.index') }}"
                                    @click="open = false"
                                    class="block px-4 py-2.5 text-sm hover:bg-paper-alt transition-colors
                                            {{ request()->routeIs('admin.kategori.*') ? 'bg-paper-alt' : '' }}">
                                        Kelola Kategori
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Akun --}}
                        <div class="relative ml-1" 
                            x-data="{ open: false }" 
                            @mouseenter="open = true" 
                            @mouseleave="open = false">
                            
                            <button
                                type="button"
                                class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-white/10 transition-colors"
                            >
                                <span class="w-7 h-7 rounded-full bg-brass/20 text-brass text-xs font-semibold flex items-center justify-center">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-ink-900/10 py-1.5 text-ink-900 overflow-hidden"
                            >
                                <a href="{{ route('dashboard') }}"
                                @click="open = false"
                                class="block px-4 py-2.5 text-sm hover:bg-paper-alt transition-colors
                                        {{ request()->routeIs('dashboard') ? 'bg-paper-alt' : '' }}">
                                    Dashboard
                                </a>
                                <div class="border-t border-ink-900/5 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            @click="open = false"
                                            class="w-full text-left px-4 py-2.5 text-sm text-rose-700 hover:bg-rose-50 transition-colors">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                        class="ml-2 px-4 py-2 rounded-md hover:bg-white/10 hover:text-brass transition-colors">
                            Login
                        </a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button
                    @click="open = !open"
                    type="button"
                    class="md:hidden w-10 h-10 flex items-center justify-center rounded-md hover:bg-white/10 transition-colors"
                    aria-label="Menu"
                    :aria-expanded="open"
                >
                    <div class="w-5 h-4 flex flex-col justify-between">
                        <span class="block h-0.5 bg-paper rounded transition-all duration-300 origin-center"
                            :class="open ? 'rotate-45 translate-y-[7px]' : ''"></span>
                        <span class="block h-0.5 bg-paper rounded transition-all duration-300"
                            :class="open ? 'opacity-0 scale-x-0' : ''"></span>
                        <span class="block h-0.5 bg-paper rounded transition-all duration-300 origin-center"
                            :class="open ? '-rotate-45 -translate-y-[7px]' : ''"></span>
                    </div>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-paper/10 bg-ink-800"
        >
            <div class="px-3 py-3 space-y-0.5 text-sm">
                <a href="{{ route('home') }}" @click="open = false"
                class="block px-3 py-2.5 rounded-md transition-colors
                        {{ request()->routeIs('home') ? 'bg-white/10 text-brass' : 'hover:bg-white/10 hover:text-brass' }}">
                    Beranda
                </a>
                <a href="{{ route('peraturan.index') }}" @click="open = false"
                class="block px-3 py-2.5 rounded-md transition-colors
                        {{ request()->routeIs('peraturan.*') ? 'bg-white/10 text-brass' : 'hover:bg-white/10 hover:text-brass' }}">
                    Peraturan
                </a>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <div class="border-t border-paper/10 my-2"></div>
                        <p class="px-3 py-1 text-[11px] uppercase tracking-wider text-paper/40">Kelola Data</p>
                        <a href="{{ route('admin.peraturan.index') }}" @click="open = false"
                        class="block px-3 py-2.5 rounded-md hover:bg-white/10 hover:text-brass transition-colors">
                            Kelola Peraturan
                        </a>
                        <a href="{{ route('admin.kategori.index') }}" @click="open = false"
                        class="block px-3 py-2.5 rounded-md hover:bg-white/10 hover:text-brass transition-colors">
                            Kelola Kategori
                        </a>
                    @endif

                    <div class="border-t border-paper/10 my-2"></div>
                    <a href="{{ route('dashboard') }}" @click="open = false"
                    class="block px-3 py-2.5 rounded-md hover:bg-white/10 hover:text-brass transition-colors">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" @click="open = false"
                                class="w-full text-left px-3 py-2.5 rounded-md text-rose-300 hover:bg-white/10 transition-colors">
                            Logout
                        </button>
                    </form>
                @else
                    <div class="border-t border-paper/10 my-2"></div>
                    <a href="{{ route('login') }}" @click="open = false"
                    class="block px-3 py-2.5 rounded-md bg-brass/20 text-brass hover:bg-brass/30 font-medium text-center transition-colors">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-8 py-8 min-h-screen relative">
        @yield('content')
    </main>

    <footer class="bg-ink-700 text-paper border-t-4 border-brass mt-16">
        <div class="max-w-6xl mx-auto px-8 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="{{ route('home') }}" class="inline-block font-display font-semibold text-lg tracking-wide">
                        Mini <span class="text-brass">JDIH</span>
                    </a>
                    <p class="mt-3 text-sm text-paper/70 leading-relaxed">
                        Jaringan Dokumentasi dan Informasi Hukum Kejaksaan RI —
                        akses peraturan perundang-undangan dengan mudah dan cepat.
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-brass mb-4">
                        Navigasi
                    </h3>
                    <ul class="space-y-2 text-sm text-paper/80">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-brass transition-colors">Beranda</a>
                        </li>
                        <li>
                            <a href="{{ route('peraturan.index') }}" class="hover:text-brass transition-colors">Daftar Peraturan</a>
                        </li>
                        @auth
                            <li>
                                <a href="{{ route('dashboard') }}" class="hover:text-brass transition-colors">Dashboard</a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}" class="hover:text-brass transition-colors">Login</a>
                            </li>
                        @endauth
                    </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-brass mb-4">
                        Kategori
                    </h3>
                    <ul class="space-y-2 text-sm text-paper/80">
                    @foreach ($footerKategori as $k)
                        <li>
                            <a href="{{ route('peraturan.index', ['kategori' => [$k->slug]]) }}"
                            class="hover:text-brass transition-colors">
                                {{ $k->nama }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                </div>

                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-brass mb-4">
                        Informasi
                    </h3>
                    <ul class="space-y-2 text-sm text-paper/80">
                        <li class="text-paper/70">Kejaksaan RI</li>
                        <li class="text-paper/60 text-xs leading-relaxed">
                            Portal dokumentasi hukum untuk keperluan informasi publik.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-paper/10">
            <div class="max-w-6xl mx-auto px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-paper/50">
                <p>&copy; {{ date('Y') }} Kejaksaan RI — Jaringan Dokumentasi dan Informasi Hukum</p>
                <p class="text-paper/40">Mini JDIH</p>
            </div>
        </div>
    </footer>

</body>
</html>