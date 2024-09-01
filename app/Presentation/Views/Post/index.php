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
    <img src="/Presentation/Assets/img/blue-waves.png" onclick="selectOverlay(this.src)" alt="Overlay 1">
    <!-- Add more images as needed -->
</div>

<form id="photo-form" action="/supperpose-images" method="post" enctype="multipart/form-data">
    <input type="hidden" name="captured_image" id="captured_image_input">
    <input type="hidden" name="overlay_image" id="overlay_image_input">
    <input type="submit" value="Submit">
</form>

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

    // TODO: remove this
    document.getElementById('photo-form').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting immediately
        
        // Log form data to the console
        const capturedImage = document.getElementById('captured_image_input').value;
        const overlayImage = document.getElementById('overlay_image_input').value;
        console.log('Captured Image:', capturedImage);
        console.log('Overlay Image:', overlayImage);

        // Submit the form after logging
        event.target.submit(); // Continue with the form submission
        
    });

    function selectOverlay(src) {

        console.log('Selected overlay:', src);

        selectedOverlay = src;
        const overlayImage = document.getElementById('selected-image');

        // TODO: voir que c'est selectionné
        overlayImage.style.border = '2px solid red';
        overlayImage.src = selectedOverlay;
        overlayImage.style.display = 'block';

        // Set the data URL in the hidden input field for the form
        document.getElementById('overlay_image_input').value = src;
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

        // Set the data URL in the hidden input field for the form
        document.getElementById('captured_image_input').value = dataURL;

    }

    // Start the camera when the page loads
    window.onload = startCamera;
</script>
