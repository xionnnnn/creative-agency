<?php
// Only show sidebar on specific pages
$show_sidebar = in_array(basename($_SERVER['PHP_SELF']), ['index.php', 'gallery.php', 'favorites.php']);
if (!$show_sidebar) return;
?>
<div class="col-md-3">
    <div class="sidebar">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Packages</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="wedding">Wedding</option>
                            <option value="birthday">Birthday</option>
                            <option value="candid">Candid</option>
                            <option value="debut">Debut</option>
                            <option value="prewedding">Pre-wedding</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Max Price</label>
                        <input type="number" name="max_price" class="form-control" placeholder="Enter max price">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Apply Filters
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-star"></i> Popular Packages</h5>
            </div>
            <div class="card-body">
                <?php
                $popular_query = "SELECT package_id, package_name, price FROM packages ORDER BY package_id DESC LIMIT 5";
                $popular_result = $conn->query($popular_query);
                if ($popular_result->num_rows > 0):
                    while($pop = $popular_result->fetch_assoc()):
                ?>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <a href="inquire.php?package_id=<?php echo $pop['package_id']; ?>" class="text-decoration-none">
                        <?php echo htmlspecialchars($pop['package_name']); ?>
                    </a>
                    <span class="badge bg-info">₱<?php echo number_format($pop['price'], 2); ?></span>
                </div>
                <?php 
                    endwhile;
                endif;
                ?>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-clock"></i> Office Hours</h5>
            </div>
            <div class="card-body">
                <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p>
                <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p>
                <p><strong>Sunday:</strong> Closed</p>
                <hr>
                <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                <p><i class="fas fa-envelope"></i> info@lensagency.com</p>
            </div>
        </div>
    </div>
</div>