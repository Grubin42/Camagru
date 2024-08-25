
    <title>Front Camera Stream</title>
    <style>
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>

<div id="camera-container">
    <video id="camera" autoplay></video>
</div>

<div id="controls">
    <button id="capture-button" >Capture Photo</button>
</div>

<canvas id="canvas" style="display: none;"></canvas>
<img id="photo" src="" alt="Captured Photo">

<script>
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
    }

    // Add event listener to the button
    document.getElementById('capture-button').addEventListener('click', capturePhoto);

    document.getElementById('capture-button').addEventListener('click', capturePhoto);

    // Start the camera when the page loads
    window.onload = startCamera;
</script>
