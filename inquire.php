<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

$package_id = isset($_GET['package_id']) ? (int)$_GET['package_id'] : 0;
$package = null;

if ($package_id > 0) {
    $package_sql = "SELECT * FROM packages WHERE package_id = $package_id";
    $package_result = $conn->query($package_sql);
    $package = $package_result->fetch_assoc();
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'NULL';
    
    $sql = "INSERT INTO inquiries (user_id, name, email, message) 
            VALUES ($user_id, '$name', '$email', '$message')";
    
    if ($conn->query($sql)) {
        $success_message = "Your inquiry has been sent successfully! We'll get back to you soon.";
    } else {
        $error_message = "Failed to send inquiry. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquire - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-paper-plane"></i> Send an Inquiry</h4>
                </div>
                <div class="card-body">
                    <?php if ($success_message): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($package): ?>
                        <div class="alert alert-info">
                            <h5>Inquiring about: <?php echo htmlspecialchars($package['package_name']); ?></h5>
                            <p>Price: ₱<?php echo number_format($package['price'], 2); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required>
<?php if ($package): ?>
I'm interested in your <?php echo $package['package_name']; ?>. Please provide more details.
<?php endif; ?>
                            </textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Send Inquiry
                        </button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>