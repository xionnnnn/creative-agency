<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <i class="fas fa-camera"></i> Lens Creative Agency
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" href="index.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'gallery.php' ? 'active' : ''; ?>" href="gallery.php">
                        <i class="fas fa-images"></i> Gallery
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>" href="about.php">
                        <i class="fas fa-info-circle"></i> About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>" href="contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'favorites.php' ? 'active' : ''; ?>" href="favorites.php">
                            <i class="fas fa-heart"></i> Favorites
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_fullname']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-id-card"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="my_bookings.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php elseif (isset($_SESSION['admin_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['admin_name']); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Admin Panel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'login.php' ? 'active' : ''; ?>" href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page == 'signup.php' ? 'active' : ''; ?>" href="signup.php">
                            <i class="fas fa-user-plus"></i> Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>