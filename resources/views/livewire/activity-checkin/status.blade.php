@if ($checkinStatus == 'success')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <x-heroicon-s-check-circle class="text-center text-emerald-500 w-52 h-52" />
        <div>
            <h2 class="text-2xl font-medium">{{ $participant?->name }}</h2>
            <span class="text-lg font-normal">Check In Successfully</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'failed')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <x-heroicon-c-x-circle class="mb-5 text-center w-52 h-52 text-rose-500" />
        <div>
            <span class="text-lg font-normal">Ticket Not Found</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'existing')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <x-heroicon-s-information-circle class="mb-5 text-center text-orange-500 w-52 h-52" />
        <div>
            <h2 class="text-2xl font-medium">{{ $participant?->name }}</h2>
            <span class="text-lg font-normal">You're Already in</span>
        </div>
    </div>
@endif
