<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

/**
 * Guest checkout: the cart lives in the browser (localStorage) until the buyer
 * submits it on /shop/checkout together with their bank transfer receipt.
 */
class ShopController extends Controller
{
    public function index(): View
    {
        return view('shop.index', [
            'products' => Product::active()->ordered()->with(['colors', 'variants'])->get(),
            'catalog'  => Product::catalog(),
        ]);
    }

    public function checkout(): View
    {
        return view('shop.checkout', [
            'catalog'       => Product::catalog(),
            'shippingAreas' => config('shop.shipping_areas'),
            'bank'          => config('shop.bank'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items'              => 'required|array|min:1|max:30',
            'items.*.variant_id' => 'required|uuid',
            'items.*.quantity'   => 'required|integer|min:1|max:' . config('shop.max_quantity'),
            'full_name'          => 'required|string|max:255',
            'email'              => 'required|email|max:255',
            'mobile_number'      => 'required|string|min:7|max:50',
            'shipping_area'      => ['required', Rule::in(array_keys(config('shop.shipping_areas')))],
            'address_line'       => 'required_unless:shipping_area,pickup|nullable|string|max:255',
            'city'               => 'required_unless:shipping_area,pickup|nullable|string|max:120',
            'province'           => 'required_unless:shipping_area,pickup|nullable|string|max:120',
            'postal_code'        => 'required_unless:shipping_area,pickup|nullable|string|max:10',
            'proof_of_payment'   => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'payment_consent'    => 'accepted',
        ], [
            'items.required'               => 'Your cart is empty.',
            'payment_consent.accepted'     => 'Please confirm you have sent the payment.',
            '*.required_unless'            => 'This field is required for delivery.',
        ], [
            'address_line' => 'street address',
            'postal_code'  => 'ZIP code',
        ]);

        // Same item added twice (e.g. from two tabs) becomes one line
        $quantities = collect($validated['items'])
            ->groupBy('variant_id')
            ->map(fn($lines) => $lines->sum('quantity'));

        $variants = ProductVariant::with(['product', 'color'])
            ->whereIn('id', $quantities->keys())
            ->get()
            ->keyBy('id');

        if ($variants->count() !== $quantities->count() || $variants->contains(fn($v) => ! $v->product->is_active)) {
            throw ValidationException::withMessages([
                'items' => 'Some items in your cart are no longer available. Please review your cart and try again.',
            ]);
        }

        // Cheap check before uploading the receipt; the locked check below is the real one
        $short = $quantities->filter(fn($qty, $id) => $variants[$id]->stock < $qty);
        if ($short->isNotEmpty()) {
            throw ValidationException::withMessages([
                'items' => $short->map(fn($qty, $id) => sprintf(
                    '%s (%s): only %d left',
                    $variants[$id]->product->name,
                    $variants[$id]->label,
                    $variants[$id]->stock,
                ))->values()->all(),
            ]);
        }

        $area = $validated['shipping_area'];
        $pickup = $area === 'pickup';

        $path = $request->file('proof_of_payment')->store('shop_payment_proofs', 's3');

        $order = DB::transaction(function () use ($validated, $quantities, $variants, $area, $pickup, $path) {
            $subtotal = $quantities->sum(fn($qty, $id) => $variants[$id]->product->price * $qty);
            $shippingFee = config("shop.shipping_areas.{$area}.fee");

            $order = Order::create([
                'full_name'          => $validated['full_name'],
                'email'              => $validated['email'],
                'mobile_number'      => $validated['mobile_number'],
                'shipping_area'      => $area,
                'address_line'       => $pickup ? null : $validated['address_line'],
                'city'               => $pickup ? null : $validated['city'],
                'province'           => $pickup ? null : $validated['province'],
                'postal_code'        => $pickup ? null : $validated['postal_code'],
                'subtotal'           => $subtotal,
                'shipping_fee'       => $shippingFee,
                'total'              => $subtotal + $shippingFee,
                'payment_proof_path' => $path,
                'notify_consent'     => true,
            ]);

            foreach ($quantities as $id => $qty) {
                $variant = $variants[$id];

                $order->items()->create([
                    'product_variant_id' => $variant->id,
                    'product_name'       => $variant->product->name,
                    'color'              => $variant->color?->label,
                    'size'               => $variant->size,
                    'quantity'           => $qty,
                    'unit_price'         => $variant->product->price,
                    'line_total'         => $variant->product->price * $qty,
                ]);
            }

            $order->reserveStock();

            return $order;
        });

        return redirect()->route('shop.success')->with('order_reference', $order->reference);
    }

    public function success(Request $request): View|RedirectResponse
    {
        $reference = $request->session()->get('order_reference');
        $order = $reference ? Order::with('items')->firstWhere('reference', $reference) : null;

        if (! $order) {
            return redirect()->route('shop.index');
        }

        // Survive one refresh of the confirmation page
        $request->session()->reflash();

        return view('shop.success', ['order' => $order]);
    }
}
