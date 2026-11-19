<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderConfirmed;
use App\Mail\OrderPaymentRejected;
use App\Mail\OrderShipped;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $this->filtered($request)
            ->with('items')
            ->when(true, function ($q) use ($request) {
                $sortable = ['full_name', 'total', 'status', 'created_at'];
                $sort = in_array($request->sort, $sortable) ? $request->sort : 'created_at';
                $dir = $request->direction === 'asc' ? 'asc' : 'desc';
                return $q->orderBy($sort, $dir);
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders'        => $orders,
            'total'         => Order::count(),
            'statusCounts'  => Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
            'products'      => Product::ordered()->pluck('name'),
            'statuses'      => Order::STATUSES,
            'shippingAreas' => config('shop.shipping_areas'),
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items', 'statusChangedBy', 'paymentVerifiedBy']);

        return view('admin.orders.show', [
            'order'    => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    // Payment checks out: confirm the order and (by default) email the buyer
    public function confirm(Request $request, Order $order): RedirectResponse
    {
        if (! in_array($order->status, ['pending', 'payment_submitted'])) {
            return back()->with('error', "Order {$order->reference} is already {$order->status_label}.");
        }

        $order->markStatus('confirmed', $request->user(), [
            'payment_verified_at' => now(),
            'payment_verified_by' => $request->user()->id,
        ]);

        if ($request->boolean('send_email')) {
            $this->sendConfirmation($order);

            return back()->with('success', "Order {$order->reference} confirmed and confirmation email sent to {$order->email}.");
        }

        return back()->with('success', "Order {$order->reference} confirmed. No email was sent.");
    }

    public function reject(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        if (! in_array($order->status, ['pending', 'payment_submitted'])) {
            return back()->with('error', "Only orders awaiting payment review can be rejected.");
        }

        $order->markStatus('rejected', $request->user(), [
            'rejection_reason' => $validated['reason'] ?? null,
        ]);

        Mail::to($order->email)->send(new OrderPaymentRejected($order));

        return back()->with('success', "Order {$order->reference} rejected, items returned to stock, and the buyer has been emailed.");
    }

    // Records the LBC tracking number and emails it. Also used to correct a tracking number.
    public function ship(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string|max:50',
        ]);

        if (! in_array($order->status, ['confirmed', 'shipped'])) {
            return back()->with('error', 'Confirm the payment before adding a tracking number.');
        }

        $tracking = trim($validated['tracking_number']);

        if ($order->status === 'shipped') {
            $order->forceFill(['tracking_number' => $tracking])->save();
        } else {
            $order->markStatus('shipped', $request->user(), [
                'tracking_number' => $tracking,
                'shipped_at'      => now(),
            ]);
        }

        Mail::to($order->email)->send(new OrderShipped($order));

        return back()->with('success', "Tracking number {$tracking} saved and emailed to {$order->email}.");
    }

    public function resendConfirmation(Order $order): RedirectResponse
    {
        if (! in_array($order->status, ['confirmed', 'shipped', 'completed'])) {
            return back()->with('error', 'Only confirmed orders can be sent a confirmation email.');
        }

        $this->sendConfirmation($order);

        return back()->with('success', "Confirmation email sent to {$order->email}.");
    }

    // Manual override for any status. Sends no email.
    public function updateStatus(Request $request, Order $order): RedirectResponse
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

    public function updateNotes(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $order->forceFill(['admin_notes' => $validated['admin_notes']])->save();

        return back()->with('success', 'Notes saved.');
    }

    public function export(Request $request)
    {
        $orders = $this->filtered($request)->with('items')->latest()->get();

        $filename = 'orders-' . now()->format('Y-m-d') . '.csv';

        // One row per item line so it can be pivoted for packing
        return response()->streamDownload(function () use ($orders) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'reference', 'status', 'full_name', 'email', 'mobile_number',
                'product', 'color', 'size', 'quantity', 'unit_price', 'line_total',
                'shipping_area', 'shipping_fee', 'order_total', 'address', 'tracking_number', 'submitted_at',
            ]);

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    fputcsv($handle, [
                        $order->reference,
                        $order->status,
                        $order->full_name,
                        $order->email,
                        $order->mobile_number,
                        $item->product_name,
                        $item->color,
                        $item->size,
                        $item->quantity,
                        $item->unit_price,
                        $item->line_total,
                        $order->shipping_area_label,
                        $order->shipping_fee,
                        $order->total,
                        $order->shipping_address,
                        $order->tracking_number,
                        $order->created_at->toDateTimeString(),
                    ]);
                }
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function sendConfirmation(Order $order): void
    {
        Mail::to($order->email)->send(new OrderConfirmed($order));

        $order->forceFill(['confirmation_emailed_at' => now()])->save();
    }

    private function filtered(Request $request)
    {
        return Order::query()
            ->when($request->search, fn($q) => $q->search($request->search))
            ->when($request->product, fn($q) => $q->whereHas('items', fn($i) => $i->where('product_name', $request->product)))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->shipping_area, fn($q) => $q->where('shipping_area', $request->shipping_area));
    }
}
