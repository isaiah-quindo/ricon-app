<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop.index', [
            'products' => Order::products(),
        ]);
    }

    // Items live as sections on /shop; keep per-item URLs working for shared links
    public function show(string $product)
    {
        Order::findProduct($product) ?? abort(404);

        return redirect()->to(route('shop.index') . '#' . $product);
    }

    public function store(Request $request, string $product)
    {
        $item = Order::findProduct($product) ?? abort(404);

        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'mobile_number'  => 'required|string|min:7|max:50',
            'size'           => $item['sizes'] ? ['required', Rule::in($item['sizes'])] : 'prohibited',
            'quantity'       => 'required|integer|min:1|max:' . config('shop.max_quantity'),
            'notify_consent' => 'accepted',
        ]);

        // Price comes from the catalog, never from the request
        $order = Order::create([
            'full_name'      => $validated['full_name'],
            'email'          => $validated['email'],
            'mobile_number'  => $validated['mobile_number'],
            'product'        => $item['slug'],
            'product_name'   => $item['name'],
            'size'           => $validated['size'] ?? null,
            'quantity'       => $validated['quantity'],
            'unit_price'     => $item['price'],
            'total'          => $item['price'] * $validated['quantity'],
            'notify_consent' => true,
        ]);

        return response()->json([
            'status'    => 'created',
            'reference' => $order->reference,
        ]);
    }
}
