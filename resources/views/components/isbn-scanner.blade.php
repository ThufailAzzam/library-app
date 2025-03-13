<head><script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script></head>

<div>
    <input
        type="text"
        id="{{ $getId() }}"
        name="{{ $getName() }}"
        value="{{ $getState() }}"
        {{ $attributes->merge($getExtraAttributes()) }}
        placeholder="Masukkan Kode Buku atau Scan ISBN..."
    />
    <div id="qr-reader" style="width: 300px; margin-top: 10px;"></div>
    <button type="button" id="start-scan" style="margin-top: 10px;">Mulai Scan</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isbnInput = document.getElementById('{{ $getId() }}');
        const startScanButton = document.getElementById('start-scan');
        let qrScanner;

        startScanButton.addEventListener('click', function () {
            if (!qrScanner) {
                qrScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });

                qrScanner.render((decodedText) => {
                    isbnInput.value = decodedText;
                    qrScanner.clear();
                    qrScanner = null;
                });
            }
        });
    });
</script>