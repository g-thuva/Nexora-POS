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
        $existingProduct = Product::where('code', $request->get('code'))->first();

        if ($existingProduct) {
            $newCode = $this->generateUniqueCode();

            $request->merge(['code' => $newCode]);
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
                    return back()->withErrors(['product_image' => 'Invalid image file']);
                }
            }

            return redirect()
                ->back()
                ->with('success', 'Product has been created with code: ' . $product->code);

        } catch (\Exception $e) {
            // Handle any unexpected errors
            return back()->withErrors(['error' => 'Something went wrong while creating the product']);
        }
    }

    // Helper method to generate a unique product code
    private function generateUniqueCode()
    {
        do {
            $code = 'PC' . strtoupper(uniqid());
        } while (Product::where('code', $code)->exists());

        return $code;
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

    public function addStock(Request $request, Product $product)
    {
        \Log::info('AddStock called', [
            'product_id' => $product->id,
            'request_data' => $request->all(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson(),
            'accept_header' => $request->header('Accept'),
            'x_requested_with' => $request->header('X-Requested-With')
        ]);

        $request->validate([
            'add_quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldQuantity = $product->quantity;
        $addQuantity = $request->add_quantity;
        $newQuantity = $oldQuantity + $addQuantity;

        $product->update([
            'quantity' => $newQuantity,
        ]);

        $message = "Stock updated successfully! Added {$addQuantity} units. New stock: {$newQuantity}";

        \Log::info('Stock updated', ['message' => $message]);

        // Always return JSON for this endpoint
        return response()->json([
            'success' => true,
            'message' => $message,
            'new_quantity' => $newQuantity
        ]);
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
