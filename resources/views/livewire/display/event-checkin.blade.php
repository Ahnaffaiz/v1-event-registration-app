<div x-data="{
        barcode: '',
        checkinStatus: @entangle('checkinStatus'),
    }" @input.debounce.2s="
        if (barcode.length > 0) {
            $wire.processCheckin(barcode);
            barcode = '';
        }
    " class="flex flex-col items-center justify-center min-h-screen p-4 text-center min-w-screen"
    style="background-image: url({{ asset('images/background.jpg') }}); background-size: cover;">

    <h1 class="font-serif text-5xl text-emerald-500">{{ $event->name }}</h1>
    <input name="barcode" x-model="barcode" id="input-scanner" placeholder="Scan Your QR Code to Register"
        class="w-full mt-6 font-serif text-3xl text-center text-transparent bg-transparent border-none focus:ring-0 focus:placeholder-white placeholder-emerald-100" />
    <div class="mt-10 h-96">
        <div x-show="checkinStatus">
            @include('livewire.partials.display-status')
        </div>
        <div x-show="!checkinStatus" class="mt-20">
            <div @if ($activeTab === 'scanner') hidden @endif>
                <div class="h-[300px] w-[300px]">
                    <x-square-camera id="preview" qrSize="200" />
                </div>
            </div>
            <div @if ($activeTab === 'camera') hidden @endif>
                @include('livewire.partials.scan-icon')
            </div>
        </div>
    </div>
    <div class="absolute space-x-2 bottom-3 left-3">
        <button id="fullscreen-button" class="text-white bg-transparent rounded " onclick="getFullscreen()">
            <span id="fullscreen-icon-maximize" class="block">
                <x-fluentui-full-screen-maximize-20 class="w-10 h-10 text-white rounded-lg bg-white/10" />
            </span>
            <span id="fullscreen-icon-minimize" class="hidden">
                <x-fluentui-full-screen-minimize-20 class="w-10 h-10 text-white rounded-lg bg-white/10" />
            </span>
        </button>
        <button id="fullscreen-button" class="text-white bg-transparent rounded " wire:click={{ $activeTab == 'scanner' ? "setActiveTab('camera')" : "setActiveTab('scanner')" }}>
            @if ($activeTab == 'scanner')
                <x-heroicon-o-camera class="w-10 h-10 text-white rounded-lg bg-white/10" />
            @else
                <x-heroicon-o-qr-code class="w-10 h-10 text-white rounded-lg bg-white/10" />
            @endif
        </button>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/instascan/instascan.min.js') }}" type="text/javascript"></script>
    {{-- camera scanner --}}
    <script type="text/javascript">
        let camera;

        function startCamera() {
            camera = new Instascan.Scanner({ video: document.getElementById('preview') });
            camera.addListener('scan', function (data) {
                Livewire.dispatch('processCheckin', { data: data });
            });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    camera.start(cameras[0]);
                } else {
                    console.error('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        }

        function stopCamera() {
            if (camera) {
                camera.stop();
                console.log('Camera stopped');
            }
        }

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

    //start and stop camera
    $wire.on('tabChanged', ({ tabStatus }) => {
        if (tabStatus === 'camera') {
            startCamera();
        } else {
            let inputScanner = document.getElementById('input-scanner');
            inputScanner.focus();
            stopCamera();
        }
    });
</script>
@endscript
