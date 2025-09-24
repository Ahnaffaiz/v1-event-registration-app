<div x-data="{
    checkinStatus: @entangle('checkinStatus'),
}">
    <div class="py-8 mx-auto">
        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-2 md:grid-cols-4">
            <x-information-card icon="heroicon-o-chart-pie" iconColor="text-rose-500" :value="$event->tickets->count() . '/' . $event->capacity" label="Quota" />
            <x-information-card icon="heroicon-o-ticket" iconColor="text-orange-500" :value="$event->tickets->count()"
                label="Event Ticket" />
            <x-information-card icon="heroicon-o-credit-card" iconColor="text-sky-500"
                :value="$activityTicket->where('qr_code', '!=', null)->count() . '/' . $activityTicket->count()"
                label="Activity Ticket" />
            <x-information-progress-card :checkedIn="$checkedIn" :total="$event->tickets->count()" />
        </div>
        <div class="grid items-start justify-center col-auto gap-8 grid-row md:grid-cols-4 sm:grid-cols-2">
            <div class="w-full bg-white rounded-2xl min-h-[400px] md:min-h-[600px] p-4">
                {{-- tab scanner active --}}
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center justify-center flex-1">
                        <div class="flex items-baseline space-x-4">
                            <button id="scannerMenu" wire:click="setActiveTab('scanner')"
                                class="px-3 py-2 text-sm font-medium rounded-2xl hover:bg-gray-100 transition duration-200 {{ $activeTab === 'scanner' ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                                <x-heroicon-o-cpu-chip class="inline-block w-5 h-5 mr-1" />
                                Scanner
                            </button>
                            <button id="cameraMenu" wire:click="setActiveTab('camera')"
                                class="px-3 py-2 text-sm font-medium rounded-2xl hover:bg-gray-100 transition duration-200 {{ $activeTab === 'camera' ? 'bg-gray-900 text-white hover:text-white hover:bg-gray-900' : '' }}">
                                <x-heroicon-o-camera class="inline-block w-5 h-5 mr-1" />
                                Camera
                            </button>
                        </div>
                    </div>
                </div>
                {{-- end tab scanner active --}}
                <div class="flex-grow p-4">
                    <div @if ($activeTab == 'scanner') hidden @endif>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols">
                            <div x-show="!checkinStatus">
                                <h2 class="py-2 text-base font-normal text-slate-800">
                                    {{ $scannerStatus == 'event' ? 'Scanning Ticket Code ...' : 'Pairing Ticket Code ...' }}
                                </h2>
                                <x-square-camera id="preview" qrSize="200" />
                            </div>
                        </div>
                    </div>
                    <div @if ($activeTab == 'camera') hidden @endif>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols">
                                <div x-data="{ barcode: '' }"
                                    @input.debounce.2s="if (barcode.length > 0) { $wire.processCheckin(barcode); barcode = ''; }">
                                    <x-input-text name="name"
                                        placeholder="{{ $scannerStatus == 'event' ? 'Input Ticket Code' : 'Pairing Ticket Code ...' }}"
                                        x-model="barcode" icon="heroicon-s-qr-code" autofocus @class(['mt-2']) />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div x-show="checkinStatus">
                        @include('livewire.partials.admin-status')
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl min-h-[400px] md:min-h-[600px] p-8">
                @if ($participant)
                    <div class="flex items-center">
                        <x-heroicon-o-user class="w-12 h-12 p-2 rounded-full text-sky-500 bg-sky-100" />
                        <div class="ml-3">
                            <h3 class="text-xl font-bold text-gray-800">{{ $participant->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $participant->occupation }}</p>
                        </div>
                    </div>
                    <div class="flex justify-start mt-10 mb-2 ">
                        <h4 class="text-base font-normal">Activity List</h4>
                        @if ($participant->activityTickets->count() > 0 && $participant->activityTickets->first()->qr_code)
                            <button wire:click="resetPairedTicket" class="ml-2 text-sm text-rose-500">
                                <x-heroicon-o-arrow-path class="w-5 h-5 " />
                            </button>
                        @endif
                    </div>
                    @if ($participant->activityTickets->count() > 0)
                        @foreach ($participant->activityTickets as $activityTicket)
                            <div class="flex justify-between">
                                <p class="mr-3 text-sm text-gray-600">{{ $activityTicket->activity->name }}</p>
                                @if ($activityTicket->qr_code)
                                    <x-heroicon-s-check-circle class="w-6 h-6 text-sky-500" />
                                @else
                                    <p class="ml-2 text-sm italic text-orange-500"> Pairing...</p>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="mr-3 text-sm italic text-gray-600">No Activity Found</p>
                    @endif
                @endif
            </div>
            <div class="bg-white rounded-2xl min-h-[400px] md:min-h-[600px] flex flex-col sm:col-span-2">
                {{ $this->table }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/instascan/instascan.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        let scanner;

        function startCamera() {
            scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            scanner.addListener('scan', function (data) {
                Livewire.dispatch('processCheckin', { data: data });
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        }

        function stopCamera() {
            if (scanner) {
                scanner.stop();
                console.log('Camera stopped');
            }
        }

        document.getElementById('cameraMenu').addEventListener('click', function () {
            startCamera();
        });

        document.getElementById('scannerMenu').addEventListener('click', function () {
            stopCamera();
        });

        // Start the camera if the active tab is already set to 'camera'
        document.addEventListener('DOMContentLoaded', function () {
            if ('{{ $activeTab }}' === 'camera') {
                startCamera();
            }
        });
    </script>
@endpush
@script
<script>
    let success = document.getElementById('success-audio');
    let danger = document.getElementById('danger-audio');
    let warning = document.getElementById('warning-audio');

    function resetStatus() {
        setTimeout(() => {
            $wire.checkinStatus = null;
        }, 2500);
    }

    $wire.on('checkin-status', ({ checkinStatus }) => {
        if (checkinStatus === 'success') {
            success.play();
        } else if (checkinStatus === 'failed') {
            danger.play();
        } else if (checkinStatus === 'existing') {
            warning.play();
        } else if (checkinStatus === 'activity not found') {
            danger.play();
        } else if (checkinStatus === 'ticket paired') {
            success.play();
        };
        resetStatus();
    });
</script>
@endscript
