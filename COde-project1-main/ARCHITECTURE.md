# System Architecture & Data Flow

## Application Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    USER BROWSER (Frontend)                  │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ HTML5 Pages (index.php, login.php, register.php)    │  │
│  │ + CSS3 Styling (style.css)                          │  │
│  │ + JavaScript (script.js - Vanilla JS)               │  │
│  └──────────────────────────────────────────────────────┘  │
│                      ↓ HTTP/AJAX                             │
└─────────────────────────────────────────────────────────────┘
        ↓                                    ↑
    Form Data                           JSON Response
    Fetch Requests                      HTML Content

┌─────────────────────────────────────────────────────────────┐
│                    PHP SERVER (Backend)                      │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Routing Layer (index.php, login.php, etc.)          │  │
│  └──────────────────────────────────────────────────────┘  │
│                      ↓                                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Business Logic Layer                                 │  │
│  │ • auth.php (Authentication)                         │  │
│  │ • api.php (Endpoints)                               │  │
│  │ • scholarship_utils.php (Quick Sort & Tree)         │  │
│  └──────────────────────────────────────────────────────┘  │
│                      ↓                                       │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Data Access Layer                                    │  │
│  │ • config.php (DB connection)                        │  │
│  │ • Prepared Statements                               │  │
│  │ • Result Processing                                 │  │
│  └──────────────────────────────────────────────────────┘  │
│                      ↓                                       │
└─────────────────────────────────────────────────────────────┘
        ↓                                    ↑
    SQL Queries                        Query Results
    Transactions

┌─────────────────────────────────────────────────────────────┐
│                   MySQL Database                             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ users          scholarships       bookmarks          │  │
│  │ ┌──────────┐  ┌───────────────┐  ┌────────────────┐ │  │
│  │ │ id       │  │ id            │  │ id             │ │  │
│  │ │ username │  │ title         │  │ user_id (FK)   │ │  │
│  │ │ email    │  │ provider      │  │ scholarship_id │ │  │
│  │ │ password │  │ level         │  │ created_at     │ │  │
│  │ │ created  │  │ field         │  └────────────────┘ │  │
│  │ └──────────┘  │ amount        │                      │  │
│  │               │ deadline      │   Relationships:    │  │
│  │               │ eligibility   │   FK user_id →     │  │
│  │               │ app_link      │   users.id         │  │
│  │               │ type          │                     │  │
│  │               │ created       │   FK scholarship_id │  │
│  │               └───────────────┘   scholarships.id   │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## Data Flow Diagrams

### User Registration Flow

```
User Input (register.php form)
        ↓
Client-side Validation (JS)
        ↓
POST request to register.php
        ↓
Server receives data
        ↓
registerUser() in auth.php
        ├─ Validate inputs
        ├─ Check if user exists (DB query)
        ├─ Hash password (bcrypt)
        └─ Insert into users table
        ↓
Response: Success or Error message
        ↓
Redirect to login.php
```

### User Login Flow

```
User Input (login.php form)
        ↓
POST request to login.php
        ↓
loginUser() in auth.php
        ├─ Get user from DB
        ├─ Verify password
        └─ Create session
        ↓
Set $_SESSION['user_id'] and $_SESSION['username']
        ↓
Redirect to index.php
```

### Scholarship Search Flow

```
User interacts with filters (index.php)
        ↓
JavaScript event listener triggered
        ↓
AJAX fetch request to api.php?action=search
        ├─ Add parameters (level, field, deadline)
        └─ Send query string
        ↓
Server-side search in api.php
        ├─ Build SQL query dynamically
        ├─ Execute prepared statement
        └─ Get results from DB
        ↓
Return JSON response with scholarship data
        ↓
JavaScript receives response
        ├─ Parse JSON
        ├─ Apply client-side text filter
        └─ Display results
        ↓
Scholarships rendered on page
```

### Sorting Flow

```
User clicks "Sort by Deadline"
        ↓
AJAX fetch to api.php?action=sort_deadline
        ↓
Server fetches all scholarships
        ↓
Load into ScholarshipSorter class
        ↓
sortByDeadline() executes Quick Sort
        ├─ Partition by deadline
        ├─ Recursive sort left/right
        └─ Return sorted array
        ↓
Convert to JSON and send to client
        ↓
JavaScript displays sorted results
```

### Bookmark Flow

```
User clicks Bookmark button
        ↓
JavaScript checks if already bookmarked
        ├─ If yes → POST to remove_bookmark
        └─ If no → POST to add_bookmark
        ↓
Server-side bookmark handler in api.php
        ├─ Check user is logged in
        ├─ INSERT/DELETE from bookmarks table
        └─ Return success/error
        ↓
JavaScript updates UI
        ├─ Change button state
        ├─ Update button text
        └─ Refresh bookmarks count
```

## Quick Sort Algorithm Flow

```
Input: Array of scholarships to sort by deadline

sortByDeadline(scholarships, 0, n-1)
  ├─ If low < high:
  │   ├─ pi = partitionDeadline(...)
  │   ├─ sortByDeadline(scholarships, low, pi-1)  [Left]
  │   └─ sortByDeadline(scholarships, pi+1, high) [Right]
  └─ Return sorted array

partitionDeadline(arr, low, high)
  ├─ pivot = arr[high]['deadline']
  ├─ i = low - 1
  ├─ For each j from low to high-1:
  │   └─ If arr[j]['deadline'] < pivot:
  │       ├─ i++
  │       └─ Swap arr[i] and arr[j]
  ├─ Swap arr[i+1] and arr[high]
  └─ Return i+1

Output: Sorted array (earliest deadline first)
```

## Tree Structure Organization

```
Root
├── Bachelor (Level)
│   ├── Engineering (Field)
│   │   ├── Merit-based (Type)
│   │   │   └── [Scholarship objects]
│   │   └── Need-based (Type)
│   │       └── [Scholarship objects]
│   ├── Business (Field)
│   │   ├── Merit-based (Type)
│   │   │   └── [Scholarship objects]
│   │   └── ...
│   └── ...
├── Master (Level)
│   ├── Engineering (Field)
│   │   ├── Merit-based (Type)
│   │   │   └── [Scholarship objects]
│   │   └── ...
│   ├── Science (Field)
│   │   └── ...
│   └── ...
└── PhD (Level)
    └── ...
```

## API Request/Response Examples

### Request: Get All Scholarships
```
GET /includes/api.php?action=get_all
```

### Response: Get All Scholarships
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Engineering Excellence Award",
      "provider": "Tech Foundation",
      "education_level": "Bachelor",
      "field": "Engineering",
      "amount": "250000.00",
      "deadline": "2025-12-31",
      "eligibility": "GPA 3.5+, Engineering major",
      "application_link": "https://example.com/eng1",
      "scholarship_type": "Merit-based",
      "created_at": "2025-11-12..."
    },
    ...
  ]
}
```

### Request: Search Scholarships
```
GET /includes/api.php?action=search&level=Bachelor&field=Engineering
```

### Request: Add Bookmark
```
POST /includes/api.php?action=add_bookmark
Content-Type: application/x-www-form-urlencoded

scholarship_id=1
```

### Response: Add Bookmark
```json
{
  "success": true,
  "message": "Bookmarked successfully"
}
```

## Session Management

```
Login
  ↓
Create session
  ├─ $_SESSION['user_id'] = 123
  └─ $_SESSION['username'] = 'john_doe'
  ↓
Session cookie sent to browser
  └─ session_id in PHPSESSID
  ↓
Each request includes session_id
  ↓
Server validates session
  ├─ Check $_SESSION exists
  ├─ Verify user_id
  └─ Allow/deny access
  ↓
On logout: session_destroy()
  └─ Clear all session data
```

## Error Handling Flow

```
User Action
  ↓
Server Process
  ├─ Try: Execute logic
  └─ Catch: Error occurs
      ↓
      Generate error message
      ↓
      Return JSON response:
      {"success": false, "message": "Error details"}
      ↓
JavaScript handles response
      ├─ Check success flag
      ├─ If false: Display alert with message
      └─ If true: Update UI
```

## Database Connection Flow

```
Application Start
  ↓
Include config.php
  ├─ Define DB constants
  └─ Create mysqli connection
  ↓
Check connection
  ├─ If error: die() with message
  └─ If ok: Set charset to UTF-8
  ↓
Use connection for queries
  ├─ Prepare statement
  ├─ Bind parameters
  ├─ Execute
  └─ Get results
  ↓
Close connection (in api.php)
```

## Security Layers

```
User Input
  ↓
Client-side validation (JS)
  ├─ Check required fields
  ├─ Validate email format
  └─ Check password length
  ↓
Server-side validation (PHP)
  ├─ Validate data types
  ├─ Verify user session
  └─ Check authorization
  ↓
Prepared statements (MySQL)
  ├─ Parameterized queries
  ├─ Prevent SQL injection
  └─ Bind values safely
  ↓
Output encoding (PHP)
  ├─ HTML escape output
  └─ Prevent XSS
  ↓
Password security
  ├─ Hash with bcrypt
  ├─ Verify on login
  └─ Never store plain text
```

## Performance Optimization

```
Frontend Optimization
  ├─ Lazy load bookmarks
  ├─ Client-side text search
  └─ Vanilla JS (no libraries)

Backend Optimization
  ├─ Prepared statements
  ├─ Database indexing (FK)
  ├─ Efficient algorithms
  └─ Minimal queries

Algorithm Optimization
  ├─ Quick Sort (O(n log n))
  ├─ Tree lookups (O(1))
  └─ Partition in-place
```

---

**System is fully functional and optimized for typical scholarship discovery workloads.**
