// Function to add image
function addImage() {
    var fileInput = document.getElementById('fileInput');
    var file = fileInput.files[0];

    if (file) {
        var formData = new FormData();
        formData.append('image', file);

        // Use Fetch API to send the file to the server
        fetch('leoupload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Handle the response (e.g., update the gallery)
            console.log(data);
            updateGallery();
        })
        .catch(error => console.error('Error:', error));
    }
}

// Function to delete image
function deleteImage() {
    var deleteSelect = document.getElementById('deleteSelect');
    var selectedImage = deleteSelect.value;

    // Use Fetch API to send the selected image to the server for deletion
    fetch('delete.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ image: selectedImage })
    })
    .then(response => response.json())
    .then(data => {
        // Handle the response (e.g., update the gallery)
        console.log(data);
        updateGallery();
    })
    .catch(error => console.error('Error:', error));
}

// Function to update the gallery after adding or deleting images
function updateGallery() {
    // Fetch the list of images from the server and update the delete select
    fetch('get_images.php')
    .then(response => response.json())
    .then(data => {
        var deleteSelect = document.getElementById('deleteSelect');

        // Clear existing options
        deleteSelect.innerHTML = '';

        // Populate the delete select with image filenames
        data.forEach(image => {
            var option = document.createElement('option');
            option.value = image;
            option.textContent = image;
            deleteSelect.appendChild(option);
        });
    })
    .catch(error => console.error('Error:', error));
}

// Initial gallery update on page load
updateGallery();
