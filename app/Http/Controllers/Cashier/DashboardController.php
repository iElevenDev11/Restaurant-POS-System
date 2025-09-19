<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all tables with their status
        $tables = Table::all();

        // Get active orders (new, preparing, ready)
        $activeOrders = Order::whereIn('status', ['new', 'preparing', 'ready'])
            ->with(['table', 'orderItems.menuItem'])
            ->latest()
            ->get();

        // Get today's completed orders for the logged-in cashier
        $completedOrders = Order::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // Get today's sales amount for the logged-in cashier
        $todaySales = Order::where('user_id', Auth::id())
            ->whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return view('cashier.dashboard', compact(
            'tables',
            'activeOrders',
            'completedOrders',
            'todaySales'
        ));
    }

    public function kitchenDisplay()
    {
        // Get orders that need to be prepared or are being prepared
        $orders = Order::whereIn('status', ['new', 'preparing'])
            ->with(['orderItems.menuItem', 'table'])
            ->latest()
            ->get();

        return view('cashier.kitchen-display', compact('orders'));
    }
}
