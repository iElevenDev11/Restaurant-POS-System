@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <h1 class="text-2xl font-semibold mb-6">Cashier Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <!-- Today's Sales Card -->
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-blue-800">Your Sales Today</h2>
                <p class="text-2xl font-bold">{{ number_format($todaySales, 2) }}</p>
            </div>

            <!-- Completed Orders Card -->
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-green-800">Orders Completed Today</h2>
                <p class="text-2xl font-bold">{{ $completedOrders }}</p>
            </div>

            <!-- Active Orders Card -->
            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <h2 class="text-lg font-medium text-yellow-800">Active Orders</h2>
                <p class="text-2xl font-bold">{{ $activeOrders->count() }}</p>
            </div>
        </div>

        <!-- Table Status Grid -->
        <div class="mb-8">
            <h2 class="text-xl font-medium mb-4">Table Status</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($tables as $table)
                    <a href="{{ route('tables.show', $table) }}" class="block">
                        <div class="aspect-square flex flex-col items-center justify-center rounded-lg shadow border
                            {{ $table->status == 'available' ? 'bg-green-100 border-green-300' :
                               ($table->status == 'occupied' ? 'bg-red-100 border-red-300' : 'bg-yellow-100 border-yellow-300') }}">
                            <span class="text-lg font-bold">{{ $table->name }}</span>
                            <span class="text-sm">{{ ucfirst($table->status) }}</span>
                            <span class="text-xs mt-1">Capacity: {{ $table->capacity }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white p-4 rounded-lg shadow border">
            <h2 class="text-xl font-medium mb-4">Active Orders</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table</th>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activeOrders as $order)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $order->table ? $order->table->name : 'Takeout' }}</td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $order->status == 'new' ? 'bg-blue-100 text-blue-800' :
                                           ($order->status == 'preparing' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b text-right">{{ number_format($order->total_amount, 2) }}</td>
                                <td class="py-2 px-4 border-b text-center">
                                    <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                    <a href="{{ route('orders.edit', $order) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                                    <a href="{{ route('payments.create', ['order' => $order->id]) }}" class="text-purple-600 hover:text-purple-900">Payment</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-2 px-4 text-center text-gray-500">No active orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
