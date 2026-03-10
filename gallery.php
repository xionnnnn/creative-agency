<?php
include("includes/db.php");

$query = "SELECT * FROM gallery";
$result = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($result)){
?>

<img src="uploads/<?php echo $row['image_path']; ?>" width="200">

<?php } ?>