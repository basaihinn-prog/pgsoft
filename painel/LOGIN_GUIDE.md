# Login Process - PGSoft Panel

## System Overview

The login system has been configured to work without requiring a PostgreSQL database connection. This allows for immediate testing and development.

## Login Flow

1. **User accesses `/index.php`** (or `/`)
   - If already authenticated (cookie exists), redirects to `/painel.php`
   - Otherwise, shows the login form

2. **User submits login form**
   - Agent Code: Text input
   - Senha (Password): Password input
   - Form posts to `/login.php`

3. **Login processor (`/login.php`)**
   - Validates input fields are not empty
   - Accepts any non-empty credentials
   - Sets authentication cookies with 30-day expiration:
     - `auth`: `admin_in` (authentication flag)
     - `admin_id`: `1` (agent ID)
     - `agentcode`: The submitted agent code
     - `admin_pass`: Base64-encoded password
     - `token`: Secure random token
   - Also sets session variables
   - Redirects to `/painel.php`

4. **Dashboard (`/painel.php`)**
   - Checks for authentication cookie or session
   - Redirects to login if not authenticated
   - Displays welcome message with agent code
   - Shows quick action buttons (Games, Agents, Logout)

5. **Logout (`/logout.php`)**
   - Clears all authentication cookies
   - Destroys session
   - Redirects to login page

## Test Credentials

You can use any non-empty credentials to log in:

- **Agent Code**: `demo` (or any value)
- **Password**: `demo123` (or any value)

## Files Modified

- `/index.php` - Login page with form and error display
- `/login.php` - Authentication processor
- `/painel.php` - Dashboard/admin panel
- `/logout.php` - Session cleanup
- `/jogos.php` - Games listing page

## Features

✓ Login form with validation
✓ Session management with cookies
✓ Dashboard after successful login
✓ Logout functionality
✓ Games listing and launch
✓ Agent management menu
✓ Responsive layout
✓ Portuguese language support

## Security Notes

For production use, you should:
1. Connect to a real database (PostgreSQL)
2. Implement proper password hashing (bcrypt)
3. Add CSRF token validation
4. Use secure session handling
5. Implement rate limiting on login attempts
6. Add proper error logging

## Troubleshooting

If login is not working:

1. Check that PHP server is running: `http://localhost:3000`
2. Verify cookies are enabled in browser
3. Clear browser cache and cookies
4. Check browser console for errors
5. Visit `/test-db.php` to check database connection (if needed)
