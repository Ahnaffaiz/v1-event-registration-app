<div x-data="{ barcode: '' }"
    @input.debounce.2s="if (barcode.length > 0) { $wire.processCheckin(barcode); barcode = ''; }">
    <x-input-text name="name" label="Input Code Here" placeholder="Ticket Code" x-model="barcode"
        icon="heroicon-s-qr-code" autofocus @class(['mt-2']) />
    <?php
$activities = [];
foreach ($event?->activities as $activity) {
    $activities[$activity->id] = $activity->name;
}
    ?>
    <x-input-select wire:model.live="activity_id" name="activity_id" label="Select activity"
        placeholder="Choose a activity" :options="$activities" />
    @include('livewire.activity-checkin.status')
</div>
