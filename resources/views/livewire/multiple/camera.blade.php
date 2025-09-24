<div x-data="{ barcode: '' }"
    @input.debounce.2s="if (barcode.length > 0) { $wire.processCheckin(barcode); barcode = ''; }">
    <div class="container mx-auto">
        <div class="h-[250px] w-[250px]">
            <x-square-camera id="preview" />
        </div>
        @include('livewire.multiple.status')
    </div>

</div>
