<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $subject = $conn->real_escape_string($_POST['subject']);
    $message = $conn->real_escape_string($_POST['message']);
    
    $sql = "INSERT INTO contact_messages (name, email, subject, message) 
            VALUES ('$name', '$email', '$subject', '$message')";
    
    if ($conn->query($sql)) {
        $success_message = "Thank you for contacting us! We'll get back to you soon.";
    } else {
        $error_message = "Sorry, something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <h2 class="mb-4">Contact Us</h2>
            
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-envelope"></i> Send us a Message</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject *</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message *</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Contact Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6><i class="fas fa-map-marker-alt text-danger"></i> Address</h6>
                                <p>123 Photography Street<br>Creative City, PC 12345<br>Philippines</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-phone text-primary"></i> Phone</h6>
                                <p>+63 (123) 456-7890<br>+63 (987) 654-3210</p>
                            </div>
                            
                            <div class="mb-4">
                                <h6><i class="fas fa-envelope text-info"></i> Email</h6>
                                <p>info@lensagency.com<br>support@lensagency.com</p>
                            </div>
                            
                            <div>
                                <h6><i class="fas fa-clock text-warning"></i> Office Hours</h6>
                                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-map"></i> Find Us</h5>
                        </div>
                        <div class="card-body p-0">
                            <!-- Google Map Placeholder -->
                            <div style="height: 250px; background-color: #f0f0f0;">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12345.67890!2d123.456!3d12.345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTLCsDIwJzU0LjAiTiAxMjPCsDI3JzM2LjAiRQ!5e0!3m2!1sen!2sph!4v1234567890" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php include 'includes/sidebar.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>