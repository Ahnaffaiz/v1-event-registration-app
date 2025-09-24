<div x-data="{
    checkinStatus: @entangle('checkinStatus'),
}">
    <div class="container px-4 py-8 mx-auto">
        <div class="grid grid-cols-1 gap-6 mb-6 sm:grid-cols-3">
            <x-information-card icon="heroicon-o-chart-pie" iconColor="text-rose-500" :value="$event?->capacity"
                label="Quota" />
            <x-information-card icon="heroicon-o-ticket" iconColor="text-orange-500" :value="$activityTicket->count()"
                label="Ticket Printed" />
            <x-information-progress-card :checkedIn="$checkedIn" :total="$activityTicket->count()" />
        </div>
        <div class="flex flex-col items-start justify-center gap-8 md:flex-row">
            <!-- Left/Top half -->
            <div class="w-full md:w-1/4 bg-white rounded-2xl min-h-[400px] md:min-h-[600px] p-4 flex flex-col">
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
                <div class="flex-grow p-4 pt-0">
                    <div @if ($activeTab == 'scanner') hidden @endif>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols">
                            <div x-show="!checkinStatus">
                                <?php
$activities = [];
foreach ($event?->activities as $activity) {
    $activities[$activity->id] = $activity->name;
}
                                    ?>
                                <x-input-select wire:model.live="activity_id" name="activity_id"
                                    placeholder="Choose a activity" :options="$activities" />
                                <x-square-camera id="preview" qrSize="200" />
                            </div>
                        </div>
                    </div>
                    <div @if ($activeTab == 'camera') hidden @endif>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols">
                            <div x-data="{ barcode: '' }"
                                @input.debounce.2s="if (barcode.length > 0) { $wire.processCheckin(barcode); barcode = ''; }">
                                <x-input-text name="name" placeholder="Input Ticket Code" x-model="barcode"
                                    icon="heroicon-s-qr-code" autofocus @class(['mt-2']) />
                            </div>
                            <?php
$activities = [];
foreach ($event?->activities as $activity) {
    $activities[$activity->id] = $activity->name;
}
                                    ?>
                            <x-input-select wire:model.live="activity_id" name="activity_id"
                                placeholder="Choose a activity" :options="$activities" />
                        </div>
                    </div>
                    <div x-show="checkinStatus">
                        @include('livewire.partials.admin-status')
                    </div>
                </div>
            </div>
            <!-- Right/Bottom half -->
            <div class="w-full md:w-2/4 bg-white rounded-2xl min-h-[400px] md:min-h-[600px] flex flex-col">
                {{ $this->table }}
            </div>
            <div class="w-full space-y-2 md:w-1/4">
                @foreach ($event?->activities as $activity)
                    <a target="_blank" href="{{ route('display-activity-checkin', $activity->id) }}"
                        class="flex items-center justify-center p-6 font-normal text-center text-white transition duration-300 ease-in-out text-md bg-slate-600 rounded-2xl hover:bg-slate-500 t">
                        <x-fluentui-arrow-forward-16-o class="inline-block w-5 h-5 mr-2" />
                        Open {{ $activity->name }} Check in</a>
                @endforeach
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
        };
        resetStatus();
    });
</script>
@endscript
