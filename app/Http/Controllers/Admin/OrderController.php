<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $this->filtered($request)
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'quantity', 'total', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'   => $orders,
            'total'    => Order::count(),
            'products' => Order::products(),
        ]);
    }

    public function export(Request $request)
    {
        $orders = $this->filtered($request)->latest()->get();

        $filename = 'orders-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'reference', 'full_name', 'email', 'mobile_number', 'product', 'size',
                'quantity', 'unit_price', 'total', 'notify_consent', 'submitted_at',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->reference,
                    $order->full_name,
                    $order->email,
                    $order->mobile_number,
                    $order->product_name,
                    $order->size,
                    $order->quantity,
                    $order->unit_price,
                    $order->total,
                    $order->notify_consent ? 'yes' : 'no',
                    $order->created_at->toDateTimeString(),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function filtered(Request $request)
    {
        return Order::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->product, fn($q) => $q->where('product', $request->product))
            ->when($request->size, fn($q) => $q->where('size', $request->size));
    }
}
