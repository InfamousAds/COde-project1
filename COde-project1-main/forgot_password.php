<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forgot Password</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    
    :root {
        --accent: #7c3aed;          
        --accent-hover: #6d28d9;     
        --text-dark: #1f2937;
        --text-muted: #6b7280;
        --white-glass: rgba(255, 255, 255, 0.70); 
        --border-glass: rgba(255, 255, 255, 0.5);
        --radius: 12px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
      
        background-image: url("G1352603244.webp");
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
       
        min-height: 100vh;
        
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        transition: 0.3s;
    }

    
    body::before {
        content: '';
        position: fixed; 
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        
        background-color: rgba(255, 255, 255, 0.35); 
        z-index: -1; 
    }
    

    .auth-box {
        width: 350px;
       
        background: var(--white-glass);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        padding: 35px;
        border-radius: var(--radius);
        box-shadow: 0 8px 30px rgba(124, 58, 237, 0.25);
        border: 1px solid var(--border-glass);
       
        text-align: center;
    }

    h1 { 
        margin-bottom: 10px; 
        color: var(--accent); 
        font-weight: 700;
    }

    p { 
        color: var(--text-muted); 
        font-size: 14px; 
        margin-bottom: 20px; 
    }

    input {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        margin-bottom: 15px;
        background: #fff;
        color: var(--text-dark);
        font-size: 14px;
        transition: 0.2s;
    }

    .btn {
        width: 100%;
        padding: 12px;
        border-radius: 8px;
        border: none;
        background: var(--accent); 
        color: white;
        font-size: 16px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
    }

    .btn:hover {
        background: var(--accent-hover);
        transform: translateY(-1px);
    }

    .auth-link a { 
        color: var(--accent); 
        text-decoration: none; 
        font-weight: 600;
    }
    
    .auth-link a:hover {
        text-decoration: underline;
    }
    
    .auth-link { 
        margin-top: 15px; 
        color: var(--text-muted); 
    }

    input:focus {
        border-color: var(--accent); 
        outline: none;
        box-shadow: 0 0 0 4px rgba(124, 58, 237, 0.1);
        background: #fff;
    }
</style>

</head>
<body>

<div class="auth-box" role="region" aria-label="Forgot password">
    <h1>Forgot Password</h1>
    <p>Enter your email address and we'll send you a reset link.</p>

    <form method="POST" action="reset_request.php">
        <input type="email" name="email" placeholder="Your Email" required>
        <button class="btn">Send Reset Link</button>
    </form>

    <p class="auth-link"><a href="login.php">Back to Login</a></p>
</div>

</body>
</html>