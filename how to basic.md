

## Step 1: Database Setup

1. Open MySQL and create database:
   ```sql
   CREATE DATABASE scholarship_finder;
   ```
   database lang i create han kau ag araramid ti table automatic nga ma create day tables

2. open this link
   ```
   http://localhost/prxjjt/includes/setup_db.php
   ```

dapat ada agpakita nga user table created successfully

## Step 2: Create Account

1. Navigate to: `http://localhost/prxjjt/register.php`
2. Fill in registration form
3. Click Register

## Step 3: Login

1. Go to: `http://localhost/prxjjt/login.php`
2. Enter mo day inaramid mo nga acc
3. Click Login


## Default Database Config (icheck u dituy in case of error's kasla nga day error ni jim idi)

File: `includes/config.php`
```php
DB_HOST = localhost
DB_USER = root
DB_PASS = (empty)
DB_NAME = scholarship_finder
```



## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify credentials in `includes/config.php`
- Ensure `scholarship_finder` database exists

### Database Setup Page Error
- Make sure database exists first
- Check file permissions on `includes/setup_db.php`
- Clear browser cache and refresh

### Login Issues
- Verify you registered successfully
- Check database has users table
- Try registering a new account

### Bookmarks Not Showing
- Make sure you're logged in
- Check user_id in session
- Verify bookmarks table exists in database
