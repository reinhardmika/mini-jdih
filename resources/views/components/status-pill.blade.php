@props(['status'])

@php
    $style = match($status) {
        'berlaku' => 'bg-emerald-100 text-emerald-700',
        'dicabut' => 'bg-rose-100 text-seal',
        'diubah' => 'bg-amber-100 text-brass',
        default => 'bg-gray-100 text-ink-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-mono uppercase tracking-wide $style"]) }}>
    {{ $status }}
</span>