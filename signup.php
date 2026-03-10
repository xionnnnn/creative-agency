<?php
include("includes/db.php");

if(isset($_POST['signup'])){

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$query = "INSERT INTO users(fullname,email,password)
VALUES('$name','$email','$password')";

mysqli_query($conn,$query);

header("Location: login.php");

}
?>

<form method="POST">

<h2>Sign Up</h2>

<input type="text" name="name" placeholder="Full Name">

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<button name="signup">Register</button>

</form>