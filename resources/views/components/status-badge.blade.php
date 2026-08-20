@props(['status'])

@php
    $color = match($status) {
        'berlaku' => 'text-emerald-700 border-emerald-700',
        'dicabut' => 'text-seal border-seal',
        'diubah' => 'text-brass border-brass',
        default => 'text-ink-500 border-ink-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center border-2 border-dashed rounded-full px-3 py-1 text-[10px] font-mono uppercase tracking-widest -rotate-2 $color"]) }}>
    {{ $status }}
</span>