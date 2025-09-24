@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'icon' => null,
    'options' => [],
])

<div class="mt-2 space-y-2">
    @if($label)
        <label for="{{ $name }}" class="text-base font-medium text-black">
            {{ $label }}
        </label>
    @endif
    <div class="relative rounded-md shadow-sm">
        @if($icon)
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <x-dynamic-component :component="$icon" class="w-5 h-5 text-gray-400" />
            </div>
        @endif
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'mb-4 block w-full rounded-2xl p-2 border-gray-300 border focus:outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-400 sm:text-base appearance-none' .
                ($icon ? ' pl-10' : '')
            ]) }}
        >
            @if($placeholder)
                <option value="0" {{ $value ? '' : 'selected' }}>{{ $placeholder }}</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $value == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <x-heroicon-o-chevron-down />
        </div>
    </div>
</div>
