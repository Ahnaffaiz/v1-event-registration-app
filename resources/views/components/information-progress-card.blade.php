@props(['checkedIn', 'total'])

@php
    $percentage = $total > 0 ? ($checkedIn / $total) * 100 : 0;
@endphp

<div class="relative p-4 overflow-hidden bg-white rounded-2xl">
    <div class="relative z-10 flex items-center justify-between">
        <div class="flex items-center">
            <x-heroicon-o-arrow-right-start-on-rectangle class="w-10 h-10 text-sky-600" />
            <div class="ml-3">
                <h3 class="text-xl font-semibold text-slate-800">{{ $checkedIn }}/{{ $total }}</h3>
                <p class="text-sm font-normal text-slate-600">Check In</p>
            </div>
        </div>
        <div>
            <span class="text-2xl font-bold text-sky-700">
                {{ round($percentage) }}%
            </span>
        </div>
    </div>

    <div class="absolute inset-0 bg-sky-100"
        style="clip-path: polygon(0 0, {{ $percentage }}% 0, {{ $percentage }}% 100%, 0% 100%);">
        <svg class="absolute right-0 w-12 h-full" viewBox="0 0 10 100" preserveAspectRatio="none">
            <path fill="white" fill-opacity="0.3" d="M0 100 C 3 90, 7 80, 10 100 L 10 0 L 0 0 Z" />
        </svg>
    </div>
</div>
