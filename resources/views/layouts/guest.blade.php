<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - JDIH Kejaksaan RI</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap&family=Google+Sans+Flex:opsz,wght@6..144,1..1000" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex lg:w-2/5 bg-ink-900 text-paper flex-col justify-between p-10 relative overflow-hidden">

            <div class="absolute inset-0 opacity-[0.04]" style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 1px, transparent 12px);"></div>

            <div class="relative">
                <div class="font-display font-semibold text-xl tracking-wide">
                    JDIH <span class="text-brass">Kejaksaan RI</span>
                </div>
            </div>

            <div class="relative">
                <!-- <div class="inline-flex items-center justify-center border-2 border-dashed border-brass rounded-full w-24 h-24 -rotate-3 mb-6">
                    <span class="font-mono text-[10px] uppercase tracking-widest text-brass text-center leading-tight">
                        Resmi<br>Terverifikasi
                    </span>
                </div> -->
                <h1 class="font-display text-3xl font-semibold leading-snug">
                    Jaringan Dokumentasi<br>dan Informasi Hukum
                </h1>
                <p class="text-paper/60 text-sm mt-3 max-w-xs leading-relaxed">
                    Portal resmi akses peraturan perundang-undangan Kejaksaan RI.
                </p>
            </div>

            <div class="relative text-paper/40 text-xs font-mono">
                &copy; {{ date('Y') }} Kejaksaan RI
            </div>
        </div>

        {{-- Panel kanan — form --}}
        <div class="w-full lg:w-3/5 flex items-center justify-center bg-paper px-6 py-12">
            <div class="w-full max-w-sm">

                <div class="lg:hidden text-center mb-8">
                    <div class="font-display font-semibold text-lg text-ink-900">
                        JDIH <span class="text-seal">Kejaksaan RI</span>
                    </div>
                </div>

                @yield('content')

            </div>
        </div>

    </div>

</body>
</html>