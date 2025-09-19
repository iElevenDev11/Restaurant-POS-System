@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h1 class="text-2xl font-semibold mb-6">Admin Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Today's Sales Card -->
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-blue-800">Today's Sales</h2>
                <p class="text-2xl font-bold">{{ number_format($todaySales, 2) }}</p>
            </div>

            <!-- Today's Orders Card -->
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-green-800">Today's Orders</h2>
                <p class="text-2xl font-bold">{{ $todayOrders }}</p>
            </div>

            <!-- Low Stock Items Card -->
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-red-800">Low Stock Items</h2>
                <p class="text-2xl font-bold">{{ $lowStockItems->count() }}</p>
            </div>

            <!-- Total Menu Items Card -->
            <div class="bg-purple-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-purple-800">Menu Items</h2>
                <p class="text-2xl font-bold">{{ \App\Models\MenuItem::count() }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Selling Items -->
            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-xl font-medium mb-4">Top Selling Items</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSellingItems as $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b text-right">{{ $item->total_quantity }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="py-2 px-4 text-center text-gray-500">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-xl font-medium mb-4">Recent Orders</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $order->table ? $order->table->name : 'Takeout' }}</td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' :
                                           ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b text-right">{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-2 px-4 text-center text-gray-500">No recent orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-xl font-medium mb-4">Low Stock Items</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Current Quantity</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockItems as $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $item->name }}</td>
                                <td class="py-2 px-4 border-b text-right">{{ $item->quantity }} {{ $item->unit }}</td>
                                <td class="py-2 px-4 border-b text-right">{{ $item->reorder_level }} {{ $item->unit }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-2 px-4 text-center text-gray-500">No low stock items</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
