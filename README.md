# Arrival Cards - International Visa Information Portal

A professional, multi-language PHP/MySQL web application providing quick access to official visa and arrival information for countries worldwide.

## Features

- 🌍 **Multi-Language Support**: English, Spanish, Chinese, French, German
- 🔍 **Real-time Search**: Instant filtering as you type
- 📱 **Mobile-First Design**: Fully responsive across all devices
- 🛡️ **Secure Admin Panel**: Manage countries and translations
- ✉️ **Contact System**: Database storage + email notifications with honeypot spam protection
- 👍 **User Feedback System**: "Was this helpful?" voting with tallies and duplicate prevention
- 📊 **Google Analytics**: Integrated with environment variable configuration
- 🔒 **Privacy Policy**: GDPR-compliant template linked in footer
- 🔐 **Secure Configuration**: Environment variables stored in .env file
- 🎨 **Professional Design**: Corporate, clean, fast-loading
- ♿ **Accessible**: WCAG compliant with ARIA labels

## Requirements

- PHP 8.0 or higher
- MySQL 8.0 or higher
- XAMPP (for local development)
- Modern web browser

## Local Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/arrivalcards.git
cd arrivalcards
```

### 2. Configure Environment

```bash
# Copy the example environment file
copy .env.example .env

# Edit .env with your database credentials
notepad .env
```

### 3. Import Database

```bash
# Using MySQL command line:
mysql -u root -p < database.sql

# Or import via phpMyAdmin:
# 1. Open http://localhost/phpmyadmin
# 2. Create database 'arrivalcards'
# 3. Import database.sql file
```

### 4. Configure Web Server

**For XAMPP:**
- Place project in `C:\xampp\htdocs\ArrivalCards`
- Access at: `http://localhost/ArrivalCards`

### 5. Test Installation

Visit: `http://localhost/ArrivalCards/test.php`

This will verify:
- Database connection
- All tables exist
- PHP configuration
- File permissions
- Language files loaded

## Admin Access

**Default Credentials:**
- Username: `admin`
- Password: `admin123`
- URL: `http://localhost/ArrivalCards/admin`

⚠️ **IMPORTANT**: Change the default password immediately after first login!

## Email Configuration

For contact form emails to work:

### Local Development (XAMPP):
1. Edit `.env` with Gmail SMTP settings
2. Enable 2-factor authentication on your Gmail
3. Generate an "App Password" in Gmail settings
4. Use the app password in `SMTP_PASS`

### Production:
- Use your hosting provider's SMTP settings
- Or configure with SendGrid/Mailgun for reliability

## Project Structure

```
/ArrivalCards
├── /admin              # Admin panel
├── /assets
│   ├── /css           # Stylesheets
│   ├── /js            # JavaScript files
│   └── /images        # Logo and images
├── /includes          # Core PHP files
├── /languages         # Translation files
├── index.php          # Homepage
├── contact.php        # Contact form
├── privacy.php        # Privacy policy
└── test.php           # System verification
```

## Development Workflow

### Testing Locally

```bash
# Start XAMPP services
# Visit http://localhost/ArrivalCards
# Use test.php to verify functionality
```

### Adding New Countries

1. Login to admin panel
2. Go to "Manage Countries"
3. Click "Add New Country"
4. Fill in all language translations
5. Provide official visa URL
6. Save and verify on frontend

### Modifying Translations

1. Login to admin panel
2. Go to "Translations"
3. Edit text for each language
4. Changes appear immediately on frontend

## Deployment to Production

### 1. Prepare for Production

```bash
# Update .env for production
APP_ENV=production
APP_URL=https://yourdomain.com

# Update database credentials
DB_HOST=your-production-host
DB_USER=your-production-user
DB_PASS=your-secure-password
```

### 2. Security Checklist

- [ ] Change admin password from default (admin123)
- [ ] Update `SESSION_SECRET` in .env with random string
- [ ] Set strong database password
- [ ] Configure SSL certificate (HTTPS)
- [ ] Set proper file permissions (644 for files, 755 for directories)
- [ ] Remove or secure test.php (delete in production)
- [ ] Enable error logging (disable display_errors in php.ini)
- [ ] Add your Google Analytics tracking ID to GA_TRACKING_ID in .env
- [ ] Review privacy policy and update with your contact information
- [ ] Test honeypot spam protection on contact form

### 3. Upload Files

```bash
# Using Git
git push origin main

# Or FTP/SFTP
# Upload all files except .git, .env (create new on server)
```

### 4. Configure Database

```bash
# Import database.sql on production server
# Update .env with production database credentials
```

### 5. Test Production

- Test all pages load correctly
- Verify language switching works
- Test contact form sends emails
- Check admin panel functionality
- Test on mobile devices

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Android)

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Troubleshooting

### Database Connection Fails
- Verify MySQL service is running in XAMPP
- Check credentials in .env file
- Ensure arrivalcards database exists
- Verify PDO extension is enabled in PHP

### Emails Not Sending
- Check SMTP settings in .env
- Verify Gmail app password if using Gmail
- Check PHP error logs in XAMPP
- Test with simple PHP mail() function first

### Translations Not Loading
- Verify translations table has data
- Check database connection works
- Verify t() function is being called
- Clear browser cache and refresh

### Admin Login Not Working
- Verify database has admin_users table
- Default credentials: admin / admin123
- Check if session is working (session.save_path writable)
- Try resetting password via SQL:
  ```sql
  UPDATE admin_users 
  SET password = '$2y$10$YourNewBcryptHashHere' 
  WHERE username = 'admin';
  ```

### User Feedback Not Working
- Verify country_feedback table exists
- Check submit_feedback.php is accessible
- Check browser console for JavaScript errors
- Verify getClientIP() function returns valid IP

### Google Analytics Not Tracking
- Add your tracking ID to .env: GA_TRACKING_ID=G-XXXXXXXXXX
- Check page source to verify gtag.js is loaded
- Test in Google Analytics real-time reports
- Ensure tracking ID format is correct (starts with G-)

## License

This project is private and proprietary.

## Support

For issues or questions:
- Email: me@carlosantoro.com
- Open an issue on GitHub

## Credits

Developed by Carlo Santoro
Last Updated: February 2026
