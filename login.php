<?php
require_once 'includes/db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id']) || isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    
    // Check if user exists in users table
    $user_sql = "SELECT * FROM tbl_users WHERE user_email = '$email' AND user_status = 'active'";
    $user_result = $conn->query($user_sql);
    
    if ($user_result->num_rows == 1) {
        $user = $user_result->fetch_assoc();
        if (password_verify($password, $user['user_password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_fullname'] = $user['user_fullname'];
            $_SESSION['user_email'] = $user['user_email'];
            
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid email or password!";
        }
    } 
    // Check if user exists in admin table
    else {
        $admin_sql = "SELECT * FROM tbl_admin WHERE admin_email = '$email'";
        $admin_result = $conn->query($admin_sql);
        
        if ($admin_result->num_rows == 1) {
            $admin = $admin_result->fetch_assoc();
            if (password_verify($password, $admin['admin_password'])) {
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_name'] = $admin['admin_name'];
                $_SESSION['admin_email'] = $admin['admin_email'];
                
                // Update last login
                $update_sql = "UPDATE tbl_admin SET admin_last_login = NOW() WHERE admin_id = " . $admin['admin_id'];
                $conn->query($update_sql);
                
                header("Location: admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lens Creative Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px;
        }
        .login-header h4 {
            margin: 0;
            font-weight: 600;
        }
        .login-body {
            padding: 30px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .demo-credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
        .demo-credentials p {
            margin-bottom: 5px;
            color: #6c757d;
        }
        .brand-logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .brand-logo i {
            font-size: 50px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="col-md-5">
            <div class="card login-card">
                <div class="login-header text-center">
                    <h4><i class="fas fa-camera me-2"></i>Lens Creative Agency</h4>
                    <p class="mb-0 small">Sign in to your account</p>
                </div>
                <div class="login-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="Enter your email" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>Password
                            </label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Enter your password" required>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        
                        <button type="submit" class="btn btn-login w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-2">Don't have an account? <a href="signup.php" class="text-decoration-none">Sign up here</a></p>
                            <p><a href="forgot_password.php" class="text-decoration-none">Forgot Password?</a></p>
                        </div>
                    </form>
                    
                    <div class="demo-credentials">
                        <h6 class="text-center mb-3"><i class="fas fa-info-circle me-2"></i>Demo Credentials</h6>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><strong>Admin:</strong></p>
                                <p class="mb-1 small">Email: admin@lensagency.com</p>
                                <p class="mb-0 small">Password: admin123</p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>User:</strong></p>
                                <p class="mb-1 small">Email: john@email.com</p>
                                <p class="mb-0 small">Password: user123</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center bg-white border-0 pb-3">
                    <a href="index.php" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>