<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\Table;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'user'])
                ->latest()
                ->paginate(10);

        return view('cashier.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $tables = Table::where('status', 'available')->get();
        $categories = Category::with(['menuItems' => function ($query) {
            $query->where('is_available', true);
        }])->get();

        $selectedTableId = $request->input('table_id');
        $selectedTable = null;

        if ($selectedTableId) {
            $selectedTable = Table::find($selectedTableId);
        }

        return view('cashier.orders.create', compact('tables', 'categories', 'selectedTable'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'customer_name' => 'nullable|string|max:255',
            'order_type' => 'required|in:dine-in,takeout',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the order
            $order = new Order();
            $order->table_id = $request->table_id;
            $order->user_id = Auth::id();
            $order->customer_name = $request->customer_name;
            $order->order_type = $request->order_type;
            $order->status = 'new';
            $order->payment_status = 'pending';
            $order->total_amount = 0;
            $order->save();

            // Add order items and calculate total
            $total = 0;

            foreach ($request->items as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->menu_item_id = $menuItem->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->unit_price = $menuItem->price;
                $orderItem->notes = $item['notes'] ?? null;
                $orderItem->save();

                $total += $menuItem->price * $item['quantity'];
            }

            // Update order total
            $order->total_amount = $total;
            $order->save();

            // Update table status if this is a dine-in order
            if ($request->order_type == 'dine-in' && $request->table_id) {
                $table = Table::find($request->table_id);
                $table->status = 'occupied';
                $table->save();
            }

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.menuItem', 'table', 'user']);
        return view('cashier.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Only allow editing of orders that are not completed or cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cannot edit completed or cancelled orders');
        }

        $order->load(['orderItems.menuItem', 'table']);

        $categories = Category::with(['menuItems' => function ($query) {
            $query->where('is_available', true);
        }])->get();

        return view('cashier.orders.edit', compact('order', 'categories'));
    }

    public function update(Request $request, Order $order)
    {
        // Only allow updating of orders that are not completed or cancelled
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Cannot update completed or cancelled orders');
        }

        $request->validate([
            'status' => 'required|in:new,preparing,ready,served,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:order_items,id',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string',
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Update order status
            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->status = $newStatus;

            // Handle table status changes based on order status
            if ($order->table_id) {
                $table = $order->table;

                // If order is cancelled or completed, free up the table
                if (in_array($newStatus, ['completed', 'cancelled']) && !in_array($oldStatus, ['completed', 'cancelled'])) {
                    $table->status = 'available';
                    $table->save();
                }

                // If order was cancelled/completed and is now active, occupy the table
                if (!in_array($newStatus, ['completed', 'cancelled']) && in_array($oldStatus, ['completed', 'cancelled'])) {
                    $table->status = 'occupied';
                    $table->save();
                }
            }

            // Get existing order items
            $existingItems = $order->orderItems->keyBy('id');
            $keepItemIds = [];

            // Update or create order items
            $total = 0;

            foreach ($request->items as $item) {
                $menuItem = MenuItem::find($item['menu_item_id']);

                if (isset($item['id']) && $existingItems->has($item['id'])) {
                    // Update existing item
                    $orderItem = $existingItems->get($item['id']);
                    $orderItem->menu_item_id = $menuItem->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->unit_price = $menuItem->price;
                    $orderItem->notes = $item['notes'] ?? null;
                    $orderItem->save();

                    $keepItemIds[] = $orderItem->id;
                } else {
                    // Create new item
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->menu_item_id = $menuItem->id;
                    $orderItem->quantity = $item['quantity'];
                    $orderItem->unit_price = $menuItem->price;
                    $orderItem->notes = $item['notes'] ?? null;
                    $orderItem->save();

                    $keepItemIds[] = $orderItem->id;
                }

                $total += $menuItem->price * $item['quantity'];
            }

            // Delete removed items
            foreach ($existingItems as $existingItem) {
                if (!in_array($existingItem->id, $keepItemIds)) {
                    $existingItem->delete();
                }
            }

            // Update order total
            $order->total_amount = $total;
            $order->save();

            DB::commit();

            return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating order: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update the order status from the kitchen display.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready,served,completed,cancelled',
        ]);

        try {
            DB::beginTransaction();

            // Update order status
            $oldStatus = $order->status;
            $newStatus = $request->status;
            $order->status = $newStatus;
            $order->save();

            // Handle table status changes if needed
            if ($order->table_id) {
                $table = $order->table;

                // If order is cancelled or completed, free up the table
                if (in_array($newStatus, ['completed', 'cancelled']) && !in_array($oldStatus, ['completed', 'cancelled'])) {
                    $table->status = 'available';
                    $table->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'updated_at' => $order->updated_at
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating order status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of orders that are new
        if ($order->status != 'new') {
            return redirect()->route('orders.index')
                ->with('error', 'Only new orders can be deleted');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Free up table if this was a dine-in order
            if ($order->table_id) {
                $table = $order->table;
                $table->status = 'available';
                $table->save();
            }

            // Delete order items
            $order->orderItems()->delete();

            // Delete the order
            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }
}
