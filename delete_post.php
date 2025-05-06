<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$conn = getDBConnection();

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: posts.php");
exit();
?> 