<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Imagick;

class LetterheadController extends Controller
{
    public function index()
    {
        $config = $this->getLetterheadConfig();

        // Get the latest order for testing
        $latestOrder = \App\Models\Order::latest()->first();
        $testOrderId = $latestOrder ? $latestOrder->id : null;

        // Add system diagnostics for PDF processing
        $diagnostics = $this->getPdfProcessingDiagnostics();

        return view('letterhead.index', compact('config', 'testOrderId', 'diagnostics'));
    }

    public function uploadLetterhead(Request $request)
    {
        try {
            // Log the incoming upload attempt for debugging
            $user = auth()->user();
            $userId = $user ? $user->id : null;
            $hasFile = $request->hasFile('letterhead');
            \Log::info('Letterhead upload attempt', [
                'user_id' => $userId,
                'has_file' => $hasFile,
                'content_length' => $request->server('CONTENT_LENGTH'),
                'ip' => $request->ip(),
            ]);

            // Validate and return JSON on failure
            $validator = \Validator::make($request->all(), [
                'letterhead' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5120'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->first();
                \Log::warning('Letterhead upload validation failed', ['user_id' => $userId, 'errors' => $errors]);
                return response()->json(['success' => false, 'message' => $errors], 422);
            }

            if ($hasFile) {
                $activeShop = $user ? $user->getActiveShop() : null;

                if (!$activeShop) {
                    \Log::warning('Letterhead upload failed: no active shop', ['user_id' => $userId]);
                    return response()->json(['success' => false, 'message' => 'No active shop found. Please select a shop first.'], 400);
                }

                // Delete old letterhead if exists
                $this->deleteOldLetterhead();

                $file = $request->file('letterhead');
                $extension = $file->getClientOriginalExtension();
                $filename = 'letterhead_shop_' . $activeShop->id . '.' . $extension;

                // Move file and check result
                $moved = false;
                try {
                    $file->move(public_path('letterheads'), $filename);
                    $moved = true;
                } catch (\Exception $e) {
                    \Log::error('Failed to move uploaded letterhead file', ['user_id' => $userId, 'error' => $e->getMessage()]);
                    return response()->json(['success' => false, 'message' => 'Failed to save uploaded file. Check server permissions.'], 500);
                }

                // If PDF, create a preview image for positioning (best-effort)
                $previewImage = null;
                if ($moved && strtolower($extension) === 'pdf') {
                    $previewImage = $this->createPdfPreviewImage(public_path('letterheads/' . $filename));

                    if (!$previewImage) {
                        \Log::warning('PDF preview generation failed during upload - positioning canvas will work without preview', ['user_id' => $userId]);
                    }
                }

                // Save letterhead info to config
                $config = [
                    'letterhead_file' => $filename,
                    'letterhead_type' => strtolower($extension) === 'pdf' ? 'pdf' : 'image',
                    'preview_image' => $previewImage,
                    'uploaded_at' => now()->toISOString()
                ];

                $this->saveLetterheadConfig($config);

                \Log::info('Letterhead uploaded successfully', ['user_id' => $userId, 'filename' => $filename]);

                return response()->json(['success' => true, 'filename' => $filename, 'type' => $config['letterhead_type']]);
            }

            \Log::warning('Letterhead upload called without file', ['user_id' => $userId]);
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        } catch (\Exception $e) {
            // Log full exception for debugging and return JSON so the frontend can display an error
            \Log::error('Exception in LetterheadController@uploadLetterhead', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Server error during upload: ' . $e->getMessage()], 500);
        }
    }

    public function savePositions(Request $request)
    {
        $positions = $request->validate([
            'positions' => 'required|array',
            'positions.*.field' => 'required|string',
            'positions.*.x' => 'required|numeric',
            'positions.*.y' => 'required|numeric',
            'positions.*.font_size' => 'nullable|numeric',
            'positions.*.font_weight' => 'nullable|string',
        ]);

        $config = $this->getLetterheadConfig();
        $config['positions'] = $positions['positions'];
        $this->saveLetterheadConfig($config);

        return response()->json(['success' => true]);
    }

    public function getPositions()
    {
        $config = $this->getLetterheadConfig();
        return response()->json($config['positions'] ?? []);
    }

    public function saveToggles(Request $request)
    {
        $toggles = $request->validate([
            'toggles' => 'required|array',
            'toggles.*' => 'boolean',
        ]);

        $config = $this->getLetterheadConfig();
        $config['element_toggles'] = $toggles['toggles'];
        $this->saveLetterheadConfig($config);

        return response()->json(['success' => true]);
    }

    public function getToggles()
    {
        $config = $this->getLetterheadConfig();
        return response()->json($config['element_toggles'] ?? []);
    }

    public function saveItemsAlignment(Request $request)
    {
        $alignment = $request->validate([
            'alignment' => 'required|array',
            'alignment.start_x' => 'required|numeric|min:0|max:400',
            'alignment.end_x' => 'required|numeric|min:200|max:595',
            'alignment.width' => 'required|numeric|min:300|max:570',
        ]);

        $config = $this->getLetterheadConfig();
        $config['items_alignment'] = $alignment['alignment'];
        $this->saveLetterheadConfig($config);

        return response()->json(['success' => true, 'message' => 'Items alignment saved successfully']);
    }

    public function saveTableWidth(Request $request)
    {
        $data = $request->validate([
            'table_width' => 'required|numeric|min:300|max:570',
        ]);

        $config = $this->getLetterheadConfig();
        $config['table_width'] = $data['table_width'];
        $this->saveLetterheadConfig($config);

        return response()->json(['success' => true, 'message' => 'Table width saved successfully']);
    }

    public function regeneratePreview()
    {
        $config = $this->getLetterheadConfig();

        if (!isset($config['letterhead_file']) || $config['letterhead_type'] !== 'pdf') {
            return response()->json(['success' => false, 'message' => 'No PDF letterhead found']);
        }

        $pdfPath = public_path('letterheads/' . $config['letterhead_file']);
        if (!file_exists($pdfPath)) {
            return response()->json(['success' => false, 'message' => 'PDF file not found']);
        }

        $previewImage = $this->createPdfPreviewImage($pdfPath);

        if ($previewImage) {
            $config['preview_image'] = $previewImage;
            $config['updated_at'] = now()->toISOString();
            $this->saveLetterheadConfig($config);

            return response()->json(['success' => true, 'preview_image' => $previewImage]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to generate preview']);
        }
    }

    public function saveSalesConfig(Request $request)
    {
        $validated = $request->validate([
            'invoice_prefix' => 'required|string|max:10',
            'invoice_starting_number' => 'required|integer|min:1'
        ]);

        $config = $this->getLetterheadConfig();
        $config['invoice_prefix'] = $validated['invoice_prefix'];
        $config['invoice_starting_number'] = $validated['invoice_starting_number'];

        $this->saveLetterheadConfig($config);

        return response()->json([
            'success' => true,
            'message' => 'Sales record settings saved successfully'
        ]);
    }

    private function getLetterheadConfig()
    {
        $user = auth()->user();
        $activeShop = $user->getActiveShop();

        if (!$activeShop) {
            return $this->getDefaultConfig();
        }

        $configPath = storage_path('app/letterhead_config_shop_' . $activeShop->id . '.json');
        if (File::exists($configPath)) {
            return json_decode(File::get($configPath), true);
        }
        return $this->getDefaultConfig();
    }

    private function createPdfPreviewUsingCommandLine($pdfPath)
    {
        try {
            // Check for ImageMagick on Windows and Unix systems
            $magickPath = null;

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows - check common installation paths
                $windowsPaths = [
                    'magick',  // If in PATH
                    'C:\Program Files\ImageMagick-7.1.1-Q16-HDRI\magick.exe',
                    'C:\Program Files\ImageMagick-7.1.0-Q16-HDRI\magick.exe',
                    'C:\Program Files (x86)\ImageMagick-7.1.1-Q16-HDRI\magick.exe',
                    'C:\ImageMagick\magick.exe',
                    'C:\Program Files\ImageMagick-7.1.1-Q16\magick.exe',
                    'C:\Program Files\ImageMagick-7.1.0-Q16\magick.exe'
                ];

                foreach ($windowsPaths as $path) {
                    if ($path === 'magick') {
                        // Test if magick is in PATH
                        $output = shell_exec('magick -version 2>&1');
                        if ($output && stripos($output, 'ImageMagick') !== false) {
                            $magickPath = 'magick';
                            break;
                        }
                    } elseif (file_exists($path)) {
                        $magickPath = $path;
                        break;
                    }
                }
            } else {
                // Unix/Linux - use which command
                $magickPath = trim(shell_exec('which magick'));
                if (empty($magickPath)) {
                    $magickPath = trim(shell_exec('which convert'));
                }
            }

            if (empty($magickPath)) {
                \Log::info('ImageMagick not found - PDF preview unavailable. Install ImageMagick to enable PDF previews.');
                return null;
            }

            if (!file_exists($pdfPath)) {
                \Log::warning('PDF file not found: ' . $pdfPath);
                return null;
            }

            $user = auth()->user();
            $activeShop = $user->getActiveShop();
            $shopId = $activeShop ? $activeShop->id : 'default';
            $previewFilename = 'letterhead_preview_shop_' . $shopId . '_' . time() . '.png';
            $previewPath = public_path('letterheads/' . $previewFilename);

            // Ensure letterheads directory exists
            $letterheadDir = public_path('letterheads');
            if (!is_dir($letterheadDir)) {
                mkdir($letterheadDir, 0755, true);
            }

            // Use ImageMagick command to create preview
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows command format
                $command = sprintf(
                    '"%s" -density 150 -quality 95 "%s[0]" -resize 595x842! -background white -alpha remove "%s" 2>&1',
                    $magickPath,
                    $pdfPath,
                    $previewPath
                );
            } else {
                // Unix command format
                $command = sprintf(
                    '%s -density 150 -quality 95 %s[0] -resize 595x842! -background white -alpha remove %s 2>&1',
                    escapeshellcmd($magickPath),
                    escapeshellarg($pdfPath),
                    escapeshellarg($previewPath)
                );
            }

            $output = shell_exec($command);

            if (file_exists($previewPath) && filesize($previewPath) > 0) {
                \Log::info('PDF preview created successfully using command line: ' . $previewFilename);
                return $previewFilename;
            } else {
                // More detailed error logging
                $errorMsg = 'Failed to create PDF preview using command line.';
                if (!empty($output)) {
                    $errorMsg .= ' Output: ' . $output;
                }
                if (strpos($output, 'no decode delegate') !== false) {
                    $errorMsg .= ' - This usually means Ghostscript is not properly installed or configured with ImageMagick.';
                }
                \Log::warning($errorMsg);
                return null;
            }
        } catch (\Exception $e) {
            \Log::error('Failed to create PDF preview using command line: ' . $e->getMessage());
            return null;
        }
    }

    private function saveLetterheadConfig($config)
    {
        $user = auth()->user();
        $activeShop = $user->getActiveShop();

        if (!$activeShop) {
            return false;
        }

        $configPath = storage_path('app/letterhead_config_shop_' . $activeShop->id . '.json');

        // Save JSON without BOM to ensure PHP can read it properly
        $jsonContent = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        File::put($configPath, $jsonContent);

        return true;
    }

    private function deleteOldLetterhead()
    {
        $user = auth()->user();
        $activeShop = $user->getActiveShop();

        if (!$activeShop) {
            return;
        }

        $letterheadDir = public_path('letterheads');
        if (File::isDirectory($letterheadDir)) {
            // Only delete this shop's letterhead files
            $shopFiles = glob($letterheadDir . '/letterhead_shop_' . $activeShop->id . '.*');
            $shopPreviews = glob($letterheadDir . '/letterhead_preview_shop_' . $activeShop->id . '_*');

            foreach (array_merge($shopFiles, $shopPreviews) as $file) {
                if (File::exists($file)) {
                    File::delete($file);
                }
            }
        }
    }

    private function checkPdfProcessingCapability()
    {
        // Check if Imagick extension is available
        if (extension_loaded('imagick')) {
            try {
                $imagick = new Imagick();
                $formats = $imagick->queryFormats('PDF');
                $imagick->destroy();
                if (!empty($formats)) {
                    return ['status' => true, 'method' => 'php_imagick'];
                }
            } catch (\Exception $e) {
                // Fall through to command line check
            }
        }

        // Check command line ImageMagick
        $magickPath = trim(shell_exec('which magick'));
        if (empty($magickPath)) {
            $magickPath = trim(shell_exec('which convert'));
        }

        if (!empty($magickPath)) {
            return ['status' => true, 'method' => 'command_line'];
        }

        return ['status' => false, 'method' => 'none'];
    }

    private function createPdfPreviewImage($pdfPath)
    {
        try {
            if (!extension_loaded('imagick')) {
                // Try using command-line ImageMagick as fallback
                return $this->createPdfPreviewUsingCommandLine($pdfPath);
            }

            if (!file_exists($pdfPath)) {
                \Log::warning('PDF file not found: ' . $pdfPath);
                return null;
            }

            $imagick = new Imagick();

            // Set high resolution for better quality, then scale down for exact dimensions
            $imagick->setResolution(150, 150);

            // Read only the first page of the PDF
            $imagick->readImage($pdfPath . '[0]');

            // Set format to PNG for best quality
            $imagick->setImageFormat('png');
            $imagick->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE);
            $imagick->setImageBackgroundColor('white');

            // Get original dimensions
            $originalWidth = $imagick->getImageWidth();
            $originalHeight = $imagick->getImageHeight();

            // Always resize to exact A4 dimensions for consistent canvas
            $imagick->resizeImage(595, 842, Imagick::FILTER_LANCZOS, 1, true);

            // Enhance image quality
            $imagick->enhanceImage();
            $imagick->setImageCompressionQuality(95);

            $user = auth()->user();
            $activeShop = $user->getActiveShop();
            $shopId = $activeShop ? $activeShop->id : 'default';
            $previewFilename = 'letterhead_preview_shop_' . $shopId . '_' . time() . '.png';
            $previewPath = public_path('letterheads/' . $previewFilename);

            // Ensure letterheads directory exists
            $letterheadDir = public_path('letterheads');
            if (!is_dir($letterheadDir)) {
                mkdir($letterheadDir, 0755, true);
            }

            // Write the preview
            $result = $imagick->writeImage($previewPath);

            // Clean up
            $imagick->clear();
            $imagick->destroy();

            if ($result && file_exists($previewPath)) {
                \Log::info('PDF preview created successfully: ' . $previewFilename);
                return $previewFilename;
            } else {
                \Log::warning('Failed to write PDF preview file');
                return null;
            }
        } catch (\Exception $e) {
            $errorMsg = 'Failed to create PDF preview using PHP Imagick: ' . $e->getMessage();
            if (strpos($e->getMessage(), 'no decode delegate') !== false) {
                $errorMsg .= ' - This usually means Ghostscript is not properly installed or configured with ImageMagick.';
            }
            \Log::error($errorMsg);
            return null;
        }
    }    private function getDefaultConfig()
    {
        return [
            'letterhead_file' => null,
            'letterhead_type' => 'image',
            'preview_image' => null,
            'invoice_prefix' => 'INV',
            'invoice_starting_number' => 1,
            'table_width' => 480,
            'merge_offset' => [
                'x' => 0,
                'y' => 0,
                'unit' => 'mm',
            ],
            'positions' => [
                ['field' => 'product_name', 'x' => 50, 'y' => 130, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'customer_name', 'x' => 50, 'y' => 150, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'customer_phone', 'x' => 50, 'y' => 170, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'customer_address', 'x' => 50, 'y' => 190, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'customer_email', 'x' => 50, 'y' => 210, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'items_table', 'x' => 50, 'y' => 240, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'total_section', 'x' => 350, 'y' => 520, 'font_size' => 10, 'font_weight' => 'normal'],
                ['field' => 'warranty_section', 'x' => 50, 'y' => 620, 'font_size' => 10, 'font_weight' => 'normal'],
            ],
            'element_toggles' => [
                'customer_name' => true,
                'customer_phone' => true,
                'customer_address' => true,
                'customer_email' => true,
            ]
        ];
    }

    private function getPdfProcessingDiagnostics()
    {
        $diagnostics = [
            'php_imagick' => extension_loaded('imagick'),
            'imagemagick_binary' => null,
            'system_info' => [
                'os' => PHP_OS,
                'is_windows' => strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ]
        ];

        // Try to find ImageMagick binary
        if ($diagnostics['system_info']['is_windows']) {
            $possiblePaths = [
                'C:\Program Files\ImageMagick-7*\magick.exe',
                'C:\ImageMagick\magick.exe',
                'magick.exe'
            ];
        } else {
            $possiblePaths = [
                '/usr/bin/convert',
                '/usr/local/bin/convert',
                '/opt/homebrew/bin/convert',
                'convert'
            ];
        }

        foreach ($possiblePaths as $path) {
            if ($diagnostics['system_info']['is_windows']) {
                $testCommand = "where magick 2>NUL";
            } else {
                $testCommand = "which convert 2>/dev/null";
            }

            $output = [];
            $returnVar = 0;
            exec($testCommand, $output, $returnVar);

            if ($returnVar === 0 && !empty($output)) {
                $diagnostics['imagemagick_binary'] = $output[0];
                break;
            }
        }

        return $diagnostics;
    }

    // Job Sheet Letterhead Methods
    public function jobLetterheadIndex()
    {
        $config = $this->getJobLetterheadConfig();

        // Get the latest job for testing
        $latestJob = \App\Models\Job::latest()->first();
        $testJobId = $latestJob ? $latestJob->id : null;

        // Add system diagnostics for PDF processing
        $diagnostics = $this->getPdfProcessingDiagnostics();

        return view('letterhead.job-letterhead', compact('config', 'testJobId', 'diagnostics'));
    }

    public function uploadJobLetterhead(Request $request)
    {
        try {
            $user = auth()->user();
            $userId = $user ? $user->id : null;

            $validator = \Validator::make($request->all(), [
                'letterhead' => 'required|mimes:jpeg,png,jpg,gif,pdf|max:5120'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
            }

            if (!$request->hasFile('letterhead')) {
                return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
            }

            $file = $request->file('letterhead');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename = 'job_letterhead_' . time() . '.' . $extension;

            // Store file
            $file->move(public_path('letterheads'), $filename);

            // Generate preview for PDF
            $previewFilename = null;
            if ($extension === 'pdf') {
                $previewFilename = $this->createPdfPreviewImage(public_path('letterheads/' . $filename));
                if (!$previewFilename) {
                    \Log::warning('PDF preview generation failed for job letterhead');
                }
            }

            // Save to shop config
            $shop = auth()->user()->shop ?? \App\Models\Shop::first();
            if ($shop) {
                // Ensure we get an array
                $config = is_array($shop->job_letterhead_config) ? $shop->job_letterhead_config : [];
                $config['letterhead_file'] = $filename;
                $config['letterhead_type'] = $extension === 'pdf' ? 'pdf' : 'image';
                if ($previewFilename) {
                    $config['preview_image'] = $previewFilename;
                }
                $config['uploaded_at'] = now()->toDateTimeString();
                $shop->job_letterhead_config = $config;
                $shop->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Job letterhead uploaded successfully',
                'filename' => $filename,
                'preview' => $previewFilename
            ]);

        } catch (\Exception $e) {
            \Log::error('Job letterhead upload failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function saveJobPositions(Request $request)
    {
        try {
            $positions = $request->input('positions', []);

            $shop = auth()->user()->shop ?? \App\Models\Shop::first();
            if ($shop) {
                $config = is_array($shop->job_letterhead_config) ? $shop->job_letterhead_config : [];
                $config['positions'] = $positions;
                $shop->job_letterhead_config = $config;
                $shop->save();
            }

            return response()->json(['success' => true, 'message' => 'Job positions saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getJobPositions()
    {
        $config = $this->getJobLetterheadConfig();
        return response()->json(['positions' => $config['positions'] ?? []]);
    }

    public function saveJobItemsAlignment(Request $request)
    {
        try {
            $alignment = $request->input('alignment', []);

            $shop = auth()->user()->shop ?? \App\Models\Shop::first();
            if ($shop) {
                $config = is_array($shop->job_letterhead_config) ? $shop->job_letterhead_config : [];
                $config['items_alignment'] = $alignment;
                $shop->job_letterhead_config = $config;
                $shop->save();
            }

            return response()->json(['success' => true, 'message' => 'Job items alignment saved successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getJobItemsAlignment()
    {
        $config = $this->getJobLetterheadConfig();
        return response()->json(['alignment' => $config['items_alignment'] ?? []]);
    }

    protected function getJobLetterheadConfig()
    {
        $shop = auth()->user()->shop ?? \App\Models\Shop::first();
        if (!$shop) return [];

        $config = $shop->job_letterhead_config;
        return is_array($config) ? $config : [];
    }
}
