<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// If already seen, skip directly to dashboard
if (isset($_SESSION['seen_welcome']) && $_SESSION['seen_welcome'] === true) {
    header('Location: index.php');
    exit;
}


?>
<!DOCTYPE html>
<html lang="en">    
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/> 
    <title>Welcome - Scholarship Finder</title>

   

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
    :root{
        --accent: #7c3aed;
        --accent-light: #ece7ff;
        --text-dark: #1f2937;
        --text-muted: #4b5563;
        --glass: rgba(255, 255, 255, 0.55);
        --radius: 20px;
    }

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body{
        font-family: "Poppins", system-ui, -apple-system;
        background-image: url("G1352603244.webp");
        background-size: cover;
        background-position: center;
        background-attachment: fixed; 
        color: var(--text-dark);
        padding: 45px 20px;
        min-height: 100vh; 
        position: relative; 
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
   
    body, .container {
        animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .container{
        max-width: 1000px;
        margin: 70px auto;
        padding: 45px;
        background: var(--glass);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border-radius: var(--radius);
        box-shadow: 
            0 15px 35px rgba(0,0,0,0.15),
            inset 0 0 35px rgba(255,255,255,0.25);
        position: relative;
        overflow: hidden;
    }

    
    .container::before{
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 2px;
        background: linear-gradient(140deg, rgba(255,255,255,0.7), rgba(255,255,255,0.1));
        -webkit-mask: 
            linear-gradient(#fff 0 0) content-box, 
            linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .content-layout{
        display: flex;
        gap: 35px;
        align-items: stretch;
        animation: slideUp 0.9s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media(max-width: 768px){
        .container { padding: 30px; }
        .content-layout { flex-direction: column; }
    }

    h1{
        color: var(--accent);
        font-size: 2.3rem;
        margin-bottom: 14px;
        font-weight: 650;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    p{
        margin-bottom: 18px;
        font-size: 1.08rem;
        color: var(--text-muted);
        line-height: 1.6;
    }

    ul{
        padding-left: 22px;
        margin-bottom: 22px;
        color: var(--text-muted);
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .info-card{
        flex: 0 0 360px;
        background: rgba(255,255,255,0.45);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 22px;
        border-radius: var(--radius);
        box-shadow: 0 10px 25px rgba(124,58,237,0.25);
        border: 1px solid rgba(255,255,255,0.4);
        animation: floatCard 3.5s ease-in-out infinite alternate;
    }

    
    @keyframes floatCard {
        from { transform: translateY(0px); }
        to { transform: translateY(-8px); }
    }

    .info-card h3{
        color: var(--accent);
        margin-bottom: 14px;
        font-size: 1.3rem;
        font-weight: 600;
    }

    .info-card ol{
        padding-left: 22px;
        color: var(--text-dark);
        font-size: 1.05rem;
        line-height: 1.7;
    }

    .cta{
        display: inline-block;
        padding: 16px 30px;
        background: var(--accent);
        color: #fff;
        border-radius: 12px;
        text-decoration: none;
        font-size: 1.1rem;
        font-weight: 600;
        box-shadow: 0 10px 25px rgba(124,58,237,0.45);
        transition: 0.25s ease;
    }

    .cta:hover{
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 15px 30px rgba(124,58,237,0.55);
    }
</style>

</head>
<body>

    <div class="container">
        <section class="content-layout">

            <div style="flex:1;min-width:320px">
                <h1 style="margin:0 0 12px;color:#4c1d95">Welcome to Scholarship Finder</h1>
                <p style="margin:0 0 18px;color:#374151;font-size:1.05rem;">
                    Scholarship Finder helps you discover, track, and apply to scholarships tailored to your profile.
                </p>

                <ul style="margin:0 0 20px;padding-left:20px;color:#374151;line-height:1.6">
                    <li>Personalized scholarship matches</li>
                    <li>Save and track opportunities</li>
                    <li>One-click application starter</li>
                </ul>

               
                <a href="transition.php" class="cta">Get Started</a> 
            </div>

            <div style="flex:0 0 360px;min-width:240px;background:#faf9ff;padding:18px;border-radius:12px;">
                <h3 style="margin:0;color:#7c3aed">How it Works</h3>
                <ol style="padding-left:18px;color:#374151;">
                    <li>Create profile and preferences</li>
                    <li>Search for scholarships</li>
                    <li>Navigate dashboard</li>
                    <li>View requirements</li>
                </ol>
            </div>

        </section>

        
    </div>
</body>
</html>