<?php
// Main dashboard page
include 'includes/config.php';
include 'includes/auth.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Finder</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-content">
                <h1>Scholarship Finder</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($username); ?></span>
                    <a href="logout.php" class="btn-logout">Logout</a>
                </div>
            </div>
        </header>
        
        <!-- Navigation -->
        <nav class="nav">
            <button class="nav-btn active" data-section="scholarships">Scholarships</button>
            <button class="nav-btn" data-section="bookmarks">My Bookmarks</button>
        </nav>
        
        <!-- Search and Filter Section -->
        <section class="search-section">
            <h2>Find Scholarships</h2>
            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search by title or provider...">
                
                <select id="levelFilter">
                    <option value="">All Levels</option>
                </select>
                
                <select id="fieldFilter">
                    <option value="">All Fields</option>
                </select>
                
                <input type="date" id="deadlineFilter" placeholder="Deadline">
                
                <div class="sort-buttons">
                    <button id="sortDeadline" class="sort-btn">Sort by Deadline</button>
                    <button id="sortAmount" class="sort-btn">Sort by Amount</button>
                </div>
                
                <button id="resetBtn" class="btn-reset">Reset</button>
            </div>
        </section>
        
        <!-- Content Section -->
        <main class="main-content">
            <!-- Scholarships Section -->
            <section id="scholarships" class="content-section active">
                <h2>Available Scholarships</h2>
                <div id="scholarshipsList" class="scholarships-grid">
                    <!-- Loaded dynamically -->
                </div>
            </section>
            
            <!-- Bookmarks Section -->
            <section id="bookmarks" class="content-section">
                <h2>My Bookmarked Scholarships</h2>
                <div id="bookmarksList" class="scholarships-grid">
                    <!-- Loaded dynamically -->
                </div>
            </section>
        </main>
    </div>
    
    <!-- Scholarship Detail Modal -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="modalBody">
                <!-- Loaded dynamically -->
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
