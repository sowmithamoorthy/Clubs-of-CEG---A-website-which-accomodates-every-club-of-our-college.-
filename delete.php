<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteImage'])) {
        $imageId = $_POST['imageId'];
        $filename = $data['image'];
        $targetDir = 'uploads/';
        $targetFile = $targetDir . $filename;

        // Remove image information from the database
        $stmt = $pdo->prepare('DELETE FROM images WHERE filename = ?');
        $stmt->execute([$filename]);

        if (unlink($targetFile)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'File deletion failed.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Image not specified.']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request.']);
}
?>
