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
                $sortable = ['full_name', 'total', 'status', 'created_at'];
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
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('statusChangedBy');

        return view('admin.orders.show', [
            'order'   => $order,
            'product' => Order::findProduct($order->product),
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Order::STATUSES))],
        ]);

        if ($order->status === $validated['status']) {
            return back()->with('error', "Order {$order->reference} is already {$order->status_label}.");
        }

        $order->markStatus($validated['status'], $request->user());

        return back()->with('success', "Order {$order->reference} marked as {$order->status_label}.");
    }

    public function export(Request $request)
    {
        $orders = $this->filtered($request)->latest()->get();

        $filename = 'orders-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'reference', 'full_name', 'email', 'mobile_number', 'product', 'size',
                'quantity', 'unit_price', 'total', 'status', 'notify_consent', 'submitted_at',
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
                    $order->status,
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
            ->when($request->size, fn($q) => $q->where('size', $request->size))
            ->when($request->status, fn($q) => $q->where('status', $request->status));
    }
}
