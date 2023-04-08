const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
const scanButton = document.getElementById('scan-button');
const response = document.getElementById('response');
const overlay = document.getElementById('overlay')
let scanning = false;
let stream;
let data = "";
import jsQR from 'jsqr';


function delay(time) {
  return new Promise(resolve => setTimeout(resolve, time));
}


// Scan for barcodes every 100ms
function scanBarcode() {
  if (scanning) {
    // Draw video frame to canvas
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    // Get barcode data from canvas
    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height, {
      inversionAttempts: 'dontInvert',
    });

    // If barcode detected, log the data and stop scanning
    if (code) {
      console.log('Barcode detected:', code.data);
      callData(code.data);
      scanning = false;
      stream.getTracks().forEach(track => track.stop());
      overlay.style.visibility = "visible";
    }
  }
}

setInterval(scanBarcode, 100);

function showData(response) {
  if (response.ok) {
    response.text().then(function(data) {
      document.getElementById('response').innerHTML = `Success: ${data}`;
    });
  } else {
    document.getElementById('response').innerHTML = 'Error!';
  }
}

function callData(decodedData) {
  const barcode = decodedData;
  const url = `https://yourwebsite.com/check-in?barcode=${barcode}`;

  fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  })
    .then(response => showData(response))
    .then(() => {
      data = "Success";
    })
    .catch(error => console.error(error));
}

function startCamera() {
  navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
    .then(s => {
      stream = s;
      video.srcObject = stream;
      video.play();
      delay(3000).then(() => scanning = true);
      scanning = true;
      data = "";
      response.innerHTML = "";
    })
    .catch(error => {
      console.error('Error accessing camera', error);
    });
}

// Activate and deactivate scanner with button
scanButton.addEventListener('click', () => {
  if (scanning) {
    // If scanning is in progress, stop it
    scanning = false;
    stream.getTracks().forEach(track => track.stop());
  } else {
    // Otherwise, start the camera stream
    startCamera();
    overlay.style.visibility = "hidden";
  }
  scanButton.disabled = false;
});