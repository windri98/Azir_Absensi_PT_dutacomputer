# Testing Guide - PT Duta Computer Absensi System

## Quick Summary

Comprehensive audit dan fixes telah diimplementasikan untuk **Frontend Web**, **Backend Laravel**, dan **Mobile React Native**. Dokumen ini memberikan panduan testing untuk memastikan semua fixes berjalan dengan baik.

---

## BACKEND TESTING (Laravel)

### 1. Authorization Checks Testing

**Location**: `app/Http/Controllers/Api/V1/UserController.php`

#### Test: Admin-Only Access
```bash
# Test 1: Access user list tanpa authorization (should fail for non-admin)
GET /api/v1/users
Authorization: Bearer {non-admin-token}
# Expected: 403 Forbidden

# Test 2: Access user list dengan authorization (should succeed for admin)
GET /api/v1/users
Authorization: Bearer {admin-token}
# Expected: 200 OK
```

### 2. N+1 Query Testing

**Location**: `app/Http/Controllers/Api/V1/UserController.php` (index method)

#### Test: Eager Loading
```bash
# Test database queries di development mode:
# Should see 2 queries total: 1 for users, 1 for roles+permissions
# Not: 1 + (n * roles queries) + (n * permissions queries)

# Run with query logging:
# Set DB_LOG_QUERIES=true in .env and check logs
GET /api/v1/users?limit=20
```

### 3. Rate Limiting Testing

**Location**: `routes/api.php`

#### Test: Login Rate Limiting
```bash
# Test 1: First 5 attempts (should succeed)
for i in {1..5}; do
  curl -X POST http://localhost/api/v1/auth/login \
    -d '{"email":"test@example.com","password":"wrong"}' \
    -H "Content-Type: application/json"
done
# Expected: 5x 401 Unauthorized (wrong credentials)

# Test 2: 6th attempt (should be rate limited)
curl -X POST http://localhost/api/v1/auth/login \
  -d '{"email":"test@example.com","password":"wrong"}' \
  -H "Content-Type: application/json"
# Expected: 429 Too Many Requests
```

### 4. Pagination Testing

**Location**: `app/Http/Controllers/Api/V1/ReportController.php`

#### Test: Report Pagination
```bash
# Test 1: Validate date range (max 365 days)
GET /api/v1/reports/all-users?start_date=2024-01-01&end_date=2026-12-31
# Expected: 422 Unprocessable Entity with message about date range

# Test 2: Successful pagination
GET /api/v1/reports/all-users?start_date=2025-01-01&end_date=2025-03-31
# Expected: 200 OK with paginated data
```

### 5. Transaction Handling Testing

**Location**: `app/Http/Controllers/Api/V1/UserController.php` (store method)

#### Test: Rollback on Error
```bash
# Test: Create user with invalid role ID (should rollback)
POST /api/v1/users
Authorization: Bearer {admin-token}
Content-Type: application/json

{
  "employee_id": "EMP001",
  "name": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "roles": [99999]  // Invalid role ID
}

# Expected: 500 error, user should NOT be created in database
```

---

## FRONTEND TESTING (Vue 3)

### 1. Responsiveness Testing - Desktop

**Screen Size**: 1920x1080

#### Test Cases:
1. **Sidebar Visibility**: Sidebar should be visible
2. **Navbar**: Logo shows full "PT DUTA COMPUTER", hamburger menu hidden
3. **Main Content**: Padding `p-8` applied
4. **Grid Layout**: Dashboard shows 4 cards in 1 row
5. **Tables**: Desktop table view visible

**Testing Checklist**:
- [ ] Sidebar visible and properly styled
- [ ] All menu items accessible
- [ ] Content padding appropriate
- [ ] No horizontal scrolling
- [ ] Admin section visible for admin users

### 2. Responsiveness Testing - Tablet

**Screen Size**: 768x1024

#### Test Cases:
1. **Sidebar**: Should hide on iPad landscape
2. **Hamburger Menu**: Should appear and be functional
3. **Grid Layout**: Dashboard shows 2 cards per row
4. **Responsive Padding**: `p-4 md:p-6 lg:p-8`
5. **Typography**: Font sizes adjusted

**Testing Checklist**:
- [ ] Hamburger menu visible and clickable
- [ ] Clicking hamburger opens sidebar drawer
- [ ] Sidebar overlay has close button
- [ ] Grid properly shows 2 columns
- [ ] No content overflow

### 3. Responsiveness Testing - Mobile

**Screen Size**: 375x812 (iPhone X)

#### Test Cases:
1. **Sidebar**: Drawer mode, appears from left
2. **Navbar**: Hamburger menu visible, logo shortened to "PT DUTA"
3. **Padding**: Reduced to `p-4`
4. **Grid Layout**: Single column layout
5. **Tables**: Card view instead of table

**Testing Checklist**:
- [ ] Hamburger menu works
- [ ] Sidebar drawer overlay clickable to close
- [ ] All content visible without horizontal scroll
- [ ] Table shows as cards on mobile
- [ ] Buttons are touch-friendly size

### 4. Modal Responsiveness Testing

#### Desktop (1920x1080):
- Modal max-width: `max-w-lg`
- Padding: `p-6`

#### Mobile (375x812):
- Modal padding: `p-4`
- Should fit screen with margins

**Test Modal Behavior**:
```javascript
// Open a modal and test responsiveness
// E.g., Create new user form
```

---

## MOBILE TESTING (React Native)

### 1. Error Boundary Testing

#### Test: Crash Recovery
1. Install and run app
2. Trigger an error in a screen (intentionally throw error)
3. **Expected**: Error Boundary shows error message with "Coba Lagi" button
4. Click "Coba Lagi" button
5. **Expected**: App recovers and shows previous screen

### 2. Safe Area Testing

**Test on physical devices**:

#### iPhone X / iPhone 12 (Notch):
- [ ] Status bar area properly handled
- [ ] Bottom safe area respected
- [ ] Content not overlapping notch
- [ ] Buttons and input fields accessible

#### Android with notch:
- [ ] Similar safe area handling

#### Device Frames to Test:
- iPhone SE (no notch) - should still work
- iPhone 13 (Dynamic Island) - should work
- Galaxy S21 (hole punch) - should work

### 3. Secure Token Storage Testing

#### Test: Token Security
```javascript
// 1. Login
// 2. Check that token is in SecureStore (not AsyncStorage)
// 3. Logout
// 4. Verify token removed from SecureStore
```

**Verification**:
- AsyncStorage should NOT contain auth_token
- SecureStore should contain encrypted token
- User data OK in AsyncStorage (not sensitive)

### 4. Deep Linking Testing

#### Test Case 1: App Not Running
```bash
# Simulate deep link when app not running
adb shell am start -W -a android.intent.action.VIEW \
  -d "pt-duta-computer://activities" \
  com.dutacomputer.azir.absensi

# Expected: App opens at activities screen
```

#### Test Case 2: App Running
```bash
# Send deep link while app is running
adb shell am start -a android.intent.action.VIEW \
  -d "pt-duta-computer://activities/123" \
  com.dutacomputer.azir.absensi

# Expected: Navigate to activity detail screen
```

### 5. Render Performance Testing

#### Test: Memoization
1. Open HomeScreen
2. Update user profile (change in store)
3. HomeScreen should NOT re-render unnecessarily
4. Use React DevTools Profiler to verify

### 6. Zustand Persistence Testing

#### Test: State Persistence
```javascript
// 1. Login to app
// 2. Close app completely
// 3. Reopen app
// Expected: User data restored from AsyncStorage
// Note: Token NOT persisted (loaded from SecureStore)
```

---

## INTEGRATION TESTING

### 1. Login Flow Testing

**Steps**:
1. Navigate to login screen
2. Enter email and password
3. Click login button
4. Wait for API response
5. App should navigate to Main/Dashboard

**Check**:
- [ ] Token stored securely
- [ ] User data displayed correctly
- [ ] Rate limiting works (test with wrong password 6x)

### 2. Attendance Check-in/Check-out

**Requirements**:
- [ ] Location permission granted
- [ ] Device connected to internet

**Steps**:
1. Go to Attendance screen
2. Click "Check-in Sekarang"
3. App should show success notification
4. Check-in time displayed on card
5. Click "Check-out Sekarang"
6. Show success message

### 3. Offline Sync Testing (Mobile)

**Steps**:
1. Enable Airplane Mode
2. Create activity entry
3. Activity queued in database
4. Disable Airplane Mode
5. App should sync pending data
6. Verify activity appears on server

---

## PERFORMANCE TESTING

### Frontend:
- [ ] Page load time < 3 seconds
- [ ] No jank/jumpy behavior on scroll
- [ ] Responsive transitions smooth

### Backend:
- [ ] User list load time < 500ms
- [ ] Report generation < 2 seconds
- [ ] API response time < 200ms for simple queries

### Mobile:
- [ ] App startup time < 2 seconds
- [ ] Screen transitions smooth (60fps)
- [ ] No memory leaks (use memory profiler)

---

## CHECKLIST BEFORE PRODUCTION

### Backend:
- [ ] All authorization checks in place
- [ ] Rate limiting configured
- [ ] N+1 query issues fixed
- [ ] Transaction handling implemented
- [ ] Error messages consistent
- [ ] Pagination working
- [ ] Date range validation on reports

### Frontend:
- [ ] Responsive on 375px, 768px, 1920px widths
- [ ] Hamburger menu functional
- [ ] Tables show as cards on mobile
- [ ] All text readable on mobile
- [ ] No horizontal scrolling
- [ ] Modal sizing responsive

### Mobile:
- [ ] Error boundary shows and recovers
- [ ] Safe area respected on notched devices
- [ ] Token in SecureStore
- [ ] Deep linking functional
- [ ] Offline sync working
- [ ] Zustand persistence working
- [ ] All permissions handled gracefully

### Security:
- [ ] HTTPS configured
- [ ] CORS properly configured
- [ ] Tokens not exposed in logs
- [ ] Sensitive data encrypted in transit
- [ ] Rate limiting active

---

## Testing Commands

### Backend Testing:
```bash
# Run PHPUnit tests
php artisan test

# Run tests with coverage
php artisan test --coverage

# Test specific class
php artisan test tests/Feature/UserControllerTest.php
```

### Frontend Testing:
```bash
# Run Vue tests (if configured)
npm run test

# Build for production
npm run build
```

### Mobile Testing:
```bash
# Run app in development
expo start

# Run on iOS
expo run:ios

# Run on Android
expo run:android

# Run tests
npm test
```

---

## Known Testing Scenarios

### Scenario 1: Admin User Flow
1. Login as admin
2. View users list
3. Create new user
4. Update user
5. Delete user
6. View reports

### Scenario 2: Employee User Flow
1. Login as employee
2. View dashboard
3. Check-in/Check-out
4. View attendance history
5. Submit activity report

### Scenario 3: Network Error Handling
1. Simulate offline (kill network)
2. Try to perform action
3. Should show error message
4. Restore network
5. Retry should work

---

## Support & Issues

If you encounter issues during testing:

1. Check error logs
2. Verify environment variables are set correctly
3. Clear cache/storage and restart
4. Check console for JavaScript errors
5. Report issues with reproduction steps

---

**Last Updated**: January 27, 2026  
**Tested On**: Desktop (1920x1080), Tablet (768x1024), Mobile (375x812)
