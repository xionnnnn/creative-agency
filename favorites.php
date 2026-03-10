<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user's favorites
$favorites_sql = "SELECT p.* FROM favorites f 
                  JOIN packages p ON f.package_id = p.package_id 
                  WHERE f.user_id = $user_id 
                  ORDER BY f.created_at DESC";
$favorites_result = $conn->query($favorites_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Favorites - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-9">
            <h2 class="mb-4"><i class="fas fa-heart text-danger"></i> My Favorite Packages</h2>
            
            <?php if ($favorites_result->num_rows > 0): ?>
                <div class="row">
                    <?php while($fav = $favorites_result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo $fav['package_image'] ?: 'assets/images/placeholder.jpg'; ?>" 
                                     class="card-img-top" alt="<?php echo $fav['package_name']; ?>" 
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($fav['package_name']); ?></h5>
                                    <p class="card-text"><?php echo substr(htmlspecialchars($fav['description']), 0, 100); ?>...</p>
                                    <p class="text-primary fw-bold">₱<?php echo number_format($fav['price'], 2); ?></p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <a href="inquire.php?package_id=<?php echo $fav['package_id']; ?>" 
                                       class="btn btn-primary btn-sm">Inquire</a>
                                    <button class="btn btn-outline-danger btn-sm remove-favorite" 
                                            data-package-id="<?php echo $fav['package_id']; ?>">
                                        <i class="fas fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    <p class="mb-0">You haven't added any packages to your favorites yet.</p>
                    <a href="index.php" class="btn btn-primary mt-3">Browse Packages</a>
                </div>
            <?php endif; ?>
        </div>
        
        <?php include 'includes/sidebar.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
$('.remove-favorite').click(function() {
    var packageId = $(this).data('package-id');
    var card = $(this).closest('.col-md-4');
    
    if (confirm('Remove this package from favorites?')) {
        $.ajax({
            url: 'favorite_handler.php',
            method: 'POST',
            data: { package_id: packageId, remove: true },
            success: function(response) {
                card.fadeOut();
            }
        });
    }
});
</script>
</body>
</html>