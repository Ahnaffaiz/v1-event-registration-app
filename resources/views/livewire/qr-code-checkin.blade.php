<div x-data="{ barcode: '' }" @input.debounce.1s="if (barcode.length > 0) { $wire.processCheckin(barcode); barcode = ''; }">
    <h2>Scan Barcode/QRCode</h2>
    <input type="text" x-model="barcode" autofocus />
    <div>
        <h3>Hasil Scan: <span x-text="barcode"></span></h3>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
</div>
