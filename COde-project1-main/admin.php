<?php
// Admin dashboard page
include 'includes/config.php';
include 'includes/auth.php';

// Check if user is admin (for now, hardcoded - user_id 1)
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = getCurrentUserId();
$username = $_SESSION['username'];

// For demo: make user 1 admin, or check in a separate admin table
// In production, would check admin table
$is_admin = ($user_id == 1);

if (!$is_admin) {
    echo "Access denied. Admin only.";
    exit;
}

// Handle add scholarship
$add_success = '';
$add_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = $_POST['title'] ?? '';
    $provider = $_POST['provider'] ?? '';
    $level = $_POST['education_level'] ?? '';
    $field = $_POST['field'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $eligibility = $_POST['eligibility'] ?? '';
    $link = $_POST['application_link'] ?? '';
    $type = $_POST['scholarship_type'] ?? '';
    
    if (empty($title) || empty($provider) || empty($level) || empty($field) || empty($amount) || empty($deadline)) {
        $add_error = 'All fields required';
    } else {
        $sql = "INSERT INTO scholarships (title, provider, education_level, field, amount, deadline, eligibility, application_link, scholarship_type) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssssdssss", $title, $provider, $level, $field, $amount, $deadline, $eligibility, $link, $type);
            
            if ($stmt->execute()) {
                $add_success = 'Scholarship added successfully!';
            } else {
                $add_error = 'Error adding scholarship: ' . $stmt->error;
            }
        }
    }
}

// Handle edit scholarship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit') {
    $id = $_POST['scholarship_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $provider = $_POST['provider'] ?? '';
    $level = $_POST['education_level'] ?? '';
    $field = $_POST['field'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $eligibility = $_POST['eligibility'] ?? '';
    $link = $_POST['application_link'] ?? '';
    $type = $_POST['scholarship_type'] ?? '';
    
    if (empty($id) || empty($title)) {
        $add_error = 'ID and title required';
    } else {
        $sql = "UPDATE scholarships SET title=?, provider=?, education_level=?, field=?, amount=?, deadline=?, eligibility=?, application_link=?, scholarship_type=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("ssssdssssi", $title, $provider, $level, $field, $amount, $deadline, $eligibility, $link, $type, $id);
            
            if ($stmt->execute()) {
                $add_success = 'Scholarship updated successfully!';
            } else {
                $add_error = 'Error updating scholarship: ' . $stmt->error;
            }
        }
    }
}

// Handle delete scholarship
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = $_POST['scholarship_id'] ?? '';
    
    if (empty($id)) {
        $add_error = 'Scholarship ID required';
    } else {
        $sql = "DELETE FROM scholarships WHERE id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $add_success = 'Scholarship deleted successfully!';
            } else {
                $add_error = 'Error deleting scholarship: ' . $stmt->error;
            }
        }
    }
}

// Get all scholarships for display
$sql = "SELECT * FROM scholarships ORDER BY deadline ASC";
$result = $conn->query($sql);
$scholarships = [];

while ($row = $result->fetch_assoc()) {
    $scholarships[] = $row;
}

// Get all users
$sql_users = "SELECT COUNT(*) as user_count FROM users";
$result_users = $conn->query($sql_users);
$user_count_row = $result_users->fetch_assoc();
$user_count = $user_count_row['user_count'];

// Get all bookmarks
$sql_bookmarks = "SELECT COUNT(*) as bookmark_count FROM bookmarks";
$result_bookmarks = $conn->query($sql_bookmarks);
$bookmark_count_row = $result_bookmarks->fetch_assoc();
$bookmark_count = $bookmark_count_row['bookmark_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Scholarship Finder</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #3498db;
        }
        
        .stat-card h3 {
            margin: 0;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .stat-card .number {
            font-size: 32px;
            color: #3498db;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .admin-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ecf0f1;
        }
        
        .admin-tab {
            padding: 12px 20px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
            color: #7f8c8d;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .admin-tab.active {
            color: #3498db;
            border-bottom-color: #3498db;
        }
        
        .admin-tab:hover {
            color: #3498db;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .form-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
        }
        
        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .form-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn-submit {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-submit:hover {
            background: #2980b9;
        }
        
        .scholarships-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .scholarships-table thead {
            background: #34495e;
            color: white;
        }
        
        .scholarships-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }
        
        .scholarships-table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .scholarships-table tr:hover {
            background: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .btn-edit {
            background: #f39c12;
            color: white;
        }
        
        .btn-edit:hover {
            background: #e67e22;
        }
        
        .btn-delete {
            background: #e74c3c;
            color: white;
        }
        
        .btn-delete:hover {
            background: #c0392b;
        }
        
        .message {
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 14px;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }
        
        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #7f8c8d;
        }
        
        .close:hover {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Welcome, <?php echo htmlspecialchars($username); ?></p>
            </div>
            <div>
                <a href="index.php" class="btn" style="margin-right: 10px;">Back to App</a>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        
        <!-- Statistics -->
        <div class="admin-stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo $user_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Scholarships</h3>
                <div class="number"><?php echo count($scholarships); ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Bookmarks</h3>
                <div class="number"><?php echo $bookmark_count; ?></div>
            </div>
        </div>
        
        <!-- Messages -->
        <?php if (!empty($add_success)): ?>
            <div class="message success">✓ <?php echo htmlspecialchars($add_success); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($add_error)): ?>
            <div class="message error">✗ <?php echo htmlspecialchars($add_error); ?></div>
        <?php endif; ?>
        
        <!-- Tabs -->
        <div class="admin-tabs">
            <button class="admin-tab active" onclick="switchTab('scholarships')">Manage Scholarships</button>
            <button class="admin-tab" onclick="switchTab('add')">Add New Scholarship</button>
        </div>
        
        <!-- Tab: Scholarships List -->
        <div id="scholarships" class="tab-content active">
            <div class="form-section">
                <h2>All Scholarships</h2>
                
                <?php if (count($scholarships) > 0): ?>
                    <table class="scholarships-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Provider</th>
                                <th>Level</th>
                                <th>Field</th>
                                <th>Amount (₱)</th>
                                <th>Deadline</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scholarships as $scholarship): ?>
                                <tr>
                                    <td><?php echo $scholarship['id']; ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['title']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['provider']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['education_level']); ?></td>
                                    <td><?php echo htmlspecialchars($scholarship['field']); ?></td>
                                    <td>₱<?php echo number_format($scholarship['amount'], 2); ?></td>
                                    <td><?php echo $scholarship['deadline']; ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-edit" onclick="editScholarship(<?php echo $scholarship['id']; ?>)">Edit</button>
                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this scholarship?');">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="scholarship_id" value="<?php echo $scholarship['id']; ?>">
                                                <button type="submit" class="btn-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No scholarships found.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Tab: Add New Scholarship -->
        <div id="add" class="tab-content">
            <div class="form-section">
                <h2>Add New Scholarship</h2>
                
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Title *</label>
                            <input type="text" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Provider *</label>
                            <input type="text" name="provider" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Education Level *</label>
                            <select name="education_level" required>
                                <option value="">Select level</option>
                                <option value="Bachelor">Bachelor</option>
                                <option value="Master">Master</option>
                                <option value="PhD">PhD</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Field *</label>
                            <select name="field" required>
                                <option value="">Select field</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Business">Business</option>
                                <option value="Science">Science</option>
                                <option value="Arts">Arts</option>
                                <option value="Medicine">Medicine</option>
                                <option value="Law">Law</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Amount (₱) *</label>
                            <input type="number" name="amount" step="1000" min="0" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Deadline *</label>
                            <input type="date" name="deadline" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Scholarship Type</label>
                            <select name="scholarship_type">
                                <option value="Merit-based">Merit-based</option>
                                <option value="Need-based">Need-based</option>
                                <option value="Sports">Sports</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Eligibility</label>
                        <textarea name="eligibility" placeholder="Enter eligibility requirements..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Application Link</label>
                        <input type="url" name="application_link" placeholder="https://example.com/apply">
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn-submit">Add Scholarship</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Scholarship</h2>
            
            <form method="POST" id="editForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="scholarship_id" id="edit_id">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" id="edit_title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Provider *</label>
                        <input type="text" name="provider" id="edit_provider" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Education Level *</label>
                        <select name="education_level" id="edit_level" required>
                            <option value="Bachelor">Bachelor</option>
                            <option value="Master">Master</option>
                            <option value="PhD">PhD</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Field *</label>
                        <select name="field" id="edit_field" required>
                            <option value="Engineering">Engineering</option>
                            <option value="Business">Business</option>
                            <option value="Science">Science</option>
                            <option value="Arts">Arts</option>
                            <option value="Medicine">Medicine</option>
                            <option value="Law">Law</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Amount (₱) *</label>
                        <input type="number" name="amount" id="edit_amount" step="1000" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Deadline *</label>
                        <input type="date" name="deadline" id="edit_deadline" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Scholarship Type</label>
                        <select name="scholarship_type" id="edit_type">
                            <option value="Merit-based">Merit-based</option>
                            <option value="Need-based">Need-based</option>
                            <option value="Sports">Sports</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Eligibility</label>
                    <textarea name="eligibility" id="edit_eligibility"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Application Link</label>
                    <input type="url" name="application_link" id="edit_link">
                </div>
                
                <div class="form-buttons">
                    <button type="submit" class="btn-submit">Update Scholarship</button>
                    <button type="button" class="btn-submit" style="background: #95a5a6;" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Switch tabs
        function switchTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active class from all buttons
            document.querySelectorAll('.admin-tab').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
        
        // Edit scholarship
        function editScholarship(id) {
            // Get scholarship data (simplified - in production would fetch via AJAX)
            const row = event.target.closest('tr');
            
            document.getElementById('edit_id').value = row.cells[0].textContent;
            document.getElementById('edit_title').value = row.cells[1].textContent;
            document.getElementById('edit_provider').value = row.cells[2].textContent;
            document.getElementById('edit_level').value = row.cells[3].textContent;
            document.getElementById('edit_field').value = row.cells[4].textContent;
            document.getElementById('edit_amount').value = row.cells[5].textContent.replace('₱', '').replace(/,/g, '');
            document.getElementById('edit_deadline').value = row.cells[6].textContent;
            
            document.getElementById('editModal').classList.add('show');
        }
        
        // Close modal
        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target === modal) {
                modal.classList.remove('show');
            }
        }
    </script>
</body>
</html>
