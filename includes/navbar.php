<nav class="navbar">
    <h2>LENS</h2>

    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="gallery.php">Gallery</a></li>
        <li><a href="inquire.php">Inquire</a></li>
        <li><a href="favorites.php">Favorites</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>

        <?php if(isset($_SESSION['user_id'])){ ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php }else{ ?>
            <li><a href="login.php">Login</a></li>
        <?php } ?>
    </ul>
</nav>