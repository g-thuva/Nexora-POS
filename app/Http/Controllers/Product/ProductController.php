<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Services\KpiService;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorHTML;

class ProductController extends Controller
{
    public function index()
    {
        // Eager-load category, unit, and creator relations to avoid N+1
        $products = Product::with(['category:id,name', 'unit:id,name', 'creator:id,name'])
            ->latest()
            ->get();

        // Use KpiService to compute lightweight counts (avoids loading collections in view)
        $kpi = new KpiService();

        $cards = [
            'total_products' => $kpi->totalProducts(),
            'in_stock' => $kpi->inStockCount(10),
            'low_stock' => $kpi->lowStockCount(),
            'categories' => $kpi->categoriesCount(),
        ];

        // Get all categories for quick access shortcuts
        $categories = Category::all(['id', 'name'])->take(5);

        return view('products.index', [
            'products' => $products,
            'productCards' => $cards,
            'categories' => $categories,
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::all(['id', 'name']);
        $units = Unit::all(['id', 'name', 'slug']);
        $warranties = \App\Models\Warranty::all(['id', 'name', 'duration', 'slug']);

        if ($request->has('category')) {
            $categories = Category::whereSlug($request->get('category'))->get();
        }

        if ($request->has('unit')) {
            $units = Unit::whereSlug($request->get('unit'))->get();
        }

        return view('products.create', [
            'categories' => $categories,
            'units' => $units,
            'warranties' => $warranties,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        // Remove code validation - let the model auto-generate SKU
        // If code is provided and exists, let the model handle uniqueness
        $existingProduct = null;
        if ($request->filled('code')) {
            $existingProduct = Product::where('code', $request->get('code'))->first();

            if ($existingProduct) {
                // Clear the code to let model auto-generate
                $request->merge(['code' => null]);
            }
        }

        try {
            $product = Product::create($request->all());

            /**
             * Handle image upload
             */
            if ($request->hasFile('product_image')) {
                $file = $request->file('product_image');
                $filename = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

                // Validate file before uploading
                if ($file->isValid()) {
                    $file->storeAs('products/', $filename, 'public');
                    $product->update([
                        'product_image' => $filename
                    ]);
                } else {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid image file'
                        ], 422);
                    }
                    return back()->withErrors(['product_image' => 'Invalid image file']);
                }
            }

            $message = 'Product has been created with code: ' . $product->code;

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'product' => $product
                ]);
            }

            return redirect()
                ->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong while creating the product'
                ], 500);
            }

            return back()->withErrors(['error' => 'Something went wrong while creating the product']);
        }
    }

    // Helper method to generate a unique product code
    private function generateUniqueCode()
    {
        // Delegate to the model's PRD-based generator
        return Product::generateSku();
    }

    public function show(Product $product)
    {
        // Generate a barcode
        $generator = new BarcodeGeneratorHTML();

        $barcode = $generator->getBarcode($product->code, $generator::TYPE_CODE_128);

        return view('products.show', [
            'product' => $product,
            'barcode' => $barcode,
        ]);
    }

    public function edit(Product $product)
    {
        return view('products.edit', [
            'categories' => Category::all(),
            'units' => Unit::all(),
            'warranties' => \App\Models\Warranty::all(['id', 'name', 'duration']),
            'product' => $product
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->except('product_image'));

        if ($request->hasFile('product_image')) {

            // Delete old image if exists
            if ($product->product_image) {
                \Storage::disk('public')->delete('products/' . $product->product_image);
            }

            // Prepare new image
            $file = $request->file('product_image');
            $fileName = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();

            // Store new image to public storage
            $file->storeAs('products/', $fileName, 'public');

            // Save new image name to database
            $product->update([
                'product_image' => $fileName
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been updated!');
    }

    public function addStock(Request $request, $product)
    {
        try {
            // Manually find the product by ID to avoid route model binding issues
            $product = Product::findOrFail($product);

            $validated = $request->validate([
                'add_quantity' => 'required|integer|min:1|max:10000',
                'notes' => 'nullable|string|max:500',
            ]);

            $oldQuantity = $product->quantity;
            $addQuantity = $validated['add_quantity'];
            $newQuantity = $oldQuantity + $addQuantity;

            // Use increment for atomic update (prevents race conditions)
            $product->increment('quantity', $addQuantity);

            $message = "Stock updated successfully! Added {$addQuantity} units. New stock: {$newQuantity}";

            return response()->json([
                'success' => true,
                'message' => $message,
                'new_quantity' => $newQuantity
            ]);
        } catch (\Exception $e) {
            \Log::error('Stock update failed', [
                'product_id' => $product ?? null,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock'
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        /**
         * Delete photo if exists.
         */
        if ($product->product_image) {
            \Storage::disk('public')->delete('products/' . $product->product_image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product has been deleted!');
    }
}
