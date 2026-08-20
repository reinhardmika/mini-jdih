@if (session('success') || session('error'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="mb-6"
    >
        @if (session('success'))
            <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm rounded-md px-4 py-3">
                <span>{{ session('success') }}</span>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-900 ml-4">✕</button>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-center justify-between bg-red-50 border border-red-200 text-seal text-sm rounded-md px-4 py-3">
                <span>{{ session('error') }}</span>
                <button @click="show = false" class="text-seal hover:text-red-900 ml-4">✕</button>
            </div>
        @endif
    </div>
@endif