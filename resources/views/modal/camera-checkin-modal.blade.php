<div class="text-center center">
    @livewire('camera-checkin', ['user' => $user], key($user->id . now()))
    <script src="{{ asset('js/instascan/instascan.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">

        let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
        scanner.addListener('scan', function (content) {
            console.log(content);
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
</div>
