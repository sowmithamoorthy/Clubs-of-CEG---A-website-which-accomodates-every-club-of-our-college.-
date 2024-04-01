<?php

require 'db_connection.php'; // Include your database connection script

// Function to fetch image filenames from the database
function getImagesFromDB() {
    global $pdo;
    $stmt = $pdo->query('SELECT filename FROM simages');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if it's a GET request and if it's for fetching images
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['getImages'])) {
    // Fetch image filenames from the database
    $images = getImagesFromDB();
    
    // Send JSON response with image filenames
    echo json_encode(['success' => true, 'images' => $images]);
    exit();
}

// Check if it's a POST request and if it's for uploading an image
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    if (isset($_POST['addImage'])) {
        $targetDir = 'suploads/';
        $filename = basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            // Add image information to the database
            $stmt = $pdo->prepare('INSERT INTO simages (filename) VALUES (?)');
            $stmt->execute([$filename]);
        
            // Redirect to "pguindytimes" after successful image upload
            header("Location:pspartanz.html");
            exit();
        } else {
            echo json_encode(['success' => false, 'error' => 'File upload failed.']);
        }
        
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
