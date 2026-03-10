<?php
session_start();
include("includes/db.php");

if(isset($_POST['login'])){

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn,$query);

$user = mysqli_fetch_assoc($result);

if(password_verify($password,$user['password'])){

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['role'] = $user['role'];

if($user['role'] == 'admin'){
header("Location: admin/dashboard.php");
}else{
header("Location: index.php");
}

}

}
?>

<form method="POST">

<h2>Login</h2>

<input type="email" name="email" placeholder="Email">

<input type="password" name="password" placeholder="Password">

<button name="login">Login</button>

</form>