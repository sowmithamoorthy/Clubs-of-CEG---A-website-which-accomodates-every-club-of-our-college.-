<?php
require 'db_connection.php'; // Include your database connection script

$stmt = $pdo->query('SELECT filename FROM images');
$images = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode($images);
?>
