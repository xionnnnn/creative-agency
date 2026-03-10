<?php
session_start();

if($_SESSION['role'] != 'admin'){
header("Location: ../index.php");
}

?>

<h1>Admin Dashboard</h1>

<a href="add_package.php">Add Package</a>

<a href="manage_inquiries.php">View Inquiries</a>