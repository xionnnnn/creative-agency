<?php
require_once 'includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please login first']);
    exit();
}

$user_id = $_SESSION['user_id'];
$package_id = (int)$_POST['package_id'];
$response = [];

// Check if already favorited
$check_sql = "SELECT favorite_id FROM favorites WHERE user_id = $user_id AND package_id = $package_id";
$check_result = $conn->query($check_sql);

if (isset($_POST['remove'])) {
    // Remove from favorites
    $delete_sql = "DELETE FROM favorites WHERE user_id = $user_id AND package_id = $package_id";
    if ($conn->query($delete_sql)) {
        $response = ['status' => 'removed', 'message' => 'Removed from favorites'];
    }
} else {
    if ($check_result->num_rows > 0) {
        // Already favorited, remove it
        $delete_sql = "DELETE FROM favorites WHERE user_id = $user_id AND package_id = $package_id";
        if ($conn->query($delete_sql)) {
            $response = ['status' => 'removed', 'message' => 'Removed from favorites'];
        }
    } else {
        // Add to favorites
        $insert_sql = "INSERT INTO favorites (user_id, package_id) VALUES ($user_id, $package_id)";
        if ($conn->query($insert_sql)) {
            $response = ['status' => 'added', 'message' => 'Added to favorites'];
        }
    }
}

echo json_encode($response);
?>