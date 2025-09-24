<div>
    <div class="container px-4 py-8 mx-auto">
        <div class="flex flex-col space-y-6 md:flex-row md:items-start md:space-x-6 md:space-y-0">
            <div class="w-full md:w-1/2">
                <x-square-camera id="preview" />
                <?php
$activities = [];
foreach ($event?->activities as $activity) {
    $activities[$activity->id] = $activity->name;
}
                    ?>
                <x-input-select wire:model.live="activity_id" name="activity_id" label="Select activity"
                    placeholder="Choose a activity" :options="$activities" />
            </div>
            <div class="w-full md:w-1/2">
                @include('livewire.activity-checkin.status')
            </div>
        </div>
    </div>

</div>
