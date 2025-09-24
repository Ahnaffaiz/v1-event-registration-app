@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'icon' => null,
])

<div class="space-y-2">
    <label for="{{ $name }}" class="text-base font-medium text-black">
        {{ $label ?? ''}}
    </label>
    <div class="relative rounded-2xl">
        @if($icon)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <x-dynamic-component :component="$icon" class="w-5 h-5 text-gray-400" />
            </div>
        @endif
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            {{ $attributes->merge([
                'class' => 'mb-4 block w-full rounded-2xl p-2 border-gray-300 border focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-400 sm:text-base placeholder:text-sm' .
                ($icon ? ' pl-10' : '')
            ]) }}
        >
    </div>
</div>
