# PDF Preview Fix - Quick Start

## Problem
PDF letterheads cannot be previewed because ImageMagick and Ghostscript are not installed.

## Quick Fix (5 minutes)

### 1. Run Diagnostic
```powershell
cd "c:\xampp\htdocs\New folder\NexoraLabs"
php check_pdf_support.php
```

### 2. Install Required Software

#### Install Ghostscript FIRST (Required for PDF processing)
1. Download: https://www.ghostscript.com/download/gsdnld.html
2. Choose: **GPL Ghostscript 10.x for Windows (64-bit)**
3. Run installer with default settings

#### Install ImageMagick SECOND
1. Download: https://imagemagick.org/script/download.php#windows
2. Choose: **ImageMagick-7.x.x-Q16-HDRI-x64-dll.exe**
3. **IMPORTANT**: During installation check:
   - ✅ Install legacy utilities (e.g., convert)
   - ✅ Add application directory to your system path

### 3. Verify Installation
Open **NEW** PowerShell window (to load new PATH):
```powershell
magick -version
gswin64c --version
```

Both commands should show version information.

### 4. Restart Apache
1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache

### 5. Test
1. Go to letterhead positioning page
2. Upload a PDF letterhead
3. Preview should now be generated automatically

## Alternative: Use Installation Helper
Run this batch file for guided installation:
```
install_imagemagick.bat
```

## Troubleshooting

### "Command not found" after installation
- Close ALL PowerShell/Command Prompt windows
- Open a NEW window
- Try again

### Preview still not working
1. Check Laravel logs: `storage/logs/laravel.log`
2. Run diagnostic again: `php check_pdf_support.php`
3. Ensure Apache was restarted

### For detailed instructions
See: `PDF_PREVIEW_FIX_GUIDE.md`

## Current Status
❌ ImageMagick: Not installed
❌ Ghostscript: Not installed
❌ PHP Imagick Extension: Disabled (fixed)

## After Installation
✅ ImageMagick: Installed and in PATH
✅ Ghostscript: Installed and in PATH
✅ PDF Preview: Working

## Note
The positioning system works WITHOUT previews - you position on blank canvas and elements appear correctly on final PDF. However, preview makes positioning much easier and more accurate.
