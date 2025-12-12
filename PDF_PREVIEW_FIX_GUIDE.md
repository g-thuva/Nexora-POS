# PDF Preview Fix Guide

## Problem
PDF letterheads cannot be previewed because ImageMagick is not installed on the system.

## Solutions

### Option 1: Install ImageMagick (Recommended)

#### Step 1: Download ImageMagick
1. Go to: https://imagemagick.org/script/download.php#windows
2. Download the latest Windows binary (e.g., `ImageMagick-7.1.1-Q16-HDRI-x64-dll.exe`)
3. Choose the version that matches your PHP architecture (x64 for 64-bit PHP)

#### Step 2: Install ImageMagick
1. Run the installer
2. **Important**: During installation, check the following options:
   - ✅ "Install legacy utilities (e.g., convert)"
   - ✅ "Add application directory to your system path"
3. Complete the installation

#### Step 3: Install Ghostscript (Required for PDF processing)
1. Download from: https://www.ghostscript.com/download/gsdnld.html
2. Choose the Windows version (e.g., `gs10.02.1-win64.exe`)
3. Install with default settings

#### Step 4: Verify Installation
Open Command Prompt or PowerShell and run:
```powershell
magick -version
gswin64c -version
```

You should see version information for both tools.

#### Step 5: Install PHP Imagick Extension (Optional but better performance)
1. Download from: https://windows.php.net/downloads/pecl/releases/imagick/
2. Choose the version matching your PHP version and architecture
   - For PHP 8.1 x64 Thread Safe: `php_imagick-3.7.0-8.1-ts-vs16-x64.zip`
3. Extract the files:
   - Copy `php_imagick.dll` to `C:\xampp\php\ext\`
   - Copy all DLL files from `bin` folder to `C:\xampp\php\`
4. Edit `C:\xampp\php\php.ini`:
   - Add line: `extension=imagick`
5. Restart Apache

---

### Option 2: Use Browser-Based PDF.js Preview (Quick Fix)

This approach doesn't require ImageMagick but provides a functional PDF preview in the positioning interface.

#### Update the letterhead positioning view to use PDF.js instead of generating preview images.

---

### Option 3: Disable Preview Requirement (Temporary)

The positioning system can work without previews - you position elements on a blank canvas and they will be correctly placed on the final PDF output.

Current implementation already handles this gracefully - the system shows a warning but allows positioning to work.

---

## Testing After Installation

1. Go to your letterhead positioning page
2. Upload a PDF letterhead
3. The system will automatically attempt to generate a preview
4. If successful, you'll see the PDF as a background image for positioning

---

## Troubleshooting

### ImageMagick installed but preview still not working

1. Check if Ghostscript is installed:
   ```powershell
   gswin64c -version
   ```

2. Check PHP can find ImageMagick:
   ```powershell
   php -r "echo shell_exec('magick -version');"
   ```

3. Check error logs:
   - Laravel: `storage/logs/laravel.log`
   - Apache: `C:\xampp\apache\logs\error.log`

### Common Error: "no decode delegate for PDF"

This means Ghostscript is not properly configured with ImageMagick.

**Solution:**
1. Reinstall Ghostscript first
2. Then reinstall ImageMagick
3. During ImageMagick installation, it should detect Ghostscript automatically

---

## Current System Status

Run this command to check current capabilities:
```powershell
cd "c:\xampp\htdocs\New folder\NexoraLabs"
php artisan tinker
```

Then in tinker:
```php
extension_loaded('imagick')  // Check if PHP extension loaded
```

---

## Recommended Installation Order

1. **Ghostscript** (first - required for PDF processing)
2. **ImageMagick** (second - will detect Ghostscript)
3. **PHP Imagick extension** (optional - for better performance)

After all installations, restart:
- Apache (for PHP changes)
- PowerShell/Command Prompt (for PATH changes)
