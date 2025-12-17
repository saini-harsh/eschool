# File Upload Quick Reference Guide

## 🎯 **How It Works**

### **Local Development (Your Computer)**
```
Files save to: E:\eschool\public\admin\ABC_School\teachers\
Access via URL: http://localhost/admin/ABC_School/teachers/image.jpg
```

### **Production (cPanel Server)**
```
Files save to: /home/username/public_html/admin/ABC_School/teachers/
Access via URL: https://yourdomain.com/admin/ABC_School/teachers/image.jpg
```

## 🔄 **The Magic: FileUploadHelper**

The `FileUploadHelper` automatically detects your environment and uses the correct path!

### **Before (Old Code)**
```php
$file = $request->file('profile_image');
$fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
$destinationPath = public_path('admin/uploads/teachers');

if (!file_exists($destinationPath)) {
    mkdir($destinationPath, 0755, true);
}

$file->move($destinationPath, $fileName);
$photoPath = 'admin/uploads/teachers/' . $fileName;
```

### **After (New Code with Helper)**
```php
use App\Helpers\FileUploadHelper;

// Simple one-liner!
$photoPath = FileUploadHelper::uploadFile($file, 'admin/ABC_School/teachers');
```

## 📝 **Usage Examples**

### **Example 1: Teacher Profile Image**
```php
use App\Helpers\FileUploadHelper;

public function store(Request $request)
{
    $institution = Institution::find($request->institution_id);
    
    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $institutionFolder = FileUploadHelper::sanitizeInstitutionName($institution->name);
        $folder = 'admin/' . $institutionFolder . '/teachers';
        
        // Upload and get path
        $photoPath = FileUploadHelper::uploadFile($file, $folder);
    }
    
    $teacher = new Teacher();
    $teacher->profile_image = $photoPath;
    $teacher->save();
}
```

### **Example 2: Student Documents**
```php
use App\Helpers\FileUploadHelper;

public function store(Request $request)
{
    $institutionFolder = FileUploadHelper::sanitizeInstitutionName($institution->name);
    
    // Photo
    if ($request->hasFile('photo')) {
        $photoPath = FileUploadHelper::uploadFile(
            $request->file('photo'), 
            'admin/' . $institutionFolder . '/students'
        );
    }
    
    // Aadhaar Front
    if ($request->hasFile('aadhaar_front')) {
        $aadhaarPath = FileUploadHelper::uploadFile(
            $request->file('aadhaar_front'), 
            'admin/' . $institutionFolder . '/students/documents'
        );
    }
}
```

### **Example 3: Custom File Name**
```php
use App\Helpers\FileUploadHelper;

// Generate custom filename
$customFileName = 'student_' . $studentId . '_photo.jpg';

$photoPath = FileUploadHelper::uploadFile(
    $request->file('photo'), 
    'admin/' . $institutionFolder . '/students',
    $customFileName  // Custom filename
);
```

## 🔧 **Helper Methods Available**

### **1. uploadFile($file, $folder, $fileName = null)**
```php
// Auto-generate filename
$path = FileUploadHelper::uploadFile($file, 'admin/ABC_School/teachers');

// Custom filename
$path = FileUploadHelper::uploadFile($file, 'admin/ABC_School/teachers', 'custom_name.jpg');
```

### **2. sanitizeInstitutionName($name)**
```php
$folder = FileUploadHelper::sanitizeInstitutionName('ABC School & College');
// Returns: 'ABC_School__College'
```

### **3. getPublicPath($subPath = '')**
```php
// Get base public path
$basePath = FileUploadHelper::getPublicPath();

// Get specific folder path
$teacherPath = FileUploadHelper::getPublicPath('admin/ABC_School/teachers');
```

### **4. getPublicUrl($path)**
```php
// Convert file system path to URL path
$url = FileUploadHelper::getPublicUrl($filePath);
```

## 🌍 **Environment Configuration**

### **Local Development (.env)**
```env
APP_ENV=local
PUBLIC_PATH=public
```

### **Production - cPanel (.env)**
```env
APP_ENV=production
PUBLIC_PATH=../../public_html
```

## ✅ **Migration Checklist**

To update all your controllers to use FileUploadHelper:

1. **Add use statement** at the top of controller:
   ```php
   use App\Helpers\FileUploadHelper;
   ```

2. **Replace file upload code** with:
   ```php
   $path = FileUploadHelper::uploadFile($file, $folder);
   ```

3. **Remove old helper methods** like `sanitizeInstitutionName()` from controllers

4. **Test locally** before deploying

## 🚀 **Deployment Steps**

1. **Update .env on production server:**
   ```env
   APP_ENV=production
   PUBLIC_PATH=../../public_html
   ```

2. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

3. **Upload files:**
   - Laravel files → `/home/username/envisiontechsolution/`
   - Public files → `/home/username/public_html/`

4. **Test file upload** from admin panel

## 🐛 **Troubleshooting**

### **Files not saving?**
```bash
# Check permissions
chmod -R 775 storage
chmod -R 755 public_html/admin
chmod -R 755 public_html/Institution
chmod -R 755 public_html/teacher
chmod -R 755 public_html/student
```

### **Images not displaying?**
- Check `PUBLIC_PATH` in `.env`
- Verify files are in `public_html/` not `envisiontechsolution/public/`
- Clear config: `php artisan config:clear`

### **Path errors?**
```php
// Debug path in controller
dd(FileUploadHelper::getPublicPath('admin/test'));
```

## 📊 **Folder Structure**

### **Local Development**
```
E:\eschool\
└── public\
    ├── admin\
    │   └── {InstitutionName}\
    ├── Institution\
    │   └── {InstitutionName}\
    ├── teacher\
    │   └── {InstitutionName}\
    └── student\
        └── {InstitutionName}\
```

### **Production (cPanel)**
```
/home/username/
├── envisiontechsolution\  (Laravel files)
└── public_html\           (Web accessible)
    ├── admin\
    │   └── {InstitutionName}\
    ├── Institution\
    │   └── {InstitutionName}\
    ├── teacher\
    │   └── {InstitutionName}\
    └── student\
        └── {InstitutionName}\
```

---

## 💡 **Pro Tips**

1. **Always test file uploads after deployment**
2. **Monitor storage space** on server
3. **Keep backups** of uploaded files
4. **Use consistent naming** for institution folders
5. **Set proper permissions** (755 for folders, 644 for files)

## 📞 **Need More Help?**

Check:
- `CPANEL_DEPLOYMENT_GUIDE.md` for full deployment instructions
- Laravel logs: `storage/logs/laravel.log`
- Test upload in different environments before going live

