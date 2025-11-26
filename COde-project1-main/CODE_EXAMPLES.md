# Code Examples

## Quick Sort Implementation

### Sorting by Deadline (Ascending)

```php
// Quick Sort by deadline - earliest first
public static function sortByDeadline(&$scholarships, $low = 0, $high = null) {
    if ($high === null) {
        $high = count($scholarships) - 1;
    }
    
    if ($low < $high) {
        $pi = self::partitionDeadline($scholarships, $low, $high);
        self::sortByDeadline($scholarships, $low, $pi - 1);
        self::sortByDeadline($scholarships, $pi + 1, $high);
    }
    
    return $scholarships;
}

// Partition helper - splits by comparing dates
private static function partitionDeadline(&$arr, $low, $high) {
    $pivot = strtotime($arr[$high]['deadline']);
    $i = $low - 1;
    
    for ($j = $low; $j < $high; $j++) {
        if (strtotime($arr[$j]['deadline']) < $pivot) {
            $i++;
            // Swap elements
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }
    
    // Place pivot in final position
    $temp = $arr[$i + 1];
    $arr[$i + 1] = $arr[$high];
    $arr[$high] = $temp;
    
    return $i + 1;
}
```

### Sorting by Amount (Descending)

```php
// Quick Sort by amount - highest first
public static function sortByAmount(&$scholarships, $low = 0, $high = null) {
    if ($high === null) {
        $high = count($scholarships) - 1;
    }
    
    if ($low < $high) {
        $pi = self::partitionAmount($scholarships, $low, $high);
        self::sortByAmount($scholarships, $low, $pi - 1);
        self::sortByAmount($scholarships, $pi + 1, $high);
    }
    
    return $scholarships;
}

// Partition helper - descending order (highest first)
private static function partitionAmount(&$arr, $low, $high) {
    $pivot = $arr[$high]['amount'];
    $i = $low - 1;
    
    for ($j = $low; $j < $high; $j++) {
        if ($arr[$j]['amount'] > $pivot) {  // Note: > for descending
            $i++;
            // Swap elements
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }
    }
    
    // Place pivot in final position
    $temp = $arr[$i + 1];
    $arr[$i + 1] = $arr[$high];
    $arr[$high] = $temp;
    
    return $i + 1;
}
```

## Usage Examples

### Sorting Scholarships by Amount (Philippine Peso)
```php
// Create sorter instance
$sorter = new ScholarshipSorter();

// Sort by deadline (ascending - earliest deadline first)
$sorted_by_deadline = $sorter->sortByDeadline($scholarships);
// Result: [₱125,000, ₱150,000, ₱250,000, ...]

// Sort by amount (descending - highest amount first)
$sorted_by_amount = $sorter->sortByAmount($scholarships);
// Result: [₱400,000, ₱375,000, ₱300,000, ...]
```

## Tree Structure Implementation

### Building Scholarship Tree

```php
// Create tree
$tree = new ScholarshipTree();

// Add scholarships to tree (Level -> Field -> Type)
foreach ($scholarships as $scholarship) {
    $tree->addScholarship($scholarship);
}

// Get scholarships by level
$bachelor_scholarships = $tree->getByLevelAndField('Bachelor');

// Get scholarships by level and field
$bachelor_engineering = $tree->getByLevelAndField('Bachelor', 'Engineering');

// Get all levels
$levels = $tree->getLevels();  // ['Bachelor', 'Master', 'PhD']

// Get fields for a level
$fields = $tree->getFieldsByLevel('Bachelor');  // ['Engineering', 'Business', ...]
```

## Authentication Examples

### User Registration
```php
// Register new user
$result = registerUser('john_doe', 'john@example.com', 'password123', $conn);

if ($result['success']) {
    echo "Registration successful";
} else {
    echo "Error: " . $result['message'];
}
```

### User Login
```php
// Login user
$result = loginUser('john_doe', 'password123', $conn);

if ($result['success']) {
    // Session is automatically set
    $_SESSION['user_id'];      // User ID
    $_SESSION['username'];     // Username
    echo "Logged in successfully";
} else {
    echo "Error: " . $result['message'];
}
```

### Check Login Status
```php
// Check if user is logged in
if (isLoggedIn()) {
    $user_id = getCurrentUserId();
    echo "User $user_id is logged in";
} else {
    echo "User is not logged in";
}
```

## API Usage Examples

### JavaScript Fetch Examples

### Get All Scholarships
```javascript
fetch('includes/api.php?action=get_all')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.data);  // Array of scholarships
        }
    });
```

### Search Scholarships
```javascript
// Search with filters
const params = new URLSearchParams();
params.append('level', 'Bachelor');
params.append('field', 'Engineering');

fetch(`includes/api.php?action=search&${params}`)
    .then(response => response.json())
    .then(data => {
        console.log(data.data);  // Filtered scholarships
    });
```

### Sort by Deadline
```javascript
fetch('includes/api.php?action=sort_deadline')
    .then(response => response.json())
    .then(data => {
        // Data sorted by deadline (earliest first)
        console.log(data.data);
    });
```

### Add Bookmark
```javascript
const formData = new FormData();
formData.append('scholarship_id', 123);

fetch('includes/api.php?action=add_bookmark', {
    method: 'POST',
    body: formData
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('Bookmarked!');
    }
});
```

### Get Bookmarks
```javascript
fetch('includes/api.php?action=get_bookmarks')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.data);  // Array of bookmarked scholarships
        }
    });
```

## Database Query Examples

### Get scholarships for specific level
```php
$level = 'Bachelor';
$sql = "SELECT * FROM scholarships WHERE education_level = ? ORDER BY deadline ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $level);
$stmt->execute();
$result = $stmt->get_result();
```

### Search with multiple filters
```php
$sql = "SELECT * FROM scholarships WHERE 1=1";

if (!empty($level)) {
    $sql .= " AND education_level = ?";
}

if (!empty($field)) {
    $sql .= " AND field = ?";
}

if (!empty($deadline)) {
    $sql .= " AND deadline <= ?";
}

// Execute with prepared statement
```

## HTML/CSS Examples

### Scholarship Card Structure
```html
<div class="scholarship-card">
    <h3>Scholarship Title</h3>
    <div class="scholarship-info">
        <p><span>Provider:</span> Organization Name</p>
        <p><span>Level:</span> Bachelor</p>
        <p><span>Field:</span> Engineering</p>
    </div>
    <div class="scholarship-amount">$5,000</div>
    <div class="scholarship-deadline">Deadline: 12/31/2025</div>
    <div class="scholarship-actions">
        <button class="btn">Details</button>
        <button class="btn btn-bookmark">Bookmark</button>
    </div>
</div>
```

## Performance Notes

- Quick Sort has O(n log n) average time complexity
- Tree structure enables O(1) level/field lookups
- Database indexes improve query performance
- Prepared statements prevent SQL injection
- Sessions reduce database queries for auth
