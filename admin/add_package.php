<?php
require_once '../includes/db.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $package_name = $conn->real_escape_string($_POST['package_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    
    // Handle image upload
    $package_image = '';
    if (isset($_FILES['package_image']) && $_FILES['package_image']['error'] == 0) {
        $target_dir = "../uploads/packages/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['package_image']['name'], PATHINFO_EXTENSION);
        $file_name = "package_" . time() . "." . $file_extension;
        $target_file = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES['package_image']['tmp_name'], $target_file)) {
            $package_image = "uploads/packages/" . $file_name;
        }
    }
    
    $sql = "INSERT INTO packages (package_name, description, price, package_image) 
            VALUES ('$package_name', '$description', '$price', '$package_image')";
    
    if ($conn->query($sql)) {
        $success_message = "Package added successfully!";
    } else {
        $error_message = "Error adding package: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Package - Admin</title>
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
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="manage_packages.php">
                                <i class="fas fa-box"></i> Packages
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="add_package.php">
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
                    <h1 class="h2">Add New Package</h1>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="package_name" class="form-label">Package Name *</label>
                                <input type="text" class="form-control" id="package_name" name="package_name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (₱) *</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="package_image" class="form-label">Package Image</label>
                                <input type="file" class="form-control" id="package_image" name="package_image" accept="image/*">
                                <small class="text-muted">Recommended size: 800x600 pixels</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Package
                            </button>
                            <a href="manage_packages.php" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>