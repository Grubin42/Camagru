<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/create-post.css">
</head>
   
   <title>Front Camera Stream</title>

<div id="camera-container">
    <video id="camera" autoplay></video>
    <canvas id="canvas" style="display: none;"></canvas>
    <img id="selected-image" src="" alt="Selected Overlay" style="display: none;">
</div>

<img id="photo" src="" alt="Captured Photo" style="display: none;">

<div id="controls">
    <button id="capture-button"  onclick="capturePhoto()">Capture Photo</button>
</div>

<canvas id="canvas" style="display: none;"></canvas>
<img id="photo" src="" alt="Captured Photo">


<div id="image-select">
    <h3>Select an Overlay Image:</h3>
    <!-- Example transparent images -->
    <img src="/Presentation/Assets/img/blue-waves.png" onclick="selectOverlay(this.src)" alt="Overlay 1">
    <!-- Add more images as needed -->
</div>

<script>

    let selectedOverlay = '';

    async function startCamera() {
        try {
            const constraints = {
    video: {
        facingMode: "user"
        }
        };
            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            const videoElement = document.getElementById('camera');
            videoElement.srcObject = stream;
        } catch (error) {
            console.error('Error accessing the camera', error);
            alert('Could not access the camera. Please check your device permissions.');
        }
    }

    function selectOverlay(src) {

        console.log('Selected overlay:', src);

        selectedOverlay = src;
        const overlayImage = document.getElementById('selected-image');

        // TODO: voir que c'est selectionné
        overlayImage.style.border = '2px solid red';
        overlayImage.src = selectedOverlay;
        overlayImage.style.display = 'block';
    }   


    function capturePhoto() {
        console.log('Capturing photo...');
        const canvas = document.getElementById('canvas');
        const video = document.getElementById('camera');
        const photo = document.getElementById('photo');
        const context = canvas.getContext('2d');

        // Set canvas size to match video size
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        // Draw the current frame from the video onto the canvas
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Convert canvas to data URL and set it as the src of the image element
        const dataURL = canvas.toDataURL('image/png');
        photo.src = dataURL;
        photo.style.display = 'block'; // Show the photo

        overlayCapturedPhoto();
    }

    function overlayCapturedPhoto() {
        if (!selectedOverlay) return;

        const canvas = document.getElementById('canvas');
        const photo = document.getElementById('photo');
        const overlayImage = document.getElementById('selected-image');

        if (overlayImage) {
            const resultCanvas = document.createElement('canvas');
            resultCanvas.width = canvas.width;
            resultCanvas.height = canvas.height;
            const resultContext = resultCanvas.getContext('2d');

            // Draw the captured photo on the result canvas
            resultContext.drawImage(photo, 0, 0);

            // Draw the selected overlay image on top of the captured photo
            resultContext.drawImage(overlayImage, 0, 0);

            // Convert the result canvas to data URL and display
            const resultURL = resultCanvas.toDataURL('image/png');
            photo.src = resultURL;
        } else {
            console.error("Element with id 'selected-image' not found.");
        }
    }

    // Add event listener to the button
    // document.getElementById('capture-button').addEventListener('click', capturePhoto);

    // Start the camera when the page loads
    window.onload = startCamera;
</script>
