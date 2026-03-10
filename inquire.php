<?php
include("includes/db.php");

if(isset($_POST['send'])){

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$query = "INSERT INTO inquiries(name,email,message)
VALUES('$name','$email','$message')";

mysqli_query($conn,$query);

echo "Inquiry sent!";

}
?>

<form method="POST">

<h2>Send Inquiry</h2>

<input type="text" name="name" placeholder="Name">

<input type="email" name="email" placeholder="Email">

<textarea name="message"></textarea>

<button name="send">Submit</button>

</form>