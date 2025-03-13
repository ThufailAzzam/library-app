import './bootstrap';
import { Html5QrcodeScanner } from 'html5-qrcode';

// Your custom JavaScript code can go here
document.addEventListener('DOMContentLoaded', function () {
    const qrScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });

    qrScanner.render((decodedText) => {
        // Handle the scanned text
        const isbnInput = document.getElementById('isbn-scanner');
        if (isbnInput) {
            isbnInput.value = decodedText;
        }
        qrScanner.clear();
    });
});