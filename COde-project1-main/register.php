<?php
// Register page
include 'includes/config.php';
include 'includes/auth.php';

$message = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate
    if ($password !== $confirm_password) {
        $message = 'Passwords do not match';
    } else {
        $result = registerUser($username, $email, $password, $conn);
        if ($result['success']) {
            header('Location: login.php?msg=registered');
            exit;
        } else {
            $message = $result['message'];
        }
    }
}


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
    <title>Register - Scholarship Finder</title>

    <style>
        :root{
            --bg: #f5f7fa;
            --card: #ffffff;
            --accent: #3b82f6;
            --muted: #6b7280;
        }

        *{box-sizing:border-box}
        body{
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
            background: var(--bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }
        
        body::before{
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("G1352603244.webp");
            background-size: cover;
            background-position: center;
            opacity: 0.2;
            pointer-events: none;
            z-index: 0;
        }

        .auth-box{
            width: 420px;
            max-width: 92vw;
            background: var(--card);
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(2,6,23,0.06);
            position: relative;
            z-index: 1;
        }

        h1{
            margin: 0 0 16px;
            font-size: 35px;
            color: #0f172a;
            text-align: center;
        }

        .message{
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 14px;
            text-align: center;
            font-weight: 500;
        }
        .error{ background:#fee2e2; color:#dc2626; }
        .success{ background:#d1fae5; color:#059669; }

        .form-group{
            margin-bottom: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label{
            font-size: 14px;
            color: #374151;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"]{
            width: 100%;
            padding: 12px 14px;
            height: 44px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 15px;
            color: #111827;
            background: #fff;
        }

        input:focus{
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 6px rgba(59,130,246,0.06);
        }

        .btn{
            width: 100%;
            height: 46px;
            padding: 0 16px;
            border: none;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .auth-link{
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            color: var(--muted);
        }
        .auth-link a{ color: var(--accent); text-decoration: none; font-weight: 600; }

        @media (max-width: 420px){
            .auth-box{ padding: 20px; }
            input{ height: 44px; font-size: 15px; }
            .btn{ height: 44px; }
        }
    </style>

</head>
<body>

<div class="auth-box">
    <h1>Registration</h1>

    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <button class="btn" type="submit">Register</button>
    </form>

    <p class="auth-link">
        Already have an account? <a href="login.php">Login</a>
    </p>
</div>

</body>
</html>