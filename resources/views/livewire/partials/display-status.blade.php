<audio id="success-audio" src="{{ asset('sound/success.mp3') }}"></audio>
<audio id="danger-audio" src="{{ asset('sound/danger.mp3') }}"></audio>
<audio id="warning-audio" src="{{ asset('sound/warning.mp3') }}"></audio>

@if ($checkinStatus == 'success')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.success')
        <div class="mt-10">
            <h2 class="mb-4 font-serif text-4xl text-emerald-100">Welcome, {{ $participant?->name }}</h2>
            <span class="font-serif text-2xl text-emerald-500">Check In Successfully</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'failed')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.danger')
        <h2 class="mt-10 font-serif text-4xl text-rose-600">Ticket Not Found</h2>
    </div>
@endif

@if ($checkinStatus == 'existing')
    <div class="flex flex-col items-center p-4 mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.warning')
        <div class="mt-10">
            <h2 class="mb-4 font-serif text-4xl text-emerald-100">Hello, {{ $participant?->name }}</h2>
            <span class="font-serif text-2xl text-orange-400">You're Already Check In</span>
        </div>
    </div>
@endif
