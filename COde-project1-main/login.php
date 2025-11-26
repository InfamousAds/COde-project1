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
        header('Location: welcomePage.php');
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
    header('Location: welcomePage.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Scholarship Finder</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
:root{
    --accent: #7c3aed;
    --accent-light: #ece7ff;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --glass: rgba(255,255,255,0.55);
    --radius: 20px;
}


*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body{
    height: 100%;
}


body{
    font-family: "Poppins", system-ui, -apple-system;
    background-image: url("G1352603244.webp");
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    color: var(--text-dark);
    animation: fadeIn 0.8s ease-out;
}


body::before{
    content: "";
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,0.10); 
    backdrop-filter: blur(2px); 
    z-index: -1;
}


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}


.container{
    max-width: 1100px;
    width: 100%;
    display: flex;
    gap: 40px;
}



.left{
    flex: 1;
    background: var(--glass);
    border-radius: var(--radius);
    padding: 50px 45px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start; 
    box-shadow: 
        0 20px 40px rgba(0,0,0,0.15),
        inset 0 0 35px rgba(255,255,255,0.25);
    animation: slideUp 0.9s ease-out;
    min-height: 520px; 
}


.left h2{
    font-size: 3.3rem;            
    color: var(--accent);
    font-weight: 700;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
    line-height: 1.2;
}


.left p{
    font-size: 1.35rem;           
    color: var(--text-dark);
    line-height: 1.7;
}



.auth-container{
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.auth-box{
    width: 100%;
    max-width: 480px;  
    padding: 50px 45px; 
    background: var(--glass);
    border-radius: var(--radius);
    backdrop-filter: blur(18px);
    box-shadow:
        0 25px 45px rgba(124,58,237,0.20),
        inset 0 0 35px rgba(255,255,255,0.25);
    border: 1px solid rgba(255,255,255,0.45);
}


.brand{
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 20px; 
}

.logo img{
    width: 65px;
    height: 65px;
    border-radius: 15px;
    object-fit: cover;
    box-shadow: 0 6px 14px rgba(0,0,0,0.25);
}

h1{
    margin: 0;
    font-size: 1.9rem;
    font-weight: 700;
    color: var(--accent);
}

.subtitle{
    font-size: 1rem;
    color: var(--text-muted);
    margin-top: 4px;
}


.message{
    padding: 12px;
    border-radius: 12px;
    font-size: 1rem;
    margin-bottom: 10px;
}

.message.success{
    background: rgba(124,58,237,0.08);
    color: var(--accent);
}

.message.error{
    background: rgba(220,38,38,0.1);
    color: #dc2626;
}


label{
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-muted);
}

.form-group{
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 5px;
}

input{
    padding: 14px;
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.12);
    font-size: 1rem;
    background: rgba(255,255,255,0.75);
    outline: none;
    transition: .2s;
}

input:focus{
    border-color: var(--accent);
    box-shadow: 0 12px 30px rgba(124,58,237,0.25);
    transform: translateY(-2px);
}


.forgot{
    font-size: .9rem;
    color: var(--text-muted);
    text-decoration: none;
}

.forgot:hover{
    color: var(--accent);
    text-decoration: underline;
}


.btn{
    width: 100%;
    padding: 16px;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 14px;
    font-size: 1.15rem;
    font-weight: 700;
    cursor: pointer;
    transition: .25s ease;
    box-shadow: 0 12px 30px rgba(124,58,237,0.35);
}

.btn:hover{
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(124,58,237,0.45);
}


.auth-link{
    font-size: 1rem;
    text-align: center;
    color: var(--text-muted);
    margin-top: 15px;
}

.auth-link a{
    color: var(--accent);
    font-weight: 700;
    text-decoration: none;
}

.auth-link a:hover{
    text-decoration: underline;
}


@media(max-width: 900px){
    .container{
        flex-direction: column;
        max-width: 500px;
    }
    .left{
        display: none;
    }
}
</style>

</head>

<body>

<div class="container">

    <div class="left">
        <h2>Welcome To Scholarship Finder!</h2>
        <p>Sign in to access your account and track scholarship opportunities tailored to you.</p>
    </div>

    <div class="auth-container">
        <div class="auth-box">

            <div class="brand">
                <div class="logo">
                    <img src="451e2ae8-5fb5-47db-8508-9ef6a65a2dd8.jpeg" alt="Scholarship Finder logo">
                </div>
                <div>
                    <h1>Scholarship Finder</h1>
                    <div class="subtitle">Sign in to your account</div>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="message <?php echo strpos($message, 'successful') !== false ? 'success' : 'error'; ?>">
                    <?= htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" novalidate>

                <div class="form-group">
                    <label for="username" class="highlight">Username</label>
                    <input type="text" id="username" name="username" required autofocus autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password" class="highlight">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <a class="forgot" href="forgot_password.php">Forgot password?</a>

                <button type="submit" class="btn">Login</button>

            </form>

            <p class="auth-link">Don't have an account? <a href="register.php">Register</a></p>

        </div>
    </div>
</div>

<script>
document.querySelectorAll("input").forEach(input => {
    input.addEventListener("focus", () => {
        const label = input.parentElement.querySelector("label");
        label.style.color = "#7c3aed";
        label.style.fontWeight = "700";
    });

    input.addEventListener("blur", () => {
        const label = input.parentElement.querySelector("label");
        label.style.color = "#6b7280";
        label.style.fontWeight = "600";
    });
});
</script>

</body>
</html>
