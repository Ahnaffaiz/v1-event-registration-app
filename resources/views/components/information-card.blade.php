<div class="p-4 duration-300 bg-white rounded-2xl">
    <div class="flex items-center">
        <x-dynamic-component :component="$icon" class="w-10 h-10 {{ $iconColor }}" />
        <div class="ml-3">
            <h3 class="text-2xl font-semibold text-slate-800">{{ $value }}</h3>
            <p class="text-sm text-slate-600">{{ $label }}</p>
        </div>
    </div>
</div>
