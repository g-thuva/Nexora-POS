@echo off
echo ============================================
echo PDF Preview Support Installation Guide
echo ============================================
echo.
echo This script will guide you through installing ImageMagick and Ghostscript
echo which are required for PDF preview generation in your letterhead system.
echo.
echo.

echo STEP 1: Install Ghostscript (REQUIRED - Install this FIRST)
echo -------------------------------------------------------
echo.
echo 1. Download from: https://www.ghostscript.com/download/gsdnld.html
echo 2. Choose: GPL Ghostscript 10.x for Windows (64-bit)
echo 3. Run the installer with default settings
echo 4. After installation, open a NEW command prompt and verify:
echo    gswin64c --version
echo.
set /p gs_done="Have you installed Ghostscript? (y/n): "
if /i not "%gs_done%"=="y" (
    echo.
    echo Please install Ghostscript first, then run this script again.
    pause
    exit /b
)

echo.
echo.
echo STEP 2: Install ImageMagick (REQUIRED)
echo -------------------------------------------------------
echo.
echo 1. Download from: https://imagemagick.org/script/download.php#windows
echo 2. Choose: ImageMagick-7.x.x-Q16-HDRI-x64-dll.exe
echo 3. During installation, CHECK these options:
echo    [X] Install legacy utilities (e.g., convert)
echo    [X] Add application directory to your system path
echo 4. Complete the installation
echo 5. After installation, open a NEW command prompt and verify:
echo    magick -version
echo.
set /p im_done="Have you installed ImageMagick? (y/n): "
if /i not "%im_done%"=="y" (
    echo.
    echo Please install ImageMagick, then run this script again.
    pause
    exit /b
)

echo.
echo.
echo STEP 3: Verify Installation
echo -------------------------------------------------------
echo.
echo Testing if ImageMagick is accessible...
where magick >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] ImageMagick found
    magick -version | findstr /i "ImageMagick"
) else (
    echo [ERROR] ImageMagick not found in PATH
    echo.
    echo Please:
    echo 1. Close this window
    echo 2. Reinstall ImageMagick and check "Add to system path"
    echo 3. Open a NEW command prompt and run this script again
    pause
    exit /b
)

echo.
echo Testing if Ghostscript is accessible...
where gswin64c >nul 2>&1
if %errorlevel% equ 0 (
    echo [OK] Ghostscript found
    gswin64c --version
) else (
    where gswin32c >nul 2>&1
    if %errorlevel% equ 0 (
        echo [OK] Ghostscript 32-bit found
        gswin32c --version
    ) else (
        echo [ERROR] Ghostscript not found in PATH
        echo.
        echo Please:
        echo 1. Close this window
        echo 2. Reinstall Ghostscript
        echo 3. Open a NEW command prompt and run this script again
        pause
        exit /b
    )
)

echo.
echo.
echo STEP 4: Test PDF Preview Generation
echo -------------------------------------------------------
echo.
echo Running PHP diagnostic test...
php check_pdf_support.php
echo.

echo.
echo ============================================
echo Installation Complete!
echo ============================================
echo.
echo Next steps:
echo 1. Restart Apache in XAMPP Control Panel
echo 2. Go to your letterhead positioning page
echo 3. Upload a PDF letterhead
echo 4. The preview should now be generated automatically
echo.
echo If you still see issues, check:
echo - storage/logs/laravel.log for errors
echo - Make sure Apache has been restarted
echo.
pause
