<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

// Fetch gallery images
$gallery_sql = "SELECT g.*, u.fullname as uploader_name 
                FROM gallery g 
                LEFT JOIN users u ON g.uploaded_by = u.user_id 
                ORDER BY g.created_at DESC";
$gallery_result = $conn->query($gallery_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <h2 class="mb-4">Our Gallery</h2>
            
            <!-- Category Filter -->
            <div class="mb-4">
                <button class="btn btn-outline-primary filter-btn active" data-filter="all">All</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="wedding">Wedding</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="birthday">Birthday</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="candid">Candid</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="debut">Debut</button>
                <button class="btn btn-outline-primary filter-btn" data-filter="prewedding">Pre-wedding</button>
            </div>
            
            <div class="row gallery-container">
                <?php if ($gallery_result->num_rows > 0): ?>
                    <?php while($image = $gallery_result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4 gallery-item" data-category="<?php echo strtolower($image['category']); ?>">
                            <div class="card">
                                <a href="<?php echo $image['image_path']; ?>" data-lightbox="gallery" data-title="<?php echo htmlspecialchars($image['title']); ?>">
                                    <img src="<?php echo $image['image_path']; ?>" class="card-img-top" alt="<?php echo $image['title']; ?>" style="height: 250px; object-fit: cover;">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($image['title']); ?></h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-tag"></i> <?php echo $image['category']; ?><br>
                                            <i class="fas fa-user"></i> Uploaded by: <?php echo $image['uploader_name'] ?: 'Admin'; ?>
                                        </small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No images in gallery yet.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <?php include 'includes/sidebar.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- Lightbox JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
// Gallery filter
$(document).ready(function() {
    $('.filter-btn').click(function() {
        var filter = $(this).data('filter');
        
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        if (filter == 'all') {
            $('.gallery-item').show();
        } else {
            $('.gallery-item').hide();
            $('.gallery-item[data-category="' + filter + '"]').show();
        }
    });
});
</script>
</body>
</html>