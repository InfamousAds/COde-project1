<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$_SESSION['seen_welcome'] = true;


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>

    <style>
        body {
            margin:0;
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
            background:#ffffff;
            font-family:Arial, sans-serif;
            animation: fadeout 2s forwards;
        }
        @keyframes fadeout {
            0% { opacity:1; }
            100% { opacity:0; }
        }
    </style>

    <meta http-equiv="refresh" content="3; url=index.php"> 
</head>
<body>
   
    <h2 style="color:#7c3aed;">You will be redirected shortly</h2>
</body>
</html>