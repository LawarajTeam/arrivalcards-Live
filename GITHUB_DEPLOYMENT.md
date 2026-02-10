# GitHub Actions Auto-Deployment Setup

Your repository is now configured to automatically deploy to **www.arrivalcards.com** when you push code to the `main` branch.

## ⚙️ One-Time Setup Required

### Add FTP Password to GitHub Secrets:

1. Go to your repository on GitHub:
   https://github.com/LawarajTeam/arrivalcards-Live

2. Click **Settings** (top menu)

3. Click **Secrets and variables** → **Actions** (left sidebar)

4. Click **New repository secret**

5. Enter:
   - **Name:** `FTP_PASSWORD`
   - **Secret:** `Ijmb)%v]If`

6. Click **Add secret**

## 🚀 How to Deploy

After setting up the secret, just push your code:

```bash
git add .
git commit -m "Your commit message"
git push
```

GitHub Actions will automatically:
- ✅ Deploy files to your FTP server
- ✅ Upload to /public_html/
- ✅ Exclude development files
- ✅ Keep your site live and updated

## 📊 Monitor Deployments

View deployment status:
- Go to **Actions** tab in your GitHub repository
- See real-time deployment logs
- Get notified of success or failures

## 🎯 Manual Deploy

You can also trigger a deployment manually:
1. Go to **Actions** tab
2. Click **Deploy to Production**
3. Click **Run workflow**
4. Select `main` branch
5. Click **Run workflow**

## ⚠️ Important Notes

**After First Deployment:**
1. You still need to manually create database via cPanel
2. Import `database_complete.sql` via phpMyAdmin
3. Create `includes/config.php` on server with database credentials
4. Create admin user

These are one-time setup tasks that can't be automated via FTP.

## 🔐 Security

- FTP password is stored securely in GitHub Secrets
- Never committed to repository
- Only accessible during GitHub Actions workflows
- Can be rotated anytime in repository settings

---

**Your site will auto-deploy on every push to main branch! 🎉**
