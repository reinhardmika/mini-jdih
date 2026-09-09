@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="border border-ink-900/10 rounded-xl p-6 bg-paper shadow-md opacity-0 animate-fade-in-up [animation-delay:200ms]">
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div class="flex justify-center">
                <img src="{{ asset('favicon.svg') }}" alt="JDIH" class="w-16 h-16">
            </div>
                
            <h2 class="text-center font-display text-2xl font-semibold text-ink-900 mb-1">Masuk</h2>
            <p class="text-center text-ink-500 text-sm mb-8">Masuk untuk mengelola data peraturan.</p>

            <div>
                <label for="email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required autofocus
                        class="w-full border border-ink-900/20 rounded-md pl-10 px-3 py-2.5 text-sm focus:border-brass focus:ring-0 outline-none">
                    @error('email') <p class="text-seal text-xs mt-1.5">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-ink-700 mb-1.5">Password</label>
                <div x-data="{ show: false }" class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input :type="show ? 'text' : 'password'" id="password" name="password" placeholder="••••••••" required
                        class="w-full border border-ink-900/20 rounded-md pl-10 px-3 py-2.5 text-sm focus:border-brass focus:ring-0 outline-none no-native-reveal">
                    @error('password') <p class="text-seal text-xs mt-1.5">{{ $message }}</p> @enderror
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 012.293-3.95m3.242-2.55A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.965 9.965 0 01-4.132 5.043M9.878 9.878a3 3 0 104.243 4.243M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-ink-700">
                    <input type="checkbox" name="remember" class="rounded border-ink-900/30 text-ink-900 focus:ring-brass">
                    Ingat saya
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-ink-500 hover:text-brass transition-colors">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit"
                    class="w-full bg-ink-900 text-paper py-2.5 rounded-md text-sm font-medium hover:bg-teal-700 transition-colors duration-300">
                Masuk
            </button>

            @if (Route::has('register'))
                <p class="text-center text-sm text-ink-500 pt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-ink-900 font-medium hover:text-brass transition-colors">Daftar</a>
                </p>
            @endif

        </form>
    </div>

@endsection

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        if (input.type === 'password') {
            input.type = 'text';
        } else {
            input.type = 'password';
        }
    }
</script>