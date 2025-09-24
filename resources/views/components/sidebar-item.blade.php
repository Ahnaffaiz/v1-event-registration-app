@props([
    'href' => '#',
    'icon' => 'heroicon-o-home',
    'label' => 'Label',
    'activeRoute' => null,
])

@php
    $isActive = request()->routeIs($activeRoute);
@endphp

<li>
    <a wire:navigate href="{{ route($activeRoute) }}"
        class="transition-all duration-500 flex items-center p-3 space-x-3 text-sm font-medium rounded-2xl hover:bg-slate-200/50 {{ $isActive ? 'text-slate-900 bg-sky-200/50' : 'text-slate-500' }}">
        <x-dynamic-component :component="$icon" class="w-6 h-6 {{ $isActive ? 'text-slate-900' : 'text-slate-600' }}" />
        <span class="hidden ml-3 md:block sidebar-label">{{ $label }}</span>
    </a>
</li>
