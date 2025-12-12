# PDF Invoice Printing - Installation Requirements

## Overview
This project uses both server-side (PHP) and client-side (JavaScript) PDF generation for invoice printing.

---

## Required Installations

### 1. PHP Composer Packages (Already in composer.json)
These are installed via `composer install`:

- ✅ **barryvdh/laravel-dompdf** (^3.1) - Main PDF generation library
- ✅ **setasign/fpdf** (^1.8) - PDF creation library
- ✅ **setasign/fpdi** (^2.6) - PDF merging and manipulation

**Installation:**
```bash
composer install
```

---

### 2. System Software (Required for PDF Preview & Advanced Features)

#### A. ImageMagick (Recommended)
**Purpose:** PDF preview generation, image processing for letterheads

**Windows Installation:**
1. Download: https://imagemagick.org/script/download.php#windows
2. Choose: **ImageMagick-7.x.x-Q16-HDRI-x64-dll.exe**
3. During installation, check:
   - ✅ Install legacy utilities (e.g., convert)
   - ✅ Add application directory to your system path

**Linux:**
```bash
sudo apt-get install imagemagick
```

**Mac:**
```bash
brew install imagemagick
```

**Verify:**
```bash
magick -version
```

#### B. Ghostscript (Required if using ImageMagick)
**Purpose:** Required by ImageMagick for PDF processing

**Windows Installation:**
1. Download: https://www.ghostscript.com/download/gsdnld.html
2. Choose: **GPL Ghostscript 10.x for Windows (64-bit)**
3. Install to default location: `C:\Program Files\gs\gs10.06.0`
4. Add to PATH: `C:\Program Files\gs\gs10.06.0\bin`

**Linux:**
```bash
sudo apt-get install ghostscript
```

**Mac:**
```bash
brew install ghostscript
```

**Verify:**
```bash
# Windows
gswin64c --version

# Linux/Mac
gs --version
```

---

### 3. JavaScript Libraries (Already in project)
These are included in the project files:

- ✅ **html2canvas** - Located in `public/assets/invoice/js/html2canvas.js`
- ✅ **jsPDF** - Located in `public/assets/invoice/js/jspdf.min.js`

**Note:** These are already included in the project. No npm installation needed for these specific files.

---

### 4. Optional: PHP Imagick Extension (For Better Performance)
**Purpose:** PHP extension for ImageMagick (faster than command-line)

**Windows:**
1. Download: https://windows.php.net/downloads/pecl/releases/imagick/
2. Extract `php_imagick.dll` to PHP extension directory
3. Add to `php.ini`: `extension=imagick`
4. Restart web server

**Linux:**
```bash
sudo apt-get install php-imagick
sudo systemctl restart apache2  # or nginx
```

**Mac:**
```bash
pecl install imagick
```

---

## Quick Installation Checklist

### Minimum Requirements (Basic PDF Generation)
- [x] PHP 8.1+ (already required)
- [x] Composer packages installed (`composer install`)
- [x] JavaScript libraries (already in project)

### Recommended (Full Features)
- [ ] ImageMagick installed and in PATH
- [ ] Ghostscript installed and in PATH
- [ ] PHP Imagick extension (optional, for performance)

---

## Verification

Run the diagnostic script to check your installation:

```bash
php check_pdf_support.php
```

This will show:
- ✅ PHP Imagick Extension status
- ✅ ImageMagick command-line availability
- ✅ Ghostscript availability
- ✅ System information

---

## What Works Without ImageMagick/Ghostscript?

✅ **Basic PDF Generation** - Works fine using DomPDF
✅ **PDF Download** - Works fine
✅ **PDF Printing** - Works fine

❌ **PDF Preview** - Requires ImageMagick + Ghostscript
❌ **PDF Letterhead Preview** - Requires ImageMagick + Ghostscript

**Note:** The positioning system works WITHOUT previews - you can position elements on a blank canvas and they will appear correctly on the final PDF. However, preview makes positioning much easier.

---

## Installation Order (Windows)

1. **Install Ghostscript FIRST** (required dependency)
2. **Install ImageMagick SECOND** (depends on Ghostscript)
3. **Restart web server** (Apache/Nginx/XAMPP)
4. **Verify installation** using `check_pdf_support.php`

---

## Troubleshooting

### "Command not found" after installation
- Close ALL PowerShell/Command Prompt windows
- Open a NEW window
- Try again

### Preview still not working
1. Check Laravel logs: `storage/logs/laravel.log`
2. Run diagnostic: `php check_pdf_support.php`
3. Ensure web server was restarted after installation

### For detailed troubleshooting
See: `PDF_PREVIEW_FIX_GUIDE.md` and `PDF_PREVIEW_QUICKSTART.md`

---

## Summary

**Must Install:**
- Composer packages (via `composer install`)

**Should Install (for full features):**
- ImageMagick
- Ghostscript

**Optional (for better performance):**
- PHP Imagick extension

The JavaScript libraries (html2canvas, jsPDF) are already included in the project and don't require separate installation.

