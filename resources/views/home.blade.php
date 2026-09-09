@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

    {{-- Hero --}}
    <div class="z-20 relative -mt-10 mb-20 w-screen left-1/2 -translate-x-1/2 text-center">
        <div class="relative h-[540px] overflow-hidden">
            <img src="{{ asset('images/gedung-kejaksaan.jpg') }}" alt="Kejaksaan RI" class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute inset-0 bg-ink-900/70"></div>
            <!-- <div class="absolute inset-0 opacity-[0.06]" style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 1px, transparent 12px);"></div> -->
            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-b from-transparent to-paper pointer-events-none"></div>
            
            <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 opacity-0 animate-fade-in-up [animation-delay:200ms]">
                <h1 class="text-4xl font-display font-semibold text-paper">
                    Jaringan Dokumentasi dan Informasi Hukum
                </h1>
                <p class="mt-3 text-paper/80 max-w-xl mx-auto">
                    Akses peraturan perundang-undangan dengan mudah dan cepat.
                </p>
            </div>
        </div>

        {{-- Search --}}
        <div class="relative -mt-16 max-w-3xl mx-auto opacity-0 animate-fade-in-up [animation-delay:200ms]"
            x-data="{
                advanced: false,
                query: '',
                results: [],
                open: false,
                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    let res = await fetch('/peraturan/suggest?q=' + encodeURIComponent(this.query));
                    this.results = await res.json();
                    this.open = true;
                }
            }"
            @click.outside="open = false">

            <div class="bg-white rounded-xl shadow-lg border border-ink-900/10 p-6">
                <h2 class="font-google font-semibold text-ink-700 mb-4">Pencarian Dokumen</h2>

                <form action="{{ route('peraturan.index') }}" method="GET" class="space-y-3">

                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 border-2 border-ink-900/10 rounded-lg focus-within:border-brass transition-colors">
                                <input
                                    type="text"
                                    name="search"
                                    x-model="query"
                                    @input.debounce.400ms="search()"
                                    placeholder="Cari nomor atau judul peraturan..."
                                    class="w-full px-4 py-3 outline-none font-sans text-sm rounded-lg"
                                    autocomplete="off">
                            </div>
                            <button type="submit" class="bg-ink-900 text-paper px-6 py-3 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors duration-300 flex-shrink-0" @click="loading = true">
                                Cari
                            </button>
                        </div>

                        <div x-show="open && results.length > 0"
                            x-cloak
                            class="absolute z-50 left-0 right-0 mt-2 bg-white border border-ink-900/10 rounded-lg shadow-lg overflow-hidden">
                            <template x-for="item in results" :key="item.id">
                                <a :href="item.url"
                                class="block px-4 py-3 hover:bg-paper-alt border-b border-ink-900/5 last:border-0">
                                    <div class="font-sans text-sm font-medium text-ink-900" x-text="item.nomor_lengkap"></div>
                                    <div class="text-xs text-ink-600 mt-0.5 truncate" x-text="item.tentang"></div>
                                </a>
                            </template>
                        </div>
                    </div>

                    {{-- Quick filter kategori --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs text-ink-500 mr-1">Kategori:</span>
                        @foreach ($kategori as $k)
                            <a href="{{ route('peraturan.index', ['kategori' => $k->slug]) }}"
                            class="text-xs font-google uppercase px-3 py-1.5 rounded-full border border-ink-900/15 text-ink-700 hover:bg-ink-900 hover:text-paper hover:border-ink-900 transition-colors">
                                {{ $k->singkatan }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Toggle filter lanjutan --}}
                    <div class="pt-1 border-t border-ink-900/5">
                        <button
                            type="button"
                            @click="advanced = !advanced"
                            class="flex items-center gap-1.5 text-xs text-ink-500 hover:text-brass transition-colors pt-3">
                            <span x-text="advanced ? 'Sembunyikan filter lanjutan' : 'Filter lanjutan'"></span>
                            <svg class="w-3 h-3 transition-transform" :class="advanced && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="advanced" x-cloak x-collapse class="grid grid-cols-2 md:grid-cols-3 gap-3 pt-4">
                            <select name="status" class="border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass">
                                <option value="">Semua Status</option>
                                <option value="berlaku">Berlaku</option>
                                <option value="diubah">Diubah</option>
                                <option value="dicabut">Dicabut</option>
                            </select>

                            <input type="number" name="tahun_dari" placeholder="Tahun dari"
                                class="border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass">

                            <input type="number" name="tahun_sampai" placeholder="Tahun sampai"
                                class="border border-ink-900/20 rounded-md px-3 py-2 text-sm outline-none focus:border-brass">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Statistik Singkat --}}
    <div class="bg-paper-alt -mx-4 px-6 py-8 mb-10 rounded-md opacity-0 animate-fade-in-up [animation-delay:200ms]">
        <div class="relative z-0 mt-2 mb-10 max-w-6xl mx-auto opacity-0 animate-fade-in-up [animation-delay:200ms]">
            <h2 class="text-center text-xl font-google font-semibold text-ink-900 mb-10 uppercase">Statistik Peraturan</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Total --}}
                <a href="{{ route('peraturan.index') }}"
                    x-data="{ show: false }"
                    x-intersect.once="show = true"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="group relative bg-white border-l-4 border-ink-900/10 rounded-xl p-7 text-center transition-all duration-300 hover:border-brass hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex justify-center mb-3">
                        <!-- <div class="w-14 h-14 px-2 py-2 rounded-xl bg-ink-900/5 flex items-center justify-center group-hover:bg-brass/15 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path d="M384 96L512 96C529.7 96 544 110.3 544 128C544 145.7 529.7 160 512 160L398.4 160C393.2 185.8 375.5 207.1 352 217.3L352 512L512 512C529.7 512 544 526.3 544 544C544 561.7 529.7 576 512 576L128 576C110.3 576 96 561.7 96 544C96 526.3 110.3 512 128 512L288 512L288 217.3C264.5 207 246.8 185.7 241.6 160L128 160C110.3 160 96 145.7 96 128C96 110.3 110.3 96 128 96L256 96C270.6 76.6 293.8 64 320 64C346.2 64 369.4 76.6 384 96zM439.6 384L584.4 384L512 259.8L439.6 384zM512 480C449.1 480 396.8 446 386 401.1C383.4 390.1 387 378.8 392.7 369L487.9 205.8C492.9 197.2 502.1 192 512 192C521.9 192 531.1 197.3 536.1 205.8L631.3 369C637 378.8 640.6 390.1 638 401.1C627.2 445.9 574.9 480 512 480zM126.8 259.8L54.4 384L199.3 384L126.8 259.8zM.9 401.1C-1.7 390.1 1.9 378.8 7.6 369L102.8 205.8C107.8 197.2 117 192 126.9 192C136.8 192 146 197.3 151 205.8L246.2 369C251.9 378.8 255.5 390.1 252.9 401.1C242.1 445.9 189.8 480 126.9 480C64 480 11.7 446 .9 401.1z"/>
                            </svg>
                        </div> -->
                        <img src="{{ asset('icon/peraturan.svg') }}" alt="Berlaku" class="w-10 h-10">
                    </div>
                    <div class="text-4xl font-display font-semibold text-brass">
                        {{ $stats['total'] ?? 0 }}
                    </div>
                    <div class="text-xs text-ink-500 font-mono uppercase tracking-wider">
                        Total Peraturan
                    </div>
                    <div class="mt-4 text-[12px] text-ink-400 group-hover:text-brass transition-colors">
                        <span class="font-medium bg-slate-200 group-hover:text-brass rounded-md border border-ink-900/20 px-6 py-1 text-[11px] font-sans tracking-widest">
                            Lihat semua →
                        </span>
                    </div>
                </a>

                {{-- Berlaku --}}
                <a href="{{ route('peraturan.index', ['status' => 'berlaku']) }}"
                    x-data="{ show: false }"
                    x-intersect.once="show = true"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="group bg-white border-l-4 border-ink-900/10 rounded-xl p-7 text-center transition-all duration-300 hover:border-teal hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex justify-center mb-3">
                        <!-- <div class="w-14 h-14 px-2 py-2 rounded-xl bg-ink-900/5 flex items-center justify-center group-hover:bg-brass/15 transition-colors"> -->
                            <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path d="M320 576C178.6 576 64 461.4 64 320C64 178.6 178.6 64 320 64C461.4 64 576 178.6 576 320C576 461.4 461.4 576 320 576zM438 209.7C427.3 201.9 412.3 204.3 404.5 215L285.1 379.2L233 327.1C223.6 317.7 208.4 317.7 199.1 327.1C189.8 336.5 189.7 351.7 199.1 361L271.1 433C276.1 438 282.9 440.5 289.9 440C296.9 439.5 303.3 435.9 307.4 430.2L443.3 243.2C451.1 232.5 448.7 217.5 438 209.7z"/>
                            </svg>
                        </div> -->
                        <img src="{{ asset('icon/berlaku.svg') }}" alt="Berlaku" class="w-10 h-10">
                    </div>
                    <div class="text-4xl font-display font-semibold text-teal-700">
                        {{ $stats['berlaku'] ?? 0 }}
                    </div>
                    <div class="text-xs text-ink-500 mt-1 font-mono uppercase tracking-wider">
                        Peraturan Berlaku
                    </div>
                    <div class="mt-3 text-[12px] text-ink-400 group-hover:text-teal-700 transition-colors">
                        <span class="font-medium bg-slate-200 group-hover:text-teal-700 rounded-md border border-teal-700/20 px-6 py-1 text-[11px] font-sans tracking-widest">
                            Lihat →
                        </span>
                    </div>
                </a>

                {{-- Diubah --}}
                <a href="{{ route('peraturan.index', ['status' => 'diubah']) }}"
                    x-data="{ show: false }"
                    x-intersect.once="show = true"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="group bg-white border-l-4 border-ink-900/10 rounded-lg p-7 text-center transition-all duration-300 hover:border-amber-700 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex justify-center mb-3">
                        <!-- <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path d="M535.6 85.7C513.7 63.8 478.3 63.8 456.4 85.7L432 110.1L529.9 208L554.3 183.6C576.2 161.7 576.2 126.3 554.3 104.4L535.6 85.7zM236.4 305.7C230.3 311.8 225.6 319.3 222.9 327.6L193.3 416.4C190.4 425 192.7 434.5 199.1 441C205.5 447.5 215 449.7 223.7 446.8L312.5 417.2C320.7 414.5 328.2 409.8 334.4 403.7L496 241.9L398.1 144L236.4 305.7zM160 128C107 128 64 171 64 224L64 480C64 533 107 576 160 576L416 576C469 576 512 533 512 480L512 384C512 366.3 497.7 352 480 352C462.3 352 448 366.3 448 384L448 480C448 497.7 433.7 512 416 512L160 512C142.3 512 128 497.7 128 480L128 224C128 206.3 142.3 192 160 192L256 192C273.7 192 288 177.7 288 160C288 142.3 273.7 128 256 128L160 128z"/>
                            </svg>
                        </div> -->
                        <img src="{{ asset('icon/diubah.svg') }}" alt="Diubah" class="w-10 h-10">
                    </div>
                    <div class="text-4xl font-display font-semibold text-amber-700">
                        {{ $stats['diubah'] ?? 0 }}
                    </div>
                    <div class="text-xs text-ink-500 mt-1 font-mono uppercase tracking-wider">
                        Peraturan Diubah
                    </div>
                    <div class="mt-3 text-[12px] text-ink-400 group-hover:text-amber-700 transition-colors">
                        <span class="font-medium bg-slate-200 group-hover:text-amber-700 rounded-md border border-brass-900/20 px-6 py-1 text-[11px] font-sans tracking-widest">
                            Lihat →
                        </span>
                    </div>
                </a>

                {{-- Dicabut --}}
                <a href="{{ route('peraturan.index', ['status' => 'dicabut']) }}"
                    x-data="{ show: false }"
                    x-intersect.once="show = true"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    class="group bg-white border-l-4 border-ink-900/10 rounded-lg p-7 text-center transition-all duration-300 hover:border-rose-700 hover:shadow-md hover:-translate-y-0.5">
                    <div class="flex justify-center mb-3">
                        <!-- <div class="w-10 h-10 rounded-full flex items-center justify-center transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                <path d="M320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320C64 461.4 178.6 576 320 576zM231 231C240.4 221.6 255.6 221.6 264.9 231L319.9 286L374.9 231C384.3 221.6 399.5 221.6 408.8 231C418.1 240.4 418.2 255.6 408.8 264.9L353.8 319.9L408.8 374.9C418.2 384.3 418.2 399.5 408.8 408.8C399.4 418.1 384.2 418.2 374.9 408.8L319.9 353.8L264.9 408.8C255.5 418.2 240.3 418.2 231 408.8C221.7 399.4 221.6 384.2 231 374.9L286 319.9L231 264.9C221.6 255.5 221.6 240.3 231 231z"/>
                            </svg>
                        </div> -->
                        <img src="{{ asset('icon/dicabut.svg') }}" alt="Dicabut" class="w-10 h-10">
                    </div>
                    <div class="text-4xl font-display font-semibold text-seal">
                        {{ $stats['dicabut'] ?? 0 }}
                    </div>
                    <div class="text-xs text-ink-500 mt-1 font-mono uppercase tracking-wider">
                        Peraturan Dicabut
                    </div>
                    <div class="mt-3 text-[12px] text-ink-400 group-hover:text-seal transition-colors">
                        <span class="font-medium bg-slate-200 group-hover:text-seal rounded-md border border-seal-900/20 px-6 py-1 text-[11px] font-sans tracking-widest">
                            Lihat →
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- Kategori Peraturan --}}
    <div class="mt-14 opacity-0 animate-fade-in-up [animation-delay:200ms]">
        <h2 class="text-center text-xl font-google font-semibold text-ink-900 mb-3 uppercase tracking-wide">
            Kategori Peraturan
        </h2>
        <p class="text-center text-sm text-ink-500 mb-10 max-w-lg mx-auto">
            Pilih jenis peraturan untuk melihat daftar dokumen.
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-5">
            @foreach ($kategori as $k)
                @php
                    $icon = match (strtoupper($k->singkatan)) {
                        'UU' => 'uu',
                        'PP' => 'pp',
                        'PERPRES' => 'perpres',
                        'PERMEN' => 'permen',
                        'PERDA' => 'perda',
                        default => 'default',
                    };
                @endphp

                <a href="{{ route('peraturan.index', ['kategori' => [$k->slug]]) }}"
                x-data="{ show: false }"
                x-intersect.once="show = true"
                :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                style="transition-delay: {{ $loop->index * 60 }}ms"
                @class([
                    'group flex flex-col items-center text-center bg-white border border-ink-900/10 rounded-xl p-6
                        transition-all duration-300 hover:border-brass hover:shadow-lg hover:-translate-y-1',
                    'opacity-50 pointer-events-none' => $k->peraturan_count === 0,
                ])>

                    {{-- Ikon --}}
                    <div class="w-14 h-14 rounded-2xl bg-ink-900/5 flex items-center justify-center mb-4
                                group-hover:bg-brass/15 transition-colors">
                        @if ($icon === 'uu')
                            {{-- Scale / hukum --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            </svg>
                        @elseif ($icon === 'pp')
                            {{-- Building / pemerintah --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        @elseif ($icon === 'perpres')
                            {{-- Badge / presiden --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        @elseif ($icon === 'permen')
                            {{-- Clipboard / menteri --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        @elseif ($icon === 'perda')
                            {{-- Map / daerah --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        @else
                            {{-- Default dokumen --}}
                            <svg class="w-7 h-7 text-ink-700 group-hover:text-brass transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @endif
                    </div>

                    <div class="font-display text-xl font-semibold text-ink-900 group-hover:text-seal transition-colors">
                        {{ $k->singkatan }}
                    </div>
                    <div class="text-xs text-ink-500 mt-1 line-clamp-2 min-h-[2rem]">
                        {{ $k->nama }}
                    </div>
                    <div class="mt-3 text-xs font-mono text-ink-600 tracking-wide">
                        {{ $k->peraturan_count }} dokumen
                    </div>
                    <div class="mt-4 text-sm font-medium text-seal group-hover:text-brass transition-colors inline-flex items-center gap-1">
                        Lihat dokumen
                        <span class="inline-block transition-transform group-hover:translate-x-0.5">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- Peraturan terbaru --}}
    <div class="mt-10 opacity-0 animate-fade-in-up [animation-delay:200ms]">
        <h2 class="text-center text-xl font-google font-semibold text-ink-900 mb-10 uppercase">Peraturan Terbaru</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($terbaru as $peraturan)
                <a href="{{ route('peraturan.show', $peraturan->slug) }}"
                    x-data="{ show: false }"
                    x-intersect.once="show = true"
                    :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    style="transition-delay: {{ $loop->index * 75 }}ms"
                    class="group flex flex-col bg-white border border-ink-900/10 rounded-lg p-5 transition-all duration-300 hover:border-ink-900/30 hover:shadow-md h-full">

                    <div class="flex justify-between items-start gap-4 mb-2">
                        <span class="inline-block bg-ink-900 text-paper text-[10px] font-mono uppercase tracking-wider px-2 py-1 rounded">
                            {{ $peraturan->kategori->singkatan }}
                        </span>
                        <x-status-pill :status="$peraturan->status" class="flex-shrink-0" />
                    </div>

                    <h3 class="font-google text-sm font-medium text-ink-900 group-hover:text-seal transition-colors">
                        {{ $peraturan->kategori->singkatan }} No. {{ $peraturan->nomor }}/{{ $peraturan->tahun }}
                    </h3>

                    <p class="text-sm text-ink-700 mt-1.5 leading-relaxed">
                        {{ Str::limit($peraturan->tentang, 110) }}
                    </p>

                    <div class="mt-auto pt-3 border-t border-ink-900/5 flex items-center gap-3 text-xs text-ink-500 font-mono">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $peraturan->created_at?->format('d M Y') }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ $peraturan->views ?? 0 }}x dilihat
                        </span>
                    </div>

                </a>
            @empty
                <p class="text-gray-500">Belum ada data peraturan.</p>
            @endforelse
        </div>
    </div>

@endsection

<!-- <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchAutocomplete', () => ({
            query: '',
            results: [],
            open: false,
            loading: false,
            highlighted: -1,

            async fetchSuggestions() {
                if (this.query.trim().length < 2) {
                    this.results = [];
                    this.open = false;
                    this.highlighted = -1;
                    return;
                }

                this.loading = true;

                try {
                    const response = await fetch(
                        `/api/peraturan/suggest?q=${encodeURIComponent(this.query.trim())}`,
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        }
                    );

                    if (!response.ok) throw new Error('Network error');

                    this.results = await response.json();
                    this.open = true;
                    this.highlighted = this.results.length > 0 ? 0 : -1;
                } catch (e) {
                    console.error('Autocomplete error:', e);
                    this.results = [];
                } finally {
                    this.loading = false;
                }
            },

            highlightNext() {
                if (this.results.length === 0) return;
                this.highlighted = (this.highlighted + 1) % this.results.length;
            },

            highlightPrev() {
                if (this.results.length === 0) return;
                this.highlighted = (this.highlighted - 1 + this.results.length) % this.results.length;
            },

            selectHighlighted() {
                if (this.highlighted >= 0 && this.results[this.highlighted]) {
                    window.location.href = this.results[this.highlighted].url;
                } else if (this.query.trim()) {
                    // Submit form biasa
                    this.$el.closest('form').submit();
                }
            },
        }));
    });
</script> -->