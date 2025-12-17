# cPanel Deployment Guide for Laravel Project

## 📋 **Pre-Deployment Checklist**

- [ ] All code tested locally
- [ ] Database backup created
- [ ] `.env` file configured for production
- [ ] File upload paths updated

## 🚀 **Step-by-Step Deployment**

### **Step 1: Upload Project Files**

1. **Connect to cPanel via FTP or File Manager**

2. **Create main project folder** (outside public_html)
   ```
   /home/username/envisiontechsolution/
   ```

3. **Upload all files EXCEPT the `public` folder contents to:**
   ```
   /home/username/envisiontechsolution/
   ```
   
   Upload these folders:
   - `app/`
   - `bootstrap/`
   - `config/`
   - `database/`
   - `resources/`
   - `routes/`
   - `storage/`
   - `vendor/` (or run composer install on server)
   - Files: `.env`, `artisan`, `composer.json`, etc.

4. **Upload PUBLIC folder contents to public_html:**
   ```
   /home/username/public_html/
   ```
   
   Move these from `public/` folder:
   - `index.php`
   - `.htaccess`
   - `admin/` folder (uploaded images will go here)
   - `Institution/` folder
   - `teacher/` folder
   - `student/` folder
   - `css/`, `js/`, `images/` folders
   - `favicon.ico`
   - Any other public assets

### **Step 2: Update index.php**

Edit `/home/username/public_html/index.php`:

**ORIGINAL CODE:**
```php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
```

**CHANGE TO:**
```php
require __DIR__.'/../envisiontechsolution/vendor/autoload.php';
$app = require_once __DIR__.'/../envisiontechsolution/bootstrap/app.php';
```

### **Step 3: Configure .env File**

Edit `/home/username/envisiontechsolution/.env`:

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# File Upload Path for cPanel
PUBLIC_PATH=../../public_html

# Session & Cache
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=database
```

### **Step 4: Set Folder Permissions**

Via SSH or cPanel Terminal:

```bash
cd /home/username/envisiontechsolution

# Set storage permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set public_html permissions for uploads
cd /home/username/public_html
chmod -R 755 admin
chmod -R 755 Institution
chmod -R 755 teacher
chmod -R 755 student
```

### **Step 5: Run Laravel Commands**

Via SSH:

```bash
cd /home/username/envisiontechsolution

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Generate application key (if not set)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link (if needed)
php artisan storage:link
```

### **Step 6: Update .htaccess (Optional)**

If you have URL rewriting issues, update `/home/username/public_html/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## 🗂️ **Final Directory Structure on cPanel**

```
/home/username/
├── envisiontechsolution/          (Laravel Root - Private)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   ├── artisan
│   └── composer.json
│
└── public_html/                   (Web Root - Public)
    ├── admin/                     (Upload folder)
    │   └── {InstitutionName}/
    ├── Institution/               (Upload folder)
    │   └── {InstitutionName}/
    ├── teacher/                   (Upload folder)
    │   └── {InstitutionName}/
    ├── student/                   (Upload folder)
    │   └── {InstitutionName}/
    ├── css/
    ├── js/
    ├── images/
    ├── .htaccess
    ├── index.php
    └── favicon.ico
```

## ✅ **Testing After Deployment**

1. **Test Main URL:**
   ```
   https://yourdomain.com
   ```

2. **Test Admin Login:**
   ```
   https://yourdomain.com/admin/login
   ```

3. **Test File Upload:**
   - Upload an image from admin panel
   - Check if image is saved in `/public_html/admin/{InstitutionName}/...`
   - Verify image displays correctly

4. **Check Permissions:**
   - Ensure uploaded files are accessible via URL
   - Example: `https://yourdomain.com/admin/ABC_School/teachers/123456.jpg`

## 🔧 **Troubleshooting**

### **Issue: 500 Internal Server Error**
**Solution:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan config:clear
```

### **Issue: Images Not Showing**
**Solution:**
- Check if files are in `public_html/` not `envisiontechsolution/public/`
- Verify folder permissions: `chmod -R 755 admin Institution teacher student`
- Check `.env` has: `PUBLIC_PATH=../../public_html`

### **Issue: Database Connection Error**
**Solution:**
- Update `.env` database credentials
- Use `localhost` for DB_HOST
- Run: `php artisan config:clear`

### **Issue: CSS/JS Not Loading**
**Solution:**
- Check `APP_URL` in `.env` matches your domain
- Clear browser cache
- Check `.htaccess` file exists in `public_html/`

## 📝 **Important Notes**

1. **Never commit `.env` file** to version control
2. **Keep `storage/` and `bootstrap/cache/` writable**
3. **Regular backups** of database and uploaded files
4. **Monitor storage space** for uploaded files
5. **Set APP_DEBUG=false** in production

## 🔐 **Security Checklist**

- [ ] `APP_DEBUG=false` in production
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secure
- [ ] `.env` file protected (outside public_html)
- [ ] File upload validation enabled
- [ ] HTTPS enabled (SSL certificate)
- [ ] Regular security updates

---

## 📞 **Need Help?**

If you encounter any issues during deployment, check:
1. Laravel logs: `/home/username/envisiontechsolution/storage/logs/laravel.log`
2. cPanel Error logs
3. PHP error logs

