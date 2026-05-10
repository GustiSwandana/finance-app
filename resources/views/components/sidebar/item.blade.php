@props(['route', 'label', 'icon'])

@php
    // Cek apakah route saat ini cocok dengan route menu ini (termasuk sub-route)
    $isActive = request()->routeIs($route) || request()->routeIs($route . '.*');
    
    // Kelas CSS untuk status Aktif vs Tidak Aktif
    $classes = $isActive
        ? 'text-emerald-600 dark:text-emerald-300 font-bold'
        : 'text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-slate-900/70 transition-colors';
@endphp

<li class="item py-[11px] rounded-lg {{ $classes }}">
    <a href="{{ route($route) }}">
        <div class="flex items-center justify-between px-2">
            <div class="flex items-center space-x-2.5">
                <span class="item-ico">
                    {{-- Render Icon SVG dari props tanpa escape --}}
                    {!! $icon !!}
                </span>
                <span class="item-text text-lg leading-none">{{ $label }}</span>
            </div>
        </div>
    </a>
</li>
