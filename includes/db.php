<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lens_agency";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8
$conn->set_charset("utf8mb4");

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if admin is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

// Function to redirect if not logged in
function requireLogin() {
    if (!isUserLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Function to redirect if not admin
function requireAdmin() {
    if (!isAdminLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}
?>