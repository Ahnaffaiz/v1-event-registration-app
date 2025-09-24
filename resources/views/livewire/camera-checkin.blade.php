<div>
    <div class="h-[300px] w-[300px]">
        <x-square-camera id="preview" />
    </div>
    @include('livewire.partials.display-status')
</div>
@push('scripts')
    <script src="{{ asset('js/instascan/instascan.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
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
    </script>
@endpush
