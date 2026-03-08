document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const uploadPreview = document.getElementById('upload-preview');
    const overlay = document.getElementById('overlay');
    const ctx = overlay.getContext('2d');
    const startBtn = document.getElementById('start-scan');
    const stopBtn = document.getElementById('stop-scan');
    const uploadBtn = document.getElementById('upload-btn');
    const fileInput = document.getElementById('file-input');
    const loadingOverlay = document.getElementById('loading-overlay');
    const resultCard = document.getElementById('result-card');
    const noResult = document.getElementById('no-result');
    const statusText = document.getElementById('status-text');
    const scanStatus = document.getElementById('scan-status');

    let scanInterval = null;
    let isScanning = false;
    let currentImageDimensions = { width: 0, height: 0 };

    function resizeCanvas() {
        overlay.width = video.clientWidth || uploadPreview.clientWidth;
        overlay.height = video.clientHeight || uploadPreview.clientHeight;
    }

    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } } 
            });
            video.srcObject = stream;
            video.onloadedmetadata = () => {
                video.play();
                video.classList.remove('hidden');
                uploadPreview.classList.add('hidden');
                resizeCanvas();
            };
        } catch (err) {
            console.error('Camera Error:', err);
            statusText.innerText = 'Camera Off';
        }
    }

    async function captureAndScan() {
        if (!isScanning) return;

        const captureCanvas = document.createElement('canvas');
        captureCanvas.width = video.videoWidth;
        captureCanvas.height = video.videoHeight;
        const captureCtx = captureCanvas.getContext('2d');
        captureCtx.drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);

        currentImageDimensions = { width: video.videoWidth, height: video.videoHeight };
        const imageData = captureCanvas.toDataURL('image/jpeg', 0.8);
        await sendImageToBackend(imageData);
    }

    async function sendImageToBackend(imageData) {
        statusText.innerText = 'Processing...';
        scanStatus.classList.replace('bg-red-500', 'bg-emerald-500');
        loadingOverlay.classList.remove('hidden');

        try {
            const response = await fetch('/api/anpr/scan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ image: imageData })
            });

            const result = await response.json();

            if (result.success) {
                updateUI(result);
                drawBoundingBox(result.box);
            } else {
                if (result.box) drawBoundingBox(result.box, 'red');
                alert(result.error || 'Gagal mengenali plat nomor');
            }
        } catch (err) {
            console.error('Scan Request Error:', err);
            alert('Terjadi kesalahan saat menghubungi server.');
        } finally {
            loadingOverlay.classList.add('hidden');
            statusText.innerText = isScanning ? 'Active' : 'Idle';
            if (!isScanning) scanStatus.classList.replace('bg-emerald-500', 'bg-red-500');
        }
    }

    function drawBoundingBox(box, color = '#10b981') {
        if (!box || !currentImageDimensions.width) return;
        
        ctx.clearRect(0, 0, overlay.width, overlay.height);
        
        const scaleX = overlay.width / currentImageDimensions.width;
        const scaleY = overlay.height / currentImageDimensions.height;

        ctx.strokeStyle = color;
        ctx.lineWidth = 4;
        ctx.strokeRect(
            box.xmin * scaleX, 
            box.ymin * scaleY, 
            (box.xmax - box.xmin) * scaleX, 
            (box.ymax - box.ymin) * scaleY
        );

        setTimeout(() => ctx.clearRect(0, 0, overlay.width, overlay.height), 2500);
    }

    function updateUI(data) {
        noResult.classList.add('hidden');
        resultCard.classList.remove('hidden');

        // Update Scanner Overlay
        const scannerInfo = document.getElementById('scanner-vehicle-info');
        document.getElementById('scanner-plate').innerText = data.plate;
        document.getElementById('scanner-vehicle').innerText = `${data.vehicle.type} | ${data.vehicle.color}`.toUpperCase();
        scannerInfo.classList.remove('hidden');
        setTimeout(() => scannerInfo.classList.add('hidden'), 5000);

        // Update Result Sidebar
        document.getElementById('result-plate').innerText = data.plate;
        document.getElementById('result-confidence').innerText = (data.confidence * 100).toFixed(1) + '%';
        document.getElementById('result-action').innerText = data.action === 'entry' ? 'Masuk' : 'Keluar';
        
        const vehicleInfo = `${data.vehicle.type} | ${data.vehicle.color}`;
        document.getElementById('result-vehicle').innerText = vehicleInfo.toUpperCase();

        document.getElementById('result-time').innerText = new Date().toLocaleTimeString();
        document.getElementById('result-status').innerText = data.action === 'entry' ? 'Success Entry' : 'Success Exit';
        document.getElementById('result-image').src = data.image_url;

        if (navigator.vibrate) navigator.vibrate(100);
    }

    function startScan() {
        isScanning = true;
        startBtn.classList.add('hidden');
        stopBtn.classList.remove('hidden');
        video.classList.remove('hidden');
        uploadPreview.classList.add('hidden');
        scanInterval = setInterval(captureAndScan, 2000);
        statusText.innerText = 'Active';
        scanStatus.classList.replace('bg-red-500', 'bg-emerald-500');
    }

    function stopScan() {
        isScanning = false;
        clearInterval(scanInterval);
        startBtn.classList.remove('hidden');
        stopBtn.classList.add('hidden');
        statusText.innerText = 'Idle';
        scanStatus.classList.replace('bg-emerald-500', 'bg-red-500');
        ctx.clearRect(0, 0, overlay.width, overlay.height);
    }

    uploadBtn.addEventListener('click', () => {
        stopScan();
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = async function(event) {
            const base64Image = event.target.result;
            
            // Show preview in main scanner area
            uploadPreview.src = base64Image;
            uploadPreview.classList.remove('hidden');
            video.classList.add('hidden');
            
            // Get dimensions of the uploaded image for bounding box
            const img = new Image();
            img.onload = async function() {
                currentImageDimensions = { width: img.width, height: img.height };
                resizeCanvas();
                await sendImageToBackend(base64Image);
            };
            img.src = base64Image;
        };
        reader.readAsDataURL(file);
        this.value = '';
    });

    startBtn.addEventListener('click', startScan);
    stopBtn.addEventListener('click', stopScan);
    window.addEventListener('resize', resizeCanvas);

    startCamera();
});
