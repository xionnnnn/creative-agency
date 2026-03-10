<?php
session_start();
include("includes/db.php");
?>

<!DOCTYPE html>
<html>
<head>
<title>Lens Creative Agency</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<?php include("includes/navbar.php"); ?>

<!-- Carousel -->
<div class="carousel">

    <div class="slide">
        <img src="assets/images/wedding.jpg">
    </div>

    <div class="slide">
        <img src="assets/images/birthday.jpg">
    </div>

    <div class="slide">
        <img src="assets/images/candid.jpg">
    </div>

</div>

<h2>Photography Packages</h2>

<div class="packages">

<?php

$query = "SELECT * FROM packages";
$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){
?>

<div class="card">

<img src="uploads/<?php echo $row['package_image']; ?>" width="200">

<h3><?php echo $row['package_name']; ?></h3>

<p><?php echo $row['description']; ?></p>

<p>₱<?php echo $row['price']; ?></p>

</div>

<?php } ?>

</div>

</body>
</html>