# Scholarship Finder System

A web-based scholarship discovery platform built with PHP and MySQL. Users can search, filter, sort, and bookmark scholarships.

## Features

### User Management
- User registration with email validation
- Secure login with password hashing (bcrypt)
- Session-based authentication
- Personalized dashboard

### Scholarship Management
- View all available scholarships
- Search scholarships by title or provider
- Filter by education level, field, and deadline
- Sort scholarships by deadline (earliest first) or amount (highest first)
- Bookmark scholarships for later reference
- View detailed scholarship information

### Backend Features
- **Quick Sort Algorithm**: Efficient sorting of scholarships by deadline or amount
- **Tree Structure**: Scholarships organized hierarchically (Level → Field → Type)
- **Database Schema**: Normalized tables for users, scholarships, and bookmarks
- **Prepared Statements**: SQL injection prevention
- **Password Hashing**: Secure bcrypt password storage

## System Architecture

### Database Schema

#### Users Table
- `id`: Primary key
- `username`: Unique username
- `email`: Unique email address
- `password`: Hashed password
- `created_at`: Registration timestamp

#### Scholarships Table
- `id`: Primary key
- `title`: Scholarship name
- `provider`: Organization providing scholarship
- `education_level`: Bachelor, Master, PhD, etc.
- `field`: Study field (Engineering, Business, etc.)
- `amount`: Scholarship amount in USD
- `deadline`: Application deadline date
- `eligibility`: Eligibility requirements
- `application_link`: External application URL
- `scholarship_type`: Merit-based, Need-based, etc.
- `created_at`: Record creation timestamp

#### Bookmarks Table
- `id`: Primary key
- `user_id`: Foreign key to users table
- `scholarship_id`: Foreign key to scholarships table
- `created_at`: Bookmark creation timestamp
- Unique constraint on (user_id, scholarship_id)

### File Structure

```
prxjjt/
├── index.php              # Main dashboard
├── login.php              # Login page
├── register.php           # Registration page
├── logout.php             # Logout handler
├── css/
│   └── style.css          # Main stylesheet
├── js/
│   └── script.js          # Frontend JavaScript
└── includes/
    ├── config.php         # Database config
    ├── setup_db.php       # Database initialization
    ├── auth.php           # Authentication functions
    ├── scholarship_utils.php  # Quick Sort & Tree structure
    └── api.php            # API endpoints
```

## Key Implementations

### Quick Sort Algorithm
Implemented in `scholarship_utils.php` using recursive partitioning:
- **Sort by Deadline**: Compares dates using `strtotime()` for ascending order
- **Sort by Amount**: Compares numeric values in descending order
- Time Complexity: O(n log n) average case
- Space Complexity: O(log n) for recursion stack

### Tree Structure
Organizes scholarships hierarchically:
```
Root
├── Bachelor
│   ├── Engineering
│   │   ├── Merit-based [scholarships array]
│   │   └── Need-based [scholarships array]
│   ├── Business
│   │   └── ...
│   └── ...
├── Master
│   └── ...
└── ...
```

Enables efficient filtering and hierarchical organization.

## Setup Instructions

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache recommended)

### Installation Steps

1. **Create Database**
   - Create a new MySQL database named `scholarship_finder`
   - Update database credentials in `includes/config.php` if needed

2. **Initialize Database**
   - Run `includes/setup_db.php` in your browser to create tables and insert sample data
   - URL: `http://localhost/prxjjt/includes/setup_db.php`

3. **Access Application**
   - Navigate to `http://localhost/prxjjt/`
   - Register a new account or login

### Database Configuration

Edit `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'scholarship_finder');
```

## Usage Guide

### Registration
1. Click "Register" on login page
2. Enter username, email, and password
3. Confirm password
4. Submit to create account

### Login
1. Enter username and password
2. Click "Login" to access dashboard

### Search & Filter
1. Use search box to find by title or provider
2. Select education level from dropdown
3. Select field of study from dropdown
4. Select deadline date to filter scholarships before that date
5. Click "Reset" to clear all filters

### Sorting
1. Click "Sort by Deadline" to arrange by earliest deadline first
2. Click "Sort by Amount" to arrange by highest amount first

### Bookmarking
1. Click "Bookmark" button on any scholarship card
2. View bookmarked scholarships in "My Bookmarks" tab
3. Click "Bookmarked" to remove from bookmarks

### View Details
1. Click "Details" on any scholarship card
2. View full information in modal popup
3. Click "Apply Now" to visit application link

## API Endpoints

### GET Endpoints
- `includes/api.php?action=get_all` - Get all scholarships
- `includes/api.php?action=search&level=...&field=...&deadline=...` - Search scholarships
- `includes/api.php?action=sort_deadline` - Sort by deadline
- `includes/api.php?action=sort_amount` - Sort by amount
- `includes/api.php?action=get_bookmarks` - Get user's bookmarks
- `includes/api.php?action=is_bookmarked&scholarship_id=...` - Check if bookmarked
- `includes/api.php?action=get_filters` - Get filter options

### POST Endpoints
- `includes/api.php?action=add_bookmark` - Add bookmark
- `includes/api.php?action=remove_bookmark` - Remove bookmark

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Security**: Prepared statements, bcrypt password hashing

## Security Features

- SQL injection prevention via prepared statements
- Password hashing using bcrypt
- Session-based authentication
- HTML escaping for XSS prevention
- CSRF-like protection through form handling

## Performance Considerations

- Quick Sort for efficient data sorting
- Tree structure for fast hierarchical lookup
- Database indexing on frequently queried columns
- Lazy-loading of bookmarks only when needed

## Sample Data

The system includes 6 sample scholarships covering:
- Engineering (Bachelor & Master levels)
- Business (Bachelor)
- Science (Master)
- Arts (Bachelor)

All with different amounts (₱125,000 - ₱400,000), deadlines, and eligibility requirements.

## Future Enhancements

- Admin panel for scholarship management
- Email notifications for upcoming deadlines
- Scholarship recommendations based on user profile
- Application tracking system
- Social sharing features
- Mobile app development
