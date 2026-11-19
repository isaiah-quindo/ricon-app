<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductColor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::ordered()->with(['colors', 'variants'])->get();

        return view('admin.products.index', [
            'products' => $products,
            'sold'     => $this->soldByVariant(),
        ]);
    }

    public function create()
    {
        return view('admin.products.create', ['product' => new Product(['is_active' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = DB::transaction(function () use ($request) {
            $product = Product::create($this->validated($request));
            $this->saveColors($request, $product);
            $product->syncVariants();

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "{$product->name} created. Enter stock below to start selling.");
    }

    public function edit(Product $product)
    {
        $product->load(['colors', 'variants']);

        return view('admin.products.edit', [
            'product' => $product,
            'sold'    => $this->soldByVariant(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $product->update($this->validated($request, $product));
            $this->saveColors($request, $product);
            $product->syncVariants();
        });

        return back()->with('success', "{$product->name} saved.");
    }

    /**
     * Applies each edit as a change from the count the admin was looking at, so
     * orders placed while the page was open aren't put back on the shelf.
     */
    public function updateStock(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'stock'      => 'required|array',
            'stock.*'    => 'required|integer|min:0|max:100000',
            'original'   => 'required|array',
            'original.*' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated, $product) {
            foreach ($validated['stock'] as $variantId => $stock) {
                $delta = $stock - ($validated['original'][$variantId] ?? $stock);
                if ($delta === 0) {
                    continue;
                }

                $variant = $product->variants()->whereKey($variantId)->lockForUpdate()->first();
                $variant?->update(['stock' => max(0, $variant->stock + $delta)]);
            }
        });

        return back()->with('success', "Stock updated for {$product->name}.");
    }

    private function validated(Request $request, ?Product $product = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('name'))]);

        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'slug'             => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($product)],
            'eyebrow'          => 'nullable|string|max:60',
            'intro'            => 'nullable|string|max:1000',
            'description'      => 'nullable|string|max:2000',
            'price'            => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'usd_price'        => 'nullable|integer|min:0',
            'specs_text'       => 'nullable|string|max:2000',
            'sizes_text'       => 'nullable|string|max:255',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'boolean',
            'colors'               => 'required|array|min:1',
            'colors.*.id'          => 'nullable|uuid',
            'colors.*.label'       => 'required|string|max:60',
            'colors.*.hex'         => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'colors.*.front_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'colors.*.back_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'colors.*.remove_back' => 'nullable|boolean',
        ], [
            'colors.required'      => 'Add at least one color.',
            'colors.*.hex.regex'   => 'Use a hex color like #C9B68A.',
        ]);

        // "Fit: One size" per line → ["Fit" => "One size"]
        $specs = collect(preg_split('/\R/', $data['specs_text'] ?? ''))
            ->map(fn($line) => array_map('trim', explode(':', $line, 2)))
            ->filter(fn($pair) => count($pair) === 2 && $pair[0] !== '' && $pair[1] !== '')
            ->mapWithKeys(fn($pair) => [$pair[0] => $pair[1]])
            ->all();

        $sizes = collect(explode(',', $data['sizes_text'] ?? ''))
            ->map(fn($s) => strtoupper(trim($s)))
            ->filter()
            ->unique()
            ->values()
            ->all();

        return [
            'name'             => $data['name'],
            'slug'             => $data['slug'],
            'eyebrow'          => $data['eyebrow'] ?? null,
            'intro'            => $data['intro'] ?? null,
            'description'      => $data['description'] ?? null,
            'price'            => $data['price'],
            'compare_at_price' => $data['compare_at_price'] ?? null,
            'usd_price'        => $data['usd_price'] ?? null,
            'specs'            => $specs ?: null,
            'sizes'            => $sizes ?: null,
            'sort_order'       => $data['sort_order'] ?? 0,
            'is_active'        => $request->boolean('is_active'),
        ];
    }

    /**
     * Upserts the submitted color rows in order and deletes colors that were
     * removed from the form (their variants and stock go with them).
     */
    private function saveColors(Request $request, Product $product): void
    {
        $keep = [];
        $i = 0;

        foreach ($request->input('colors', []) as $key => $row) {
            $color = isset($row['id']) ? $product->colors()->find($row['id']) : null;
            $color ??= new ProductColor(['product_id' => $product->id]);

            $color->fill([
                'label'      => $row['label'],
                'hex'        => strtoupper($row['hex']),
                'sort_order' => $i++,
            ]);

            if ($file = $request->file("colors.{$key}.front_image")) {
                $color->image_front = $file->store('shop_products', 's3');
            }
            if ($file = $request->file("colors.{$key}.back_image")) {
                $color->image_back = $file->store('shop_products', 's3');
            } elseif (! empty($row['remove_back'])) {
                $color->image_back = null;
            }

            $color->save();
            $keep[] = $color->id;
        }

        $product->colors()->whereNotIn('id', $keep)->delete();
    }

    // Units tied up in orders that still hold stock, keyed by variant id
    private function soldByVariant(): array
    {
        return OrderItem::query()
            ->whereNotNull('product_variant_id')
            ->whereHas('order', fn($q) => $q->whereNotIn('status', Order::RELEASES_STOCK))
            ->selectRaw('product_variant_id, sum(quantity) as qty')
            ->groupBy('product_variant_id')
            ->pluck('qty', 'product_variant_id')
            ->map(fn($q) => (int) $q)
            ->all();
    }
}
