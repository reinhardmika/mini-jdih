<x-guest-layout>

    <h2 class="font-display text-2xl font-semibold text-ink-900 mb-1">Masuk</h2>
    <p class="text-ink-500 text-sm mb-8">Masuk untuk mengelola data peraturan.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-ink-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2.5 text-sm focus:border-brass focus:ring-0 outline-none">
            @error('email') <p class="text-seal text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-ink-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required
                   class="w-full border border-ink-900/20 rounded-md px-3 py-2.5 text-sm focus:border-brass focus:ring-0 outline-none">
            @error('password') <p class="text-seal text-xs mt-1.5">{{ $message }}</p> @enderror
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
                class="w-full bg-ink-900 text-paper py-2.5 rounded-md text-sm font-medium hover:bg-seal transition-colors duration-300">
            Masuk
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-ink-500 pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-ink-900 font-medium hover:text-brass transition-colors">Daftar</a>
            </p>
        @endif

    </form>

</x-guest-layout>