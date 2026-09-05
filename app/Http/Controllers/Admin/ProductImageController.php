<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageOptimizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $maxOrder = $product->images()->max('order') ?? 0;
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $index => $file) {
            $path = ImageOptimizer::optimizeAndStore($file, 'products', 1600, 85);
            $maxOrder++;

            $product->images()->create([
                'image_path' => $path,
                'variant_id' => $request->input('variant_id'),
                'order' => $maxOrder,
                'is_primary' => (! $hasPrimary && $index === 0),
            ]);
        }

        return redirect()->route('admin.products.edit', $product)->with('success', 'Gambar berhasil diunggah.');
    }

    public function setPrimary(Product $product, ProductImage $image): RedirectResponse
    {
        // Unset primary on all other images for this product
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Gambar utama berhasil diatur.');
    }

    public function reorder(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['integer'],
        ]);

        foreach ($request->input('order') as $position => $imageId) {
            ProductImage::where('id', $imageId)
                ->where('product_id', $product->id)
                ->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product, ProductImage $image): RedirectResponse
    {
        if ($image->image_path && ! filter_var($image->image_path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $wasPrimary = $image->is_primary;
        $image->delete();

        // If primary was deleted, assign to next image if available
        if ($wasPrimary) {
            $next = $product->images()->orderBy('order')->first();
            if ($next) {
                $next->update(['is_primary' => true]);
            }
        }

        return redirect()->route('admin.products.edit', $product)->with('success', 'Gambar berhasil dihapus.');
    }
}
