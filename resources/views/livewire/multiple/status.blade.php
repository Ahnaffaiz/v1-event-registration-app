@if ($checkinStatus == 'success')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <img src="{{ asset('images/success.png') }}" alt="" class="mb-2 w-52 h-52">
        <div>
            <h2 class="text-2xl font-medium">{{ $participant?->name }}</h2>
            <span class="text-lg font-normal">Check In Successfully</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'failed')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <img src="{{ asset('images/failed.png') }}" alt="" class="mb-2 w-52 h-52">
        <div>
            <span class="text-lg font-normal">Ticket Not Found</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'existing')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        <img src="{{ asset('images/warning.png') }}" alt="" class="mb-2 w-52 h-52">
        <div>
            <h2 class="text-2xl font-medium">{{ $participant?->name }}</h2>
            <span class="text-lg font-normal">You're Already Check In</span>
        </div>
    </div>
@endif
