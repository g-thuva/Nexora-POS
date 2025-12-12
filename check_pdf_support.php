<?php
/**
 * PDF Support Diagnostic Tool
 * Run this from command line: php check_pdf_support.php
 */

echo "=== PDF Preview Support Diagnostic ===\n\n";

// Check 1: PHP Imagick Extension
echo "1. PHP Imagick Extension\n";
if (extension_loaded('imagick')) {
    echo "   ✅ INSTALLED\n";
    try {
        $imagick = new Imagick();
        $formats = $imagick->queryFormats('PDF');
        if (!empty($formats)) {
            echo "   ✅ PDF support enabled\n";
        } else {
            echo "   ❌ PDF support NOT available (Ghostscript missing?)\n";
        }
        $version = $imagick->getVersion();
        echo "   Version: " . $version['versionString'] . "\n";
        $imagick->destroy();
    } catch (Exception $e) {
        echo "   ⚠️  Error testing Imagick: " . $e->getMessage() . "\n";
    }
} else {
    echo "   ❌ NOT INSTALLED\n";
    echo "   Note: PHP can still use command-line ImageMagick\n";
}

echo "\n";

// Check 2: ImageMagick Command Line
echo "2. ImageMagick Command Line (magick.exe)\n";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $output = shell_exec('where magick 2>NUL');
    if (!empty($output)) {
        echo "   ✅ FOUND: " . trim($output) . "\n";
        $version = shell_exec('magick -version 2>NUL');
        if ($version) {
            $lines = explode("\n", $version);
            echo "   " . trim($lines[0]) . "\n";
        }
    } else {
        echo "   ❌ NOT FOUND\n";
    }
} else {
    $magickPath = trim(shell_exec('which magick 2>/dev/null'));
    if (empty($magickPath)) {
        $magickPath = trim(shell_exec('which convert 2>/dev/null'));
    }
    if (!empty($magickPath)) {
        echo "   ✅ FOUND: $magickPath\n";
        $version = shell_exec("$magickPath -version 2>/dev/null");
        if ($version) {
            $lines = explode("\n", $version);
            echo "   " . trim($lines[0]) . "\n";
        }
    } else {
        echo "   ❌ NOT FOUND\n";
    }
}

echo "\n";

// Check 3: Ghostscript
echo "3. Ghostscript (Required for PDF processing)\n";
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $gsOutput = shell_exec('where gswin64c 2>NUL');
    if (empty($gsOutput)) {
        $gsOutput = shell_exec('where gswin32c 2>NUL');
    }
    if (!empty($gsOutput)) {
        echo "   ✅ FOUND: " . trim($gsOutput) . "\n";
        $version = shell_exec('gswin64c --version 2>NUL');
        if (empty($version)) {
            $version = shell_exec('gswin32c --version 2>NUL');
        }
        if ($version) {
            echo "   Version: " . trim($version) . "\n";
        }
    } else {
        echo "   ❌ NOT FOUND\n";
        echo "   ⚠️  CRITICAL: Ghostscript is required for PDF preview generation\n";
    }
} else {
    $gsPath = trim(shell_exec('which gs 2>/dev/null'));
    if (!empty($gsPath)) {
        echo "   ✅ FOUND: $gsPath\n";
        $version = shell_exec('gs --version 2>/dev/null');
        if ($version) {
            echo "   Version: " . trim($version) . "\n";
        }
    } else {
        echo "   ❌ NOT FOUND\n";
    }
}

echo "\n";

// Check 4: System Info
echo "4. System Information\n";
echo "   OS: " . PHP_OS . "\n";
echo "   PHP Version: " . PHP_VERSION . "\n";
echo "   PHP Architecture: " . (PHP_INT_SIZE * 8) . "-bit\n";

echo "\n";

// Summary
echo "=== SUMMARY ===\n";
$phpImagick = extension_loaded('imagick');
$cmdImageMagick = !empty(shell_exec(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where magick 2>NUL' : 'which magick 2>/dev/null'));
$ghostscript = !empty(shell_exec(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'where gswin64c 2>NUL' : 'which gs 2>/dev/null'));

if ($phpImagick && $ghostscript) {
    echo "✅ PDF Preview: FULLY SUPPORTED (Best Performance)\n";
    echo "   Using: PHP Imagick Extension + Ghostscript\n";
} elseif ($cmdImageMagick && $ghostscript) {
    echo "✅ PDF Preview: SUPPORTED (Good Performance)\n";
    echo "   Using: Command-line ImageMagick + Ghostscript\n";
} elseif ($cmdImageMagick || $phpImagick) {
    echo "⚠️  PDF Preview: PARTIALLY SUPPORTED\n";
    echo "   ImageMagick found but Ghostscript is MISSING\n";
    echo "   Action Required: Install Ghostscript\n";
} else {
    echo "❌ PDF Preview: NOT SUPPORTED\n";
    echo "   Action Required: Install ImageMagick and Ghostscript\n";
    echo "   Note: Positioning system will work but without preview background\n";
}

echo "\n=== INSTALLATION INSTRUCTIONS ===\n";
if (!$ghostscript) {
    echo "1. Install Ghostscript first:\n";
    echo "   Download: https://www.ghostscript.com/download/gsdnld.html\n\n";
}
if (!$cmdImageMagick && !$phpImagick) {
    echo "2. Install ImageMagick:\n";
    echo "   Download: https://imagemagick.org/script/download.php#windows\n";
    echo "   Important: Check 'Add to system PATH' during installation\n\n";
}
if (!$phpImagick && ($cmdImageMagick || $ghostscript)) {
    echo "3. (Optional) Install PHP Imagick extension for better performance:\n";
    echo "   Download: https://windows.php.net/downloads/pecl/releases/imagick/\n";
    echo "   Extract php_imagick.dll to: " . PHP_EXTENSION_DIR . "\n";
    echo "   Add to php.ini: extension=imagick\n\n";
}

echo "\nFor detailed instructions, see: PDF_PREVIEW_FIX_GUIDE.md\n";
