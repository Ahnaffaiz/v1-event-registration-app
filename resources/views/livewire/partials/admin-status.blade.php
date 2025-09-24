<audio id="success-audio" src="{{ asset('sound/success.mp3') }}"></audio>
<audio id="danger-audio" src="{{ asset('sound/danger.mp3') }}"></audio>
<audio id="warning-audio" src="{{ asset('sound/warning.mp3') }}"></audio>

@if ($checkinStatus == 'success')
    <div class="flex flex-col items-center mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.success')
        <div class="mt-5">
            <h2 class="mb-2 font-serif text-xl text-slate-950">Welcome, {{ $participant?->name }}</h2>
            <span class="font-serif text-lg text-emerald-500">Check In Successfully</span>
        </div>
    </div>
@endif

@if ($checkinStatus == 'failed')
    <div class="flex flex-col items-center mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.danger')
        <h2 class="mt-5 font-serif text-xl text-rose-600">Ticket Not Found</h2>
    </div>
@endif

@if ($checkinStatus == 'existing')
    <div class="flex flex-col items-center mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.warning')
        <div class="mt-5">
            <h2 class="mb-2 font-serif text-xl text-slate-950">Hello, {{ $participant?->name }}</h2>
            <span class="font-serif text-lg text-orange-400">You're Already Check In</span>
        </div>
    </div>
@endif

{{-- activity generate ticket --}}
@if ($checkinStatus == 'ticket paired')
    <div class="flex flex-col items-center mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.success')
        <div class="mt-5">
            <h2 class="mb-2 font-serif text-xl text-emerald-500">Ticket Successfully Paired</h2>
        </div>
    </div>
@endif

@if ($checkinStatus == 'activity not found')
    <div class="flex flex-col items-center mb-4 text-center rounded-lg text-md">
        @include('livewire.partials.danger')
        <h2 class="mt-5 font-serif text-xl text-rose-600">Activity Not Found</h2>
    </div>
@endif
