<?php
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM tbl_users WHERE user_status = 'active'")->fetch_assoc()['count'];
$total_packages = $conn->query("SELECT COUNT(*) as count FROM tbl_packages WHERE package_status = 'active'")->fetch_assoc()['count'];
$total_inquiries = $conn->query("SELECT COUNT(*) as count FROM tbl_inquiries")->fetch_assoc()['count'];
$pending_inquiries = $conn->query("SELECT COUNT(*) as count FROM tbl_inquiries WHERE inquiry_status = 'pending'")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM tbl_bookings")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM tbl_bookings WHERE booking_status = 'pending'")->fetch_assoc()['count'];
$total_gallery = $conn->query("SELECT COUNT(*) as count FROM tbl_gallery WHERE gallery_status = 'published'")->fetch_assoc()['count'];
$total_contacts = $conn->query("SELECT COUNT(*) as count FROM tbl_contact_messages WHERE contact_status = 'unread'")->fetch_assoc()['count'];

// Get admin info
$admin_id = $_SESSION['admin_id'];
$admin_sql = "SELECT * FROM tbl_admin WHERE admin_id = $admin_id";
$admin_result = $conn->query($admin_sql);
$admin = $admin_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <img src="<?php echo $admin['admin_profile'] ? '../' . $admin['admin_profile'] : '../assets/images/admin-avatar.png'; ?>" 
                             class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        <h6 class="mt-2"><?php echo htmlspecialchars($admin['admin_name']); ?></h6>
                        <small class="text-muted">Administrator</small>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_packages.php">
                                <i class="fas fa-box"></i> Packages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="add_package.php">
                                <i class="fas fa-plus-circle"></i> Add Package
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_inquiries.php">
                                <i class="fas fa-question-circle"></i> Inquiries
                                <?php if ($pending_inquiries > 0): ?>
                                    <span class="badge bg-danger float-end"><?php echo $pending_inquiries; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_bookings.php">
                                <i class="fas fa-calendar-alt"></i> Bookings
                                <?php if ($pending_bookings > 0): ?>
                                    <span class="badge bg-warning float-end"><?php echo $pending_bookings; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_gallery.php">
                                <i class="fas fa-images"></i> Gallery
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_users.php">
                                <i class="fas fa-users"></i> Users
                                <span class="badge bg-info float-end"><?php echo $total_users; ?></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_contacts.php">
                                <i class="fas fa-envelope"></i> Contact Messages
                                <?php if ($total_contacts > 0): ?>
                                    <span class="badge bg-primary float-end"><?php echo $total_contacts; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user-shield"></i> My Profile
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <a href="add_package.php" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> New Package
                            </a>
                            <a href="add_gallery.php" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus"></i> Add to Gallery
                            </a>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Total Users</h6>
                                        <h2 class="mb-0"><?php echo $total_users; ?></h2>
                                    </div>
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <small class="text-white-50">Active members</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Packages</h6>
                                        <h2 class="mb-0"><?php echo $total_packages; ?></h2>
                                    </div>
                                    <i class="fas fa-box fa-3x"></i>
                                </div>
                                <small class="text-white-50">Active packages</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Bookings</h6>
                                        <h2 class="mb-0"><?php echo $total_bookings; ?></h2>
                                    </div>
                                    <i class="fas fa-calendar-check fa-3x"></i>
                                </div>
                                <small class="text-white-50"><?php echo $pending_bookings; ?> pending</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Inquiries</h6>
                                        <h2 class="mb-0"><?php echo $total_inquiries; ?></h2>
                                    </div>
                                    <i class="fas fa-question-circle fa-3x"></i>
                                </div>
                                <small class="text-white-50"><?php echo $pending_inquiries; ?> pending</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Gallery</h6>
                                        <h2 class="mb-0"><?php echo $total_gallery; ?></h2>
                                    </div>
                                    <i class="fas fa-images fa-3x"></i>
                                </div>
                                <small class="text-white-50">Published images</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <div class="card text-white bg-secondary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title">Messages</h6>
                                        <h2 class="mb-0"><?php echo $total_contacts; ?></h2>
                                    </div>
                                    <i class="fas fa-envelope fa-3x"></i>
                                </div>
                                <small class="text-white-50">Unread contact messages</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-clock"></i> Recent Bookings</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $recent_bookings = $conn->query("
                                    SELECT b.*, u.user_fullname, p.package_name 
                                    FROM tbl_bookings b 
                                    JOIN tbl_users u ON b.booking_user_id = u.user_id 
                                    JOIN tbl_packages p ON b.booking_package_id = p.package_id 
                                    ORDER BY b.booking_created_at DESC 
                                    LIMIT 5
                                ");
                                
                                if ($recent_bookings->num_rows > 0):
                                ?>
                                <div class="list-group">
                                    <?php while($booking = $recent_bookings->fetch_assoc()): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($booking['user_fullname']); ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo $booking['package_name']; ?> - 
                                                        <?php echo date('M d, Y', strtotime($booking['booking_event_date'])); ?>
                                                    </small>
                                                </div>
                                                <span class="badge bg-<?php 
                                                    echo $booking['booking_status'] == 'pending' ? 'warning' : 
                                                        ($booking['booking_status'] == 'approved' ? 'success' : 
                                                        ($booking['booking_status'] == 'completed' ? 'info' : 'danger')); 
                                                ?>">
                                                    <?php echo ucfirst($booking['booking_status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <?php else: ?>
                                    <p class="text-center">No recent bookings</p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <a href="manage_bookings.php" class="btn btn-sm btn-primary">View All Bookings</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-warning">
                                <h5 class="mb-0"><i class="fas fa-question-circle"></i> Recent Inquiries</h5>
                            </div>
                            <div class="card-body">
                                <?php
                                $recent_inquiries = $conn->query("
                                    SELECT * FROM tbl_inquiries 
                                    ORDER BY inquiry_created_at DESC 
                                    LIMIT 5
                                ");
                                
                                if ($recent_inquiries->num_rows > 0):
                                ?>
                                <div class="list-group">
                                    <?php while($inq = $recent_inquiries->fetch_assoc()): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($inq['inquiry_name']); ?></h6>
                                                    <small class="text-muted">
                                                        <?php echo substr(htmlspecialchars($inq['inquiry_message']), 0, 50); ?>...
                                                    </small>
                                                </div>
                                                <span class="badge bg-<?php 
                                                    echo $inq['inquiry_status'] == 'pending' ? 'warning' : 
                                                        ($inq['inquiry_status'] == 'read' ? 'info' : 
                                                        ($inq['inquiry_status'] == 'replied' ? 'success' : 'secondary')); 
                                                ?>">
                                                    <?php echo ucfirst($inq['inquiry_status']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <?php else: ?>
                                    <p class="text-center">No recent inquiries</p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <a href="manage_inquiries.php" class="btn btn-sm btn-warning">View All Inquiries</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="card mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-plus"></i> Recent Users</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $recent_users = $conn->query("
                            SELECT * FROM tbl_users 
                            WHERE user_status = 'active' 
                            ORDER BY user_created_at DESC 
                            LIMIT 5
                        ");
                        
                        if ($recent_users->num_rows > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Joined Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($user = $recent_users->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['user_fullname']); ?></td>
                                            <td><?php echo $user['user_email']; ?></td>
                                            <td><?php echo $user['user_phone'] ?: 'N/A'; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($user['user_created_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <p class="text-center">No users yet</p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a href="manage_users.php" class="btn btn-sm btn-success">View All Users</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>