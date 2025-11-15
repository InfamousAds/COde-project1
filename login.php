<?php
// Login page
include 'includes/config.php';
include 'includes/auth.php';

$message = '';

// Handle login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $result = loginUser($username, $password, $conn);
    if ($result['success']) {
        header('Location: index.php');
        exit;
    } else {
        $message = $result['message'];
    }
}

// Check for registration message
if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $message = 'Registration successful! Please login.';
}

// If already logged in, redirect
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scholarship Finder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Login</h1>
            <?php if (!empty($message)): ?>
                <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn">Login</button>
            </form>
            
            <p class="auth-link">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>
</html>
