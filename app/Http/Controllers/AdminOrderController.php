<?php

namespace App\Http\Controllers;

use App\DataTables\OrdersDataTable;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdated;

class AdminOrderController extends Controller
{
    public function index(OrdersDataTable $dataTable, Request $request)
    {
        $searchParam = $request->input('search');
        $search = is_array($searchParam) ? ($searchParam['value'] ?? '') : trim((string) $searchParam);
        $statusId = (int) $request->input('status_id', 0);

        $statuses = OrderStatus::orderBy('status_id')->get(['status_id', 'status_name']);

        $metrics = [
            'total' => Order::count(),
            'pending' => Order::where('status_id', $this->statusIdByName($statuses, 'Pending'))->count(),
            'processing' => Order::where('status_id', $this->statusIdByName($statuses, 'Processing'))->count(),
            'shipped' => Order::where('status_id', $this->statusIdByName($statuses, 'Shipped'))->count(),
            'delivered' => Order::where('status_id', $this->statusIdByName($statuses, 'Delivered'))->count(),
            'cancelled' => Order::where('status_id', $this->statusIdByName($statuses, 'Cancelled'))->count(),
        ];

        return $dataTable->render('admin.order.index', [
            'statuses' => $statuses,
            'statusId' => $statusId,
            'search' => $search,
            'metrics' => $metrics,
        ]);
    }

    public function show(int $orderId)
    {
        $order = Order::with(['user', 'status', 'paymentMethod', 'items.product'])
            ->findOrFail($orderId);

        $statuses = OrderStatus::orderBy('status_id')->get(['status_id', 'status_name']);

        $statusLogs = OrderStatusLog::where('order_id', $order->order_id)
            ->with(['status', 'changedBy'])
            ->orderByDesc('changed_at')
            ->get();

        $subtotal = 0.0;
        foreach ($order->items as $item) {
            $subtotal += (float) $item->unit_price * (int) $item->quantity;
        }

        $shipping = (float) ($order->shipping_fee ?? 0);
        $taxRateSetting = (float) ($order->tax_rate ?? 0);

        $taxAmount = $subtotal > 0 ? ($subtotal * ($taxRateSetting / 100)) : 0;
        $total = $subtotal + $shipping + $taxAmount;

        return view('admin.order.show', [
            'order' => $order,
            'statuses' => $statuses,
            'statusLogs' => $statusLogs,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'taxAmount' => $taxAmount,
            'taxRateSetting' => $taxRateSetting,
            'total' => $total,
        ]);
    }

    public function updateStatus(Request $request, int $orderId)
    {
        $data = $request->validate([
            'status_id' => ['required', 'integer', 'exists:order_statuses,status_id'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::findOrFail($orderId);

        $oldStatusId = (int) $order->status_id;
        $newStatusId = (int) $data['status_id'];

        if ($oldStatusId === $newStatusId) {
            return redirect()
                ->back()
                ->with('success', 'Order status is already set to the selected value.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $order->loadMissing('items');
        $statuses = OrderStatus::get();
        $cancelledStatusId = $this->statusIdByName($statuses, 'Cancelled');

        DB::transaction(function () use ($order, $data, $user, $oldStatusId, $newStatusId, $cancelledStatusId) {
            $order->status_id = $newStatusId;
            $order->updated_at = now();
            $order->save();

            OrderStatusLog::create([
                'order_id' => $order->order_id,
                'status_id' => $newStatusId,
                'changed_by' => $user->user_id,
                'changed_at' => now(),
                'note' => $data['note'] ?? null,
            ]);

            if ($oldStatusId !== $cancelledStatusId && $newStatusId === $cancelledStatusId) {
                // Stock restoration on cancellation
                foreach ($order->items as $item) {
                    \App\Models\Product::where('product_id', $item->product_id)
                        ->increment('stock_qty', $item->quantity);

                    \App\Models\RestockTransaction::create([
                        'product_id' => $item->product_id,
                        'supplier_id' => null,
                        'transaction_type' => 'add',
                        'managed_by' => $user->user_id,
                        'quantity_added' => $item->quantity,
                        'unit_cost' => 0,
                        'notes' => 'Cancellation Restoration: Order #' . $order->order_id,
                        'restocked_at' => now(),
                    ]);
                }
            } elseif ($oldStatusId === $cancelledStatusId && $newStatusId !== $cancelledStatusId) {
                // Stock deduction when un-cancelling
                foreach ($order->items as $item) {
                    \App\Models\Product::where('product_id', $item->product_id)
                        ->decrement('stock_qty', $item->quantity);

                    \App\Models\RestockTransaction::create([
                        'product_id' => $item->product_id,
                        'supplier_id' => null,
                        'transaction_type' => 'remove',
                        'managed_by' => $user->user_id,
                        'quantity_added' => -$item->quantity,
                        'unit_cost' => 0,
                        'notes' => 'Un-Cancellation Deduction: Order #' . $order->order_id,
                        'restocked_at' => now(),
                    ]);
                }
            }
        });

        Mail::to($order->user->email, $order->user->full_name)->send(new OrderStatusUpdated($order, $data['note'] ?? null));

        return redirect()
            ->route('admin.order.show', $order->order_id)
            ->with('success', 'Order status updated successfully.');
    }

    private function statusIdByName($statuses, string $name): int
    {
        $match = $statuses->first(function ($status) use ($name) {
            return strcasecmp((string) $status->status_name, $name) === 0;
        });

        return $match ? (int) $match->status_id : 0;
    }
}
