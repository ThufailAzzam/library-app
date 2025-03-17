<div>
    <div class="space-y-4">
        <div class="flex items-center space-x-2">
            <input 
                type="text" 
                wire:model.defer="isbn" 
                placeholder="Scan or type ISBN"
                class="block w-full rounded-md border-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-white bg-black placeholder-gray-400"
            />
            <button 
                wire:click="fetchBookData" 
                type="button"
                class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ $loading ? 'Loading...' : 'Search' }}
            </button>
            <button 
                id="openScannerBtn"
                type="button"
                class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2 6a2 2 0 012-2h5a1 1 0 010 2H4v12h12v-5a1 1 0 112 0v5a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                    <path d="M16 0a1 1 0 011 1v5h-2V2h-5V0h6zM2 14v-5h2v5H2zm16 0v-5h2v5h-2z" />
                </svg>
                Scan Barcode
            </button>
        </div>

        <div id="scanner-container" class="hidden">
            <div id="scanner-viewport" class="relative w-full h-80">
                <video id="scanner-video" class="w-full h-full object-cover"></video>
                <div id="scanner-overlay" class="absolute inset-0 flex items-center justify-center">
                    <div class="border-4 border-green-500 w-4/5 h-1/3 rounded-lg opacity-70 bg-transparent"></div>
                </div>
                <div id="scanner-status" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 text-center">
                    Position barcode within the green area
                </div>
            </div>
            <div class="mt-4 flex justify-between">
                <button 
                    id="captureImageBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-purple-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    Capture Image
                </button>
                <button 
                    id="toggleFlashBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-yellow-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z" />
                    </svg>
                    Toggle Flash
                </button>
                <button 
                    id="switchCameraBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                    </svg>
                    Switch Camera
                </button>
                <button 
                    id="closeScannerBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Close Scanner
                </button>
            </div>
        </div>
        
        <!-- Captured image preview container -->
        <div id="captured-image-container" class="hidden mt-4">
            <div class="relative w-full max-h-80 overflow-hidden border-2 border-dashed border-gray-300 p-2 rounded-md">
                <canvas id="captured-canvas" class="max-w-full max-h-80 mx-auto"></canvas>
                <div class="absolute top-2 right-2">
                    <button 
                        id="closeCapturedImageBtn"
                        type="button"
                        class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-red-600 text-white hover:bg-red-700 focus:outline-none"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div id="captured-scanner-status" class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-2 text-center">
                    Analyzing image...
                </div>
            </div>
            <div class="mt-4 flex justify-between">
                <button 
                    id="retakeCaptureBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Take Another Photo
                </button>
                <button 
                    id="processCaptureBtn"
                    type="button"
                    class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                >
                    Process Image
                </button>
            </div>
        </div>
        
        <div id="debug-output" class="text-sm bg-black text-white p-2 rounded-md mt-2 mb-2"></div>

        @if($loading)
            <div class="text-gray-500">Searching...</div>
        @endif

        @if($error)
            <div class="text-red-500">{{ $error }}</div>
        @endif

        @if($bookData)
            <div class="bg-black-50 p-4 rounded-md">
                <h3 class="text-lg font-medium">{{ $bookData['title'] ?? 'Unknown Title' }}</h3>
                
                @if(isset($bookData['authors']))
                    <p><strong>Authors:</strong> {{ collect($bookData['authors'])->pluck('name')->join(', ') }}</p>
                @endif
                
                @if(isset($bookData['publishers']))
                    <p><strong>Publisher:</strong> {{ collect($bookData['publishers'])->pluck('name')->join(', ') }}</p>
                @endif
                
                @if(isset($bookData['publish_date']))
                    <p><strong>Published:</strong> {{ $bookData['publish_date'] }}</p>
                @endif
                
                <button 
                type="button" 
                class="mt-2 inline-flex items-center rounded-md border border-solid bg-teal-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:ring-offset-2"
                wire:click="$emit('useBookData')"
                >
                Use This Book
            </button>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Camera scanner functionality
    const scannerContainer = document.getElementById('scanner-container');
    const capturedImageContainer = document.getElementById('captured-image-container');
    const openScannerBtn = document.getElementById('openScannerBtn');
    const closeScannerBtn = document.getElementById('closeScannerBtn');
    const toggleFlashBtn = document.getElementById('toggleFlashBtn');
    const switchCameraBtn = document.getElementById('switchCameraBtn');
    const captureImageBtn = document.getElementById('captureImageBtn');
    const closeCapturedImageBtn = document.getElementById('closeCapturedImageBtn');
    const retakeCaptureBtn = document.getElementById('retakeCaptureBtn');
    const processCaptureBtn = document.getElementById('processCaptureBtn');
    const video = document.getElementById('scanner-video');
    const capturedCanvas = document.getElementById('captured-canvas');
    const statusEl = document.getElementById('scanner-status');
    const capturedStatusEl = document.getElementById('captured-scanner-status');
    const debugOutput = document.getElementById('debug-output');
    
    let scanning = false;
    let codeReader = null;
    let selectedDeviceId = null;
    let availableDevices = [];
    let currentDeviceIndex = 0;
    let track = null;
    
    // Debug logging function
    function debug(message) {
        if (debugOutput) {
            debugOutput.textContent = message;
            console.log(message);
        }
    }

    // Load ZXing library
    function loadZXingLibrary() {
        return new Promise((resolve, reject) => {
            if (window.ZXing) {
                resolve(window.ZXing);
                return;
            }
            debug("Loading ZXing library...");
            const script = document.createElement('script');
            script.src = 'https://unpkg.com/@zxing/library@latest/umd/index.min.js';
            script.async = true;
            script.onload = () => {
                debug("ZXing library loaded");
                resolve(window.ZXing);
            };
            script.onerror = (e) => {
                debug("Error loading ZXing library: " + e);
                reject(e);
            };
            document.head.appendChild(script);
        });
    }

    // Initialize ZXing
    async function initZXing() {
        try {
            debug("Initializing ZXing...");
            const ZXing = await loadZXingLibrary();
            
            // Create hint configuration focused on ISBN (EAN-13) format
            const hints = new Map();
            const formats = [
                ZXing.BarcodeFormat.EAN_13,
                ZXing.BarcodeFormat.EAN_8,
                ZXing.BarcodeFormat.UPC_A,
                ZXing.BarcodeFormat.UPC_E,
                ZXing.BarcodeFormat.ISBN
            ];
            hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, formats);
            hints.set(ZXing.DecodeHintType.TRY_HARDER, true);
            
            // Create reader with hints
            codeReader = new ZXing.BrowserMultiFormatReader(hints);
            debug("Reader created successfully");
            
            // List available devices
            availableDevices = await codeReader.listVideoInputDevices();
            debug(`Found ${availableDevices.length} video devices`);
            
            if (availableDevices.length === 0) {
                debug("No cameras found");
                return false;
            }
            
            // Find back camera
            let backCameraIndex = -1;
            for (let i = 0; i < availableDevices.length; i++) {
                const device = availableDevices[i];
                debug(`Device ${i}: ${device.deviceId} - ${device.label}`);
                if (/back|rear|environment/i.test(device.label)) {
                    backCameraIndex = i;
                    break;
                }
            }
            
            // Set selected device to back camera or first available
            currentDeviceIndex = backCameraIndex >= 0 ? backCameraIndex : 0;
            selectedDeviceId = availableDevices[currentDeviceIndex].deviceId;
            debug(`Selected camera: ${availableDevices[currentDeviceIndex].label}`);
            
            return true;
        } catch (error) {
            debug('Error initializing ZXing: ' + error.message);
            console.error('Error initializing ZXing:', error);
            return false;
        }
    }

    // Start scanner
    async function startScanner() {
        if (!codeReader) {
            const initialized = await initZXing();
            if (!initialized) return;
        }
        
        try {
            // Make sure we completely reset before starting again
            if (scanning) {
                await stopScanner(false);
                // Add a small delay to ensure proper cleanup
                await new Promise(resolve => setTimeout(resolve, 500));
            }
            
            scanning = true;
            statusEl.textContent = "Accessing camera...";
            
            debug(`Starting scanner with device ${selectedDeviceId}`);
            
            const constraints = {
                video: {
                    deviceId: selectedDeviceId ? { exact: selectedDeviceId } : undefined,
                    facingMode: "environment",
                    width: { min: 640, ideal: 1280, max: 1920 },
                    height: { min: 480, ideal: 720, max: 1080 },
                    aspectRatio: 4/3
                }
            };
            
            // Start live video preview (we'll use this for capturing images)
            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    video.srcObject = stream;
                    video.play();
                    
                    // Get the video track to control flash
                    track = stream.getVideoTracks()[0];
                    debug("Video track obtained for flash control");
                    
                    // Update flash button visibility based on flash capability
                    if (track && track.getCapabilities) {
                        const capabilities = track.getCapabilities();
                        toggleFlashBtn.style.display = capabilities.torch ? 'block' : 'none';
                        debug("Torch capability: " + (capabilities.torch ? "Available" : "Not available"));
                    } else {
                        toggleFlashBtn.style.display = 'none';
                        debug("Track capabilities not accessible");
                    }
                    
                    statusEl.textContent = "Camera ready. Position barcode in frame and tap Capture.";
                })
                .catch(err => {
                    debug(`Error accessing camera: ${err.message}`);
                    statusEl.textContent = "Failed to access camera";
                    scanning = false;
                });
            
        } catch (error) {
            debug(`Error starting scanner: ${error.message}`);
            statusEl.textContent = "Failed to start camera";
            console.error('Error starting scanner:', error);
            scanning = false;
        }
    }

    // Stop scanning
    function stopScanner(hideContainer = true) {
        debug("Stop scanner called - scanning status: " + scanning);
        if (scanning) {
            debug("Stopping scanner");
            try {
                // Stop any ongoing video stream
                if (video.srcObject) {
                    const tracks = video.srcObject.getTracks();
                    tracks.forEach(track => {
                        track.stop();
                    });
                    video.srcObject = null;
                }
                
                if (track) {
                    track.stop();
                    track = null;
                }
                
            } catch (e) {
                debug("Error during stream cleanup: " + e.message);
            }
            
            scanning = false;
            
            if (hideContainer) {
                scannerContainer.classList.add('hidden');
            }
            
            // Reset overlay color
            const overlay = document.getElementById('scanner-overlay').firstElementChild;
            if (overlay) {
                overlay.classList.remove('border-blue-500');
                overlay.classList.add('border-green-500');
            }
        }
    }
    
    // Toggle flash/torch
    function toggleFlash() {
        if (track && track.getCapabilities && track.getCapabilities().torch) {
            const currentTorchState = track.getConstraints().advanced?.[0]?.torch || false;
            debug("Current torch state: " + currentTorchState);
            
            track.applyConstraints({
                advanced: [{ torch: !currentTorchState }]
            }).then(() => {
                debug("Flash toggled to: " + !currentTorchState);
            }).catch(err => {
                debug(`Error toggling flash: ${err.message}`);
            });
        } else {
            debug("Flash not supported on this device");
        }
    }
    
    // Switch camera
    function switchCamera() {
        if (availableDevices.length <= 1) {
            debug("No other cameras available");
            return;
        }
        
        currentDeviceIndex = (currentDeviceIndex + 1) % availableDevices.length;
        selectedDeviceId = availableDevices[currentDeviceIndex].deviceId;
        debug(`Switching to camera: ${availableDevices[currentDeviceIndex].label}`);
        startScanner();
    }

    // Capture still image from video stream
    function captureImage() {
        if (!video.srcObject || !video.videoWidth) {
            debug("No video stream available to capture");
            return;
        }
        
        try {
            debug("Capturing image from video stream");
            
            // Setup canvas to match video dimensions
            const ctx = capturedCanvas.getContext('2d');
            capturedCanvas.width = video.videoWidth;
            capturedCanvas.height = video.videoHeight;
            
            // Draw current video frame to canvas
            ctx.drawImage(video, 0, 0, capturedCanvas.width, capturedCanvas.height);
            
            // Hide scanner and show captured image
            scannerContainer.classList.add('hidden');
            capturedImageContainer.classList.remove('hidden');
            capturedStatusEl.textContent = "Ready to process image";
            
            // Stop the video stream to save battery and resources
            stopScanner(false);
            
            debug("Image captured successfully");
        } catch (error) {
            debug(`Error capturing image: ${error.message}`);
        }
    }

    // Process captured image with ZXing
    async function processImage() {
        if (!codeReader) {
            const initialized = await initZXing();
            if (!initialized) return;
        }
        
        try {
            capturedStatusEl.textContent = "Processing image...";
            debug("Processing captured image");
            
            // Convert canvas to image data URL
            const imageUrl = capturedCanvas.toDataURL('image/png');
            
            // Create a new HTML Image element
            const img = new Image();
            
            // Set up promise to handle image loading
            const imageLoaded = new Promise((resolve, reject) => {
                img.onload = () => resolve(img);
                img.onerror = () => reject(new Error("Failed to load image"));
            });
            
            // Set the image source to our canvas data
            img.src = imageUrl;
            
            // Wait for image to load
            const loadedImg = await imageLoaded;
            
            // Create a bitmap source from the image
            const canvas = document.createElement('canvas');
            canvas.width = loadedImg.width;
            canvas.height = loadedImg.height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(loadedImg, 0, 0);
            
            // Get image data for ZXing to process
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            
            // Create ZXing BinaryBitmap from image data
            const ZXing = await loadZXingLibrary();
            const luminanceSource = new ZXing.HTMLCanvasElementLuminanceSource(canvas);
            const binaryBitmap = new ZXing.BinaryBitmap(new ZXing.HybridBinarizer(luminanceSource));
            
            // Try to decode the barcode
            try {
                const result = codeReader.decodeBitmap(binaryBitmap);
                if (result) {
                    const code = result.getText();
                    debug(`Barcode detected: ${code}`);
                    capturedStatusEl.textContent = `Detected: ${code}`;
                    
                    // Set the ISBN input value
                    const isbnInput = document.querySelector('[wire\\:model\\.defer="isbn"]');
                    if (isbnInput) {
                        isbnInput.value = code;
                        isbnInput.dispatchEvent(new Event('input', { bubbles: true }));
                        
                        // Find and click the search button
                        const searchButton = document.querySelector('[wire\\:click="fetchBookData"]');
                        if (searchButton) {
                            searchButton.click();
                        }
                    }
                    
                    // Close the captured image view after a second
                    setTimeout(() => {
                        capturedImageContainer.classList.add('hidden');
                    }, 1000);
                }
            } catch (error) {
                if (error instanceof ZXing.NotFoundException) {
                    debug("No barcode found in image");
                    capturedStatusEl.textContent = "No barcode found. Try another image.";
                } else {
                    debug(`Error decoding barcode: ${error.message}`);
                    capturedStatusEl.textContent = "Error processing image";
                }
            }
            
        } catch (error) {
            debug(`Error processing image: ${error.message}`);
            capturedStatusEl.textContent = "Error processing image";
        }
    }

    // Open scanner button click handler
    if (openScannerBtn) {
        openScannerBtn.addEventListener('click', function() {
            debug("Open scanner button clicked");
            // Hide captured image container if it's visible
            capturedImageContainer.classList.add('hidden');
            // Show scanner container
            scannerContainer.classList.remove('hidden');
            startScanner();
        });
    }
    
    // Close scanner button click handler
    if (closeScannerBtn) {
        closeScannerBtn.addEventListener('click', function() {
            debug("Close scanner button clicked");
            stopScanner(true);
        });
    }
    
    // Toggle flash button click handler
    if (toggleFlashBtn) {
        toggleFlashBtn.addEventListener('click', function() {
            debug("Toggle flash button clicked");
            toggleFlash();
        });
    }
    
    // Switch camera button click handler
    if (switchCameraBtn) {
        switchCameraBtn.addEventListener('click', function() {
            debug("Switch camera button clicked");
            switchCamera();
        });
    }
    
    // Capture image button click handler
    if (captureImageBtn) {
        captureImageBtn.addEventListener('click', function() {
            debug("Capture image button clicked");
            captureImage();
        });
    }
    
    // Close captured image button click handler
    if (closeCapturedImageBtn) {
        closeCapturedImageBtn.addEventListener('click', function() {
            debug("Close captured image button clicked");
            capturedImageContainer.classList.add('hidden');
        });
    }
    
    // Retake capture button click handler
    if (retakeCaptureBtn) {
        retakeCaptureBtn.addEventListener('click', function() {
            debug("Retake capture button clicked");
            capturedImageContainer.classList.add('hidden');
            scannerContainer.classList.remove('hidden');
            startScanner();
        });
    }
    
    // Process capture button click handler
    if (processCaptureBtn) {
        processCaptureBtn.addEventListener('click', function() {
            debug("Process capture button clicked");
            processImage();
        });
    }

    // Handle manual entry by keyboard
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
            debug(`Keyboard scanner detected: ${barcodeBuffer}`);
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
});
    </script>
</div>