<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'JDIH') - Kejaksaan Negeri Trenggalek</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-paper text-ink-900 font-sans antialiased">

    <nav class="bg-ink-900 text-paper border-b-4 border-brass" x-data="{ open: false }">
        <div class="max-w-6xl mx-auto px-4 flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="font-display font-semibold text-lg tracking-wide">
                Mini JDIH <span class="text-brass">Kejaksaan Negeri Trenggalek</span>
            </a>

            {{-- Desktop menu --}}
            <div class="hidden md:flex space-x-6 text-sm items-center">
                <a href="{{ route('home') }}" class="hover:text-brass transition-colors">Beranda</a>
                <a href="{{ route('peraturan.index') }}" class="hover:text-brass transition-colors">Peraturan</a>
                @auth
                    @if (Auth::user()->role === 'admin')
                        <a href="{{ route('admin.peraturan.index') }}" class="hover:text-brass transition-colors">Kelola Peraturan</a>
                        <a href="{{ route('admin.kategori.index') }}" class="hover:text-brass transition-colors">Kelola Kategori</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="hover:text-brass transition-colors">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-brass transition-colors">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-brass transition-colors">Login</a>
                @endauth
            </div>

            {{-- Mobile hamburger button --}}
            <button @click="open = !open" class="md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak class="md:hidden border-t border-paper/10 px-4 py-3 space-y-3 text-sm">
            <a href="{{ route('home') }}" class="block hover:text-brass transition-colors">Beranda</a>
            <a href="{{ route('peraturan.index') }}" class="block hover:text-brass transition-colors">Peraturan</a>
            @auth
                @if (Auth::user()->role === 'admin')
                    <a href="{{ route('admin.peraturan.index') }}" class="block hover:text-brass transition-colors">Kelola Peraturan</a>
                    <a href="{{ route('admin.kategori.index') }}" class="block hover:text-brass transition-colors">Kelola Kategori</a>
                @endif
                <a href="{{ route('dashboard') }}" class="block hover:text-brass transition-colors">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-brass transition-colors">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block hover:text-brass transition-colors">Login</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-10 min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-ink-900 text-paper/70 text-sm text-center py-6 mt-16 border-t-4 border-brass">
        &copy; {{ date('Y') }} Kejaksaan Negeri Trenggalek — Jaringan Dokumentasi dan Informasi Hukum
    </footer>

</body>
</html>