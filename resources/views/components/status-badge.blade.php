@props(['status'])

@php
    $color = match($status) {
        'berlaku'  => 'bg-emerald-50 text-emerald-800 border-emerald-200',
        'diubah'   => 'bg-amber-50 text-amber-800 border-amber-200',
        'dicabut'  => 'bg-stone-50 text-rose-700 border-rose-200',
        default    => 'bg-gray-50 text-gray-800 border-gray-200',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center justify-center border-2 border-dashed rounded-full px-3 py-1 text-[10px] font-mono uppercase tracking-widest -rotate-2 $color"]) }}>
    {{ $status }}
</span>