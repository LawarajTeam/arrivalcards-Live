# 🚀 QUICK START GUIDE - Arrival Cards

## Get Running in 5 Minutes!

### Step 1: Setup XAMPP
1. Make sure XAMPP is installed and running
2. Start Apache and MySQL services

### Step 2: Place Files
1. Copy the entire `ArrivalCards` folder to: `C:\xampp\htdocs\`
2. Your path should be: `C:\xampp\htdocs\ArrivalCards\`

### Step 3: Create Database
1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Name it: `arrivalcards`
4. Click "Create"
5. Click "Import" tab
6. Choose file: `database.sql` from your ArrivalCards folder
7. Click "Go" at bottom
8. Wait for success message

### Step 4: Test Your Site
1. Open browser and go to: `http://localhost/ArrivalCards/test.php`
2. All tests should pass ✅
3. If any tests fail, check:
   - Is MySQL running?
   - Did database import succeed?
   - Are files in correct location?

### Step 5: View Your Site
1. Homepage: `http://localhost/ArrivalCards/index.php`
2. Admin Panel: `http://localhost/ArrivalCards/admin/`
   - Username: `admin`
   - Password: `admin123`
   - ⚠️ **CHANGE THIS IMMEDIATELY!**

## Features to Try

### On the Homepage:
- ✨ **Search** for countries in real-time
- 🌍 **Filter** by region or visa type
- 🌐 **Switch languages** (top right)
- 🇫🇷 Click "View Official Site" on any country

### Test Contact Form:
- Go to `http://localhost/ArrivalCards/contact.php`
- Fill out the form
- Check admin panel to see the submission

### Admin Panel:
- View dashboard statistics
- See contact submissions
- Manage countries
- View recent activity

## Common Issues & Fixes

### "Database Connection Failed"
- ✅ Start MySQL in XAMPP Control Panel
- ✅ Check database name is `arrivalcards`
- ✅ Default MySQL credentials: user=`root`, password=empty

### "Page Not Found"
- ✅ Check URL: `http://localhost/ArrivalCards/index.php`
- ✅ Ensure folder name is exactly `ArrivalCards`
- ✅ Make sure Apache is running in XAMPP

### "No Countries Showing"
- ✅ Import `database.sql` again via phpMyAdmin
- ✅ Check test.php shows countries in database

### Emails Not Sending
- ℹ️ Normal on localhost - emails configured for production
- ℹ️ Messages still save to database
- ℹ️ Check admin panel to see submissions

## Next Steps

1. **Change Admin Password**
   - Login to admin panel
   - This is critical for security!

2. **Customize .env File** (Optional)
   - Located in ArrivalCards folder
   - Update email settings
   - Change site name if desired

3. **Add More Countries**
   - Use admin panel (coming soon)
   - Or add directly via phpMyAdmin

4. **Test All Features**
   - Try all 5 languages
   - Test search and filters
   - Submit contact form
   - Browse countries

## Need Help?

1. Run test page: `http://localhost/ArrivalCards/test.php`
2. Check README.md for detailed information
3. Review DEPLOYMENT.md for production setup
4. Email: me@carlosantoro.com

## GitHub Setup (Optional)

```bash
cd "C:\Users\Carlo Santoro\OneDrive - RETAILCARE PTY LTD\Documents\AI Projects\ArrivalCards"
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/yourusername/arrivalcards.git
git push -u origin main
```

---

**You're all set! 🎉**

Visit: `http://localhost/ArrivalCards/`
Admin: `http://localhost/ArrivalCards/admin/`
