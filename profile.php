<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    
    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/profiles/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
        $file_name = "user_" . $user_id . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
            $update_sql = "UPDATE tbl_users SET user_fullname='$fullname', user_phone='$phone', user_profile='$target_file' WHERE user_id=$user_id";
        } else {
            $error_message = "Failed to upload profile picture.";
        }
    } else {
        $update_sql = "UPDATE tbl_users SET user_fullname='$fullname', user_phone='$phone' WHERE user_id=$user_id";
    }
    
    if (!isset($error_message) && $conn->query($update_sql)) {
        $_SESSION['user_fullname'] = $fullname;
        $success_message = "Profile updated successfully!";
    } elseif (!isset($error_message)) {
        $error_message = "Failed to update profile.";
    }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verify current password
    $password_sql = "SELECT user_password FROM tbl_users WHERE user_id = $user_id";
    $password_result = $conn->query($password_sql);
    $user_data = $password_result->fetch_assoc();
    
    if (password_verify($current_password, $user_data['user_password'])) {
        if ($new_password == $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password = "UPDATE tbl_users SET user_password='$hashed_password' WHERE user_id=$user_id";
                if ($conn->query($update_password)) {
                    $success_message = "Password changed successfully!";
                } else {
                    $error_message = "Failed to change password.";
                }
            } else {
                $error_message = "New password must be at least 6 characters.";
            }
        } else {
            $error_message = "New passwords do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

// Get user data
$user_sql = "SELECT * FROM tbl_users WHERE user_id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

// Get user's favorites
$favorites_sql = "SELECT p.* FROM tbl_favorites f 
                  JOIN tbl_packages p ON f.favorite_package_id = p.package_id 
                  WHERE f.favorite_user_id = $user_id AND p.package_status = 'active'
                  ORDER BY f.favorite_created_at DESC";
$favorites_result = $conn->query($favorites_sql);

// Get user's inquiries
$inquiries_sql = "SELECT * FROM tbl_inquiries WHERE inquiry_user_id = $user_id ORDER BY inquiry_created_at DESC";
$inquiries_result = $conn->query($inquiries_sql);

// Get user's bookings
$bookings_sql = "SELECT b.*, p.package_name, p.package_price 
                 FROM tbl_bookings b 
                 JOIN tbl_packages p ON b.booking_package_id = p.package_id 
                 WHERE b.booking_user_id = $user_id 
                 ORDER BY b.booking_created_at DESC";
$bookings_result = $conn->query($bookings_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Sidebar -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?php echo $user['user_profile'] ?: 'assets/images/default-avatar.png'; ?>" 
                         class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    <h5><?php echo htmlspecialchars($user['user_fullname']); ?></h5>
                    <p class="text-muted"><i class="fas fa-envelope"></i> <?php echo $user['user_email']; ?></p>
                    <p class="text-muted"><i class="fas fa-phone"></i> <?php echo $user['user_phone'] ?: 'No phone'; ?></p>
                    <p><span class="badge bg-success">Member since <?php echo date('M Y', strtotime($user['user_created_at'])); ?></span></p>
                </div>
            </div>
            
            <div class="list-group mb-4">
                <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-user"></i> Profile Information
                </a>
                <a href="#favorites" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-heart"></i> My Favorites
                    <?php if ($favorites_result->num_rows > 0): ?>
                        <span class="badge bg-danger float-end"><?php echo $favorites_result->num_rows; ?></span>
                    <?php endif; ?>
                </a>
                <a href="#bookings" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-calendar-check"></i> My Bookings
                    <?php if ($bookings_result->num_rows > 0): ?>
                        <span class="badge bg-info float-end"><?php echo $bookings_result->num_rows; ?></span>
                    <?php endif; ?>
                </a>
                <a href="#inquiries" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-question-circle"></i> My Inquiries
                    <?php if ($inquiries_result->num_rows > 0): ?>
                        <span class="badge bg-warning float-end"><?php echo $inquiries_result->num_rows; ?></span>
                    <?php endif; ?>
                </a>
                <a href="#password" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-key"></i> Change Password
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <?php if ($success_message): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <div class="tab-content">
                <!-- Profile Information Tab -->
                <div class="tab-pane fade show active" id="profile">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullname" name="fullname" 
                                           value="<?php echo htmlspecialchars($user['user_fullname']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" 
                                           value="<?php echo $user['user_email']; ?>" disabled>
                                    <small class="text-muted">Email cannot be changed</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo $user['user_phone']; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="profile_picture" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current picture</small>
                                </div>
                                
                                <button type="submit" name="update_profile" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Favorites Tab -->
                <div class="tab-pane fade" id="favorites">
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fas fa-heart"></i> My Favorite Packages</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($favorites_result->num_rows > 0): ?>
                                <div class="row">
                                    <?php while($fav = $favorites_result->fetch_assoc()): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <img src="<?php echo $fav['package_image'] ?: 'assets/images/placeholder.jpg'; ?>" 
                                                     class="card-img-top" style="height: 150px; object-fit: cover;">
                                                <div class="card-body">
                                                    <h6><?php echo htmlspecialchars($fav['package_name']); ?></h6>
                                                    <p class="text-primary fw-bold">₱<?php echo number_format($fav['package_price'], 2); ?></p>
                                                    <a href="booking.php?package_id=<?php echo $fav['package_id']; ?>" 
                                                       class="btn btn-sm btn-primary">Book Now</a>
                                                    <button class="btn btn-sm btn-outline-danger remove-favorite" 
                                                            data-package-id="<?php echo $fav['package_id']; ?>">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                                    <p>You haven't added any favorites yet.</p>
                                    <a href="index.php" class="btn btn-primary">Browse Packages</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Bookings Tab -->
                <div class="tab-pane fade" id="bookings">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> My Bookings</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($bookings_result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Booking ID</th>
                                                <th>Package</th>
                                                <th>Event Date</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($booking = $bookings_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td>#<?php echo $booking['booking_id']; ?></td>
                                                    <td><?php echo htmlspecialchars($booking['package_name']); ?></td>
                                                    <td><?php echo date('M d, Y', strtotime($booking['booking_event_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($booking['booking_event_location']); ?></td>
                                                    <td>
                                                        <?php
                                                        $status_class = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'completed' => 'info',
                                                            'cancelled' => 'danger'
                                                        ];
                                                        $status = $booking['booking_status'];
                                                        ?>
                                                        <span class="badge bg-<?php echo $status_class[$status]; ?>">
                                                            <?php echo ucfirst($status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="booking_details.php?id=<?php echo $booking['booking_id']; ?>" 
                                                           class="btn btn-sm btn-info">View</a>
                                                        <?php if ($status == 'pending'): ?>
                                                            <button class="btn btn-sm btn-danger cancel-booking" 
                                                                    data-booking-id="<?php echo $booking['booking_id']; ?>">
                                                                Cancel
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                    <p>You haven't made any bookings yet.</p>
                                    <a href="index.php" class="btn btn-primary">Book Now</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Inquiries Tab -->
                <div class="tab-pane fade" id="inquiries">
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h5 class="mb-0"><i class="fas fa-question-circle"></i> My Inquiries</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($inquiries_result->num_rows > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Subject</th>
                                                <th>Message</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while($inq = $inquiries_result->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo date('M d, Y', strtotime($inq['inquiry_created_at'])); ?></td>
                                                    <td><?php echo htmlspecialchars($inq['inquiry_subject'] ?: 'General Inquiry'); ?></td>
                                                    <td><?php echo substr(htmlspecialchars($inq['inquiry_message']), 0, 50); ?>...</td>
                                                    <td>
                                                        <?php
                                                        $status_class = [
                                                            'pending' => 'warning',
                                                            'read' => 'info',
                                                            'replied' => 'success',
                                                            'closed' => 'secondary'
                                                        ];
                                                        ?>
                                                        <span class="badge bg-<?php echo $status_class[$inq['inquiry_status']]; ?>">
                                                            <?php echo ucfirst($inq['inquiry_status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                    <p>You haven't made any inquiries yet.</p>
                                    <a href="contact.php" class="btn btn-primary">Contact Us</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="password">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-key"></i> Change Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                    <small class="text-muted">Minimum 6 characters</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                </div>
                                
                                <button type="submit" name="change_password" class="btn btn-warning">
                                    <i class="fas fa-key"></i> Change Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Remove favorite
$('.remove-favorite').click(function() {
    var packageId = $(this).data('package-id');
    var card = $(this).closest('.col-md-6');
    
    if (confirm('Remove this package from favorites?')) {
        $.ajax({
            url: 'favorite_handler.php',
            method: 'POST',
            data: { package_id: packageId, remove: true },
            success: function(response) {
                card.fadeOut();
                location.reload();
            }
        });
    }
});

// Cancel booking
$('.cancel-booking').click(function() {
    var bookingId = $(this).data('booking-id');
    
    if (confirm('Are you sure you want to cancel this booking?')) {
        $.ajax({
            url: 'cancel_booking.php',
            method: 'POST',
            data: { booking_id: bookingId },
            success: function(response) {
                location.reload();
            }
        });
    }
});
</script>
</body>
</html>