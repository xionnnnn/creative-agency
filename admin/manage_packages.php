<?php
require_once '../includes/db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Handle delete
if (isset($_GET['delete'])) {
    $package_id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM packages WHERE package_id = $package_id";
    if ($conn->query($delete_sql)) {
        $success_message = "Package deleted successfully!";
    } else {
        $error_message = "Error deleting package.";
    }
}

// Get all packages
$packages_sql = "SELECT * FROM packages ORDER BY created_at DESC";
$packages_result = $conn->query($packages_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Packages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="manage_packages.php">
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
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_bookings.php">
                                <i class="fas fa-calendar-alt"></i> Bookings
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
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Packages</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="add_package.php" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Add New Package
                        </a>
                    </div>
                </div>

                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="packagesTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Package Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($packages_result->num_rows > 0): ?>
                                        <?php while($package = $packages_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo $package['package_id']; ?></td>
                                            <td>
                                                <img src="<?php echo $package['package_image'] ? '../' . $package['package_image'] : '../assets/images/placeholder.jpg'; ?>" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td><?php echo htmlspecialchars($package['package_name']); ?></td>
                                            <td><?php echo substr(htmlspecialchars($package['description']), 0, 50); ?>...</td>
                                            <td>₱<?php echo number_format($package['price'], 2); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($package['created_at'])); ?></td>
                                            <td>
                                                <a href="edit_package.php?id=<?php echo $package['package_id']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?delete=<?php echo $package['package_id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this package?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
    
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#packagesTable').DataTable();
    });
    </script>
</body>
</html>