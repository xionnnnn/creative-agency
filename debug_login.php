<?php
require_once 'includes/db.php';

echo "<h2>Login Debug Tool</h2>";

// Check if tables exist
$tables = ['tbl_admin', 'tbl_users'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows > 0) {
        echo "<p style='color:green'>✓ Table '$table' exists</p>";
    } else {
        echo "<p style='color:red'>✗ Table '$table' does NOT exist</p>";
    }
}

// Show admin users
echo "<h3>Admin Users:</h3>";
$admin_sql = "SELECT admin_id, admin_name, admin_email, admin_password FROM tbl_admin";
$admin_result = $conn->query($admin_sql);

if ($admin_result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Password Hash (first 20 chars)</th></tr>";
    while($row = $admin_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['admin_id'] . "</td>";
        echo "<td>" . $row['admin_name'] . "</td>";
        echo "<td>" . $row['admin_email'] . "</td>";
        echo "<td>" . substr($row['admin_password'], 0, 20) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>No admin users found!</p>";
}

// Show regular users
echo "<h3>Regular Users:</h3>";
$user_sql = "SELECT user_id, user_fullname, user_email, user_password, user_status FROM tbl_users";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows > 0) {
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Password Hash (first 20 chars)</th></tr>";
    while($row = $user_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['user_id'] . "</td>";
        echo "<td>" . $row['user_fullname'] . "</td>";
        echo "<td>" . $row['user_email'] . "</td>";
        echo "<td>" . $row['user_status'] . "</td>";
        echo "<td>" . substr($row['user_password'], 0, 20) . "...</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:red'>No regular users found!</p>";
}

// Test password verification
echo "<h3>Test Password Verification:</h3>";

if (isset($_POST['test_email']) && isset($_POST['test_password'])) {
    $test_email = $conn->real_escape_string($_POST['test_email']);
    $test_password = $_POST['test_password'];
    
    echo "<p>Testing login for: <strong>$test_email</strong></p>";
    
    // Check admin
    $admin_test = $conn->query("SELECT * FROM tbl_admin WHERE admin_email = '$test_email'");
    if ($admin_test->num_rows > 0) {
        $admin = $admin_test->fetch_assoc();
        if (password_verify($test_password, $admin['admin_password'])) {
            echo "<p style='color:green'>✓ Admin login SUCCESSFUL!</p>";
        } else {
            echo "<p style='color:red'>✗ Admin login FAILED - Wrong password</p>";
            // Show what the hash should verify to
            echo "<p>Hash in DB: " . $admin['admin_password'] . "</p>";
        }
    }
    
    // Check user
    $user_test = $conn->query("SELECT * FROM tbl_users WHERE user_email = '$test_email' AND user_status = 'active'");
    if ($user_test->num_rows > 0) {
        $user = $user_test->fetch_assoc();
        if (password_verify($test_password, $user['user_password'])) {
            echo "<p style='color:green'>✓ User login SUCCESSFUL!</p>";
        } else {
            echo "<p style='color:red'>✗ User login FAILED - Wrong password</p>";
            echo "<p>Hash in DB: " . $user['user_password'] . "</p>";
        }
    }
    
    if ($admin_test->num_rows == 0 && $user_test->num_rows == 0) {
        echo "<p style='color:red'>✗ Email not found in either table!</p>";
    }
}

// Test form
?>
<form method="POST" style="margin-top:20px; padding:20px; border:1px solid #ccc;">
    <h4>Test Login Credentials:</h4>
    <div>
        <label>Email:</label>
        <input type="email" name="test_email" required>
    </div>
    <div style="margin-top:10px;">
        <label>Password:</label>
        <input type="text" name="test_password" required>
    </div>
    <button type="submit" style="margin-top:10px;">Test Login</button>
</form>

<?php
// Show PHP version and password_hash info
echo "<h3>System Info:</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Password Hash Algorithm: " . PASSWORD_DEFAULT . "<br>";

// Generate a test hash
$test_hash = password_hash('admin123', PASSWORD_DEFAULT);
echo "<p>Test hash for 'admin123': " . $test_hash . "</p>";
?>