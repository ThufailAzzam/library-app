// resources/js/barcode-scanner.js
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a page with the ISBN scanner
    if (document.querySelector('[wire\\:model\\.defer="isbn"]')) {
        // Add an event listener for keydown events
        let barcodeBuffer = '';
        let lastKeyTime = 0;
        const BARCODE_DELAY = 50; // Typical barcode scanners send characters with little delay

        document.addEventListener('keydown', function(e) {
            // Most barcode scanners send a "Enter" key as the last character
            const currentTime = new Date().getTime();
            
            // If the delay between keys is long, it's probably manual typing, so reset buffer
            if (currentTime - lastKeyTime > BARCODE_DELAY && barcodeBuffer.length > 0) {
                barcodeBuffer = '';
            }
            
            lastKeyTime = currentTime;
            
            // If it's an Enter key and we have data in the buffer
            if (e.key === 'Enter' && barcodeBuffer.length > 0) {
                const isbnInput = document.querySelector('[wire\\:model\\.defer="isbn"]');
                if (isbnInput) {
                    isbnInput.value = barcodeBuffer;
                    isbnInput.dispatchEvent(new Event('input', { bubbles: true }));
                    // Find and click the search button
                    const searchButton = document.querySelector('[wire\\:click="fetchBookData"]');
                    if (searchButton) {
                        searchButton.click();
                    }
                }
                barcodeBuffer = '';
                e.preventDefault();
            } 
            // Only add to buffer if it's a valid ISBN character (digits or X)
            else if (/[\dX]/.test(e.key)) {
                barcodeBuffer += e.key;
            }
        });
    }
});