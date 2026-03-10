<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lens Creative Agency - Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Carousel Section -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="assets/images/slide1.jpg" class="d-block w-100" alt="Wedding Photography" style="height: 600px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4">Wedding Photography</h1>
                <p class="lead">Capture your special day with elegance and style</p>
                <a href="inquire.php" class="btn btn-primary btn-lg">Inquire Now</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide2.jpg" class="d-block w-100" alt="Birthday Photography" style="height: 600px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4">Birthday Celebrations</h1>
                <p class="lead">Make your birthday memories last forever</p>
                <a href="inquire.php" class="btn btn-primary btn-lg">Inquire Now</a>
            </div>
        </div>
        <div class="carousel-item">
            <img src="assets/images/slide3.jpg" class="d-block w-100" alt="Candid Photography" style="height: 600px; object-fit: cover;">
            <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4">Candid Moments</h1>
                <p class="lead">Natural and spontaneous photography</p>
                <a href="inquire.php" class="btn btn-primary btn-lg">Inquire Now</a>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Main Content -->
<div class="container mt-5">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-md-9">
            <h2 class="mb-4">Our Photography Packages</h2>
            <div class="row">
                <?php
                // Fetch packages from database - UPDATED TABLE NAME AND COLUMNS
                $sql = "SELECT * FROM tbl_packages WHERE package_status = 'active' ORDER BY package_created_at DESC";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card package-card h-100">
                                <img src="<?php echo $row['package_image'] ?: 'assets/images/placeholder.jpg'; ?>" class="card-img-top" alt="<?php echo $row['package_name']; ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['package_name']); ?></h5>
                                    <p class="card-text"><?php echo substr(htmlspecialchars($row['package_description']), 0, 100); ?>...</p>
                                    <p class="text-primary fw-bold">₱<?php echo number_format($row['package_price'], 2); ?></p>
                                </div>
                                <div class="card-footer bg-transparent border-top-0">
                                    <a href="inquire.php?package_id=<?php echo $row['package_id']; ?>" class="btn btn-primary btn-sm">Inquire</a>
                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <button class="btn btn-outline-danger btn-sm favorite-btn" data-package-id="<?php echo $row['package_id']; ?>">
                                            <i class="far fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 text-center py-5'>";
                    echo "<i class='fas fa-box-open fa-4x text-muted mb-3'></i>";
                    echo "<p class='lead'>No packages available at the moment.</p>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
        
        <?php include 'includes/sidebar.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Favorite button functionality
$(document).ready(function() {
    $('.favorite-btn').click(function() {
        var packageId = $(this).data('package-id');
        var btn = $(this);
        
        $.ajax({
            url: 'favorite_handler.php',
            method: 'POST',
            data: { package_id: packageId },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status == 'added') {
                        btn.find('i').removeClass('far').addClass('fas');
                        // Optional: Show success message
                        showNotification('Added to favorites!', 'success');
                    } else if (data.status == 'removed') {
                        btn.find('i').removeClass('fas').addClass('far');
                        showNotification('Removed from favorites!', 'info');
                    } else if (data.status == 'error') {
                        alert(data.message);
                    }
                } catch(e) {
                    console.log('Error parsing response:', e);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Notification function
    function showNotification(message, type) {
        // You can implement a toast notification here
        // For now, just console.log
        console.log(message);
    }
});
</script>
</body>
</html>