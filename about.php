<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <h2 class="mb-4">About Lens Creative Agency</h2>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Our Story</h4>
                    <p>Founded in 2020, Lens Creative Agency has been at the forefront of professional photography services. We believe that every moment tells a story, and it's our passion to capture those stories through our lenses.</p>
                    <p>What started as a small team of passionate photographers has grown into a full-service creative agency specializing in various photography genres including weddings, birthdays, candid moments, debuts, and pre-wedding shoots.</p>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4><i class="fas fa-bullseye text-primary"></i> Our Mission</h4>
                            <p>To provide exceptional photography services that capture the essence of every moment, delivering timeless memories that our clients will cherish forever.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4><i class="fas fa-eye text-success"></i> Our Vision</h4>
                            <p>To become the most trusted and innovative photography agency in the region, known for creativity, professionalism, and outstanding customer service.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <h4 class="mb-3">Why Choose Us?</h4>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-camera-retro fa-3x text-primary mb-3"></i>
                            <h5>Professional Equipment</h5>
                            <p>State-of-the-art cameras and lighting equipment</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h5>Experienced Team</h5>
                            <p>Skilled photographers with years of experience</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x text-info mb-3"></i>
                            <h5>Timely Delivery</h5>
                            <p>Quick turnaround without compromising quality</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Our Team</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <img src="assets/images/team1.jpg" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                            <h6>John Doe</h6>
                            <p class="text-muted">Lead Photographer</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <img src="assets/images/team2.jpg" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                            <h6>Jane Smith</h6>
                            <p class="text-muted">Wedding Specialist</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <img src="assets/images/team3.jpg" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                            <h6>Mike Johnson</h6>
                            <p class="text-muted">Candid Photographer</p>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <img src="assets/images/team4.jpg" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;">
                            <h6>Sarah Williams</h6>
                            <p class="text-muted">Creative Director</p>
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