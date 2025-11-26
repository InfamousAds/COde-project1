# Testing Guide

## Pre-Testing Setup

1. Start Apache/PHP server
2. Start MySQL server
3. Navigate to: `http://localhost/prxjjt/includes/setup_db.php`
4. Verify all tables created successfully

## Test Cases

### Test 1: User Registration

**Steps:**
1. Go to `http://localhost/prxjjt/register.php`
2. Fill in form:
   - Username: test_user
   - Email: test@example.com
   - Password: password123
   - Confirm: password123
3. Click Register

**Expected Result:**
- Redirected to login page
- Message: "Registration successful! Please login."
- User data saved in database

**Test 1b: Duplicate Username**
- Try registering again with same username
- Expected: Error message "Username or email already exists"

**Test 1c: Password Mismatch**
- Register with mismatched passwords
- Expected: Error message "Passwords do not match"

---

### Test 2: User Login

**Steps:**
1. Go to `http://localhost/prxjjt/login.php`
2. Enter credentials:
   - Username: test_user
   - Password: password123
3. Click Login

**Expected Result:**
- Redirected to dashboard (index.php)
- Session created with user_id and username
- Welcome message shows username

**Test 2b: Wrong Password**
- Try login with incorrect password
- Expected: Error message "Invalid username or password"

**Test 2c: Nonexistent User**
- Try login with non-existent username
- Expected: Error message "Invalid username or password"

---

### Test 3: View All Scholarships

**Steps:**
1. Login successfully
2. Dashboard loads by default

**Expected Result:**
- 6 scholarship cards displayed
- Each card shows:
  - Title
  - Provider
  - Level
  - Field
  - Amount
  - Deadline
  - Buttons: Details, Bookmark

---

### Test 4: Search Functionality

**Test 4a: Search by Title**
- Type "Engineering" in search box
- Expected: Shows only "Engineering Excellence Award" and "Computer Science Scholars"

**Test 4b: Search by Provider**
- Type "Tech" in search box
- Expected: Shows all Tech Foundation scholarships

**Test 4c: Clear Search**
- Clear search box
- Expected: All 6 scholarships reappear

---

### Test 5: Filter by Education Level

**Steps:**
1. Select "Bachelor" from Level dropdown

**Expected Result:**
- Shows only Bachelor level scholarships (4 scholarships)

**Test 5b: Filter by Master**
- Select "Master" from Level dropdown
- Expected: Shows 2 Master scholarships

---

### Test 6: Filter by Field

**Steps:**
1. Select "Engineering" from Field dropdown

**Expected Result:**
- Shows all Engineering scholarships (3 total)

**Test 6b: Combined Filters**
- Select Bachelor level AND Engineering field
- Expected: Shows 2 Bachelor Engineering scholarships

---

### Test 7: Filter by Deadline

**Steps:**
1. Select date "2025-12-15" in deadline filter

**Expected Result:**
- Shows scholarships with deadline <= 2025-12-15

---

### Test 8: Sort by Deadline

**Steps:**
1. Click "Sort by Deadline" button

**Expected Result:**
- Scholarships sorted earliest to latest:
  1. 2025-11-20 (Arts)
  2. 2025-11-30 (Business)
  3. 2025-12-15 (Science)
  4. 2025-12-31 (Engineering & CS)
  5. 2026-01-31 (CS)
  6. 2026-02-28 (Engineering)

---

### Test 9: Sort by Amount

**Steps:**
1. Click "Sort by Amount" button

**Expected Result:**
- Scholarships sorted highest to lowest:
  1. ₱400,000 (Graduate Engineering)
  2. ₱375,000 (Science Pioneer)
  3. ₱300,000 (Computer Science)
  4. ₱250,000 (Engineering Excellence)
  5. ₱150,000 (Business Leaders)
  6. ₱125,000 (Arts)

---

### Test 10: Reset Filters

**Steps:**
1. Apply multiple filters/searches
2. Click "Reset" button

**Expected Result:**
- All filters cleared
- All 6 scholarships displayed
- Sorting reset to default order

---

### Test 11: View Scholarship Details

**Steps:**
1. Click "Details" button on any scholarship card

**Expected Result:**
- Modal popup appears with:
  - Full scholarship information
  - Apply Now button (external link)
  - Bookmark button
  - Close button (X)

**Test 11b: Apply Now Link**
- Click "Apply Now" button
- Expected: Opens scholarship application link in new tab

**Test 11c: Close Modal**
- Click X button or outside modal
- Expected: Modal closes

---

### Test 12: Add Bookmark

**Steps:**
1. Click "Bookmark" button on any scholarship

**Expected Result:**
- Button changes to "Bookmarked"
- Button color changes to orange/gold
- Data saved to bookmarks table

---

### Test 13: View Bookmarks

**Steps:**
1. Bookmark 2-3 scholarships
2. Click "My Bookmarks" tab

**Expected Result:**
- Shows only bookmarked scholarships
- Cards display same format as main view
- Bookmark button shows "Bookmarked" status

---

### Test 14: Remove Bookmark

**Steps:**
1. Go to My Bookmarks tab
2. Click "Bookmarked" button on any scholarship

**Expected Result:**
- Button changes to "Bookmark"
- Scholarship disappears from bookmarks list
- Bookmark removed from database

---

### Test 15: Logout

**Steps:**
1. Click "Logout" button in top right

**Expected Result:**
- Session destroyed
- Redirected to login page
- User data cleared

**Test 15b: Access Protected Page**
- Try accessing index.php directly
- Expected: Redirected to login page

---

### Test 16: Database Integrity

**Steps:**
1. Check database after various operations:
   ```sql
   SELECT * FROM users;
   SELECT * FROM scholarships;
   SELECT * FROM bookmarks;
   ```

**Expected Result:**
- Users table has registered users
- Scholarships table has 6 scholarships
- Bookmarks table shows user bookmarks with correct foreign keys

---

### Test 17: Mobile Responsiveness

**Steps:**
1. Open in browser DevTools
2. Toggle device toolbar (Ctrl+Shift+M)
3. Test on mobile sizes (iPhone, iPad, Android)

**Expected Result:**
- Layout adjusts to screen width
- Grid becomes single column on mobile
- All buttons and inputs remain accessible
- Text remains readable

---

### Test 18: XSS Prevention

**Steps:**
1. Try searching with: `<script>alert('xss')</script>`
2. Try registering with username: `"><script>alert('xss')</script>`

**Expected Result:**
- No JavaScript executed
- Text displayed as literal string
- HTML escaped properly

---

## Database Verification Commands

```sql
-- Check users
SELECT * FROM users;

-- Check scholarships
SELECT * FROM scholarships ORDER BY amount DESC;

-- Check bookmarks with user info
SELECT b.id, u.username, s.title, b.created_at 
FROM bookmarks b
JOIN users u ON b.user_id = u.id
JOIN scholarships s ON b.scholarship_id = s.id;

-- Count scholarships by level
SELECT education_level, COUNT(*) as count 
FROM scholarships 
GROUP BY education_level;

-- Count scholarships by field
SELECT field, COUNT(*) as count 
FROM scholarships 
GROUP BY field;
```

## Performance Notes

- Quick Sort should complete instantly for 6 scholarships
- Sorting by deadline/amount should be smooth
- Search/filter results appear immediately
- No noticeable lag on modern browsers
- Modal opens/closes smoothly

## Bug Report Template

If issues found, record:
- Test case number
- Steps to reproduce
- Expected result
- Actual result
- Browser/PHP version
- MySQL version
- Error message (if any)
- Screenshot/screencast
