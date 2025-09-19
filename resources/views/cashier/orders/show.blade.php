@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">


        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Order #{{ $order->id }}</h1>
            <div>
                <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2">Back to Orders</a>

                @if(!in_array($order->status, ['completed', 'cancelled']))
                    <a href="{{ route('orders.edit', $order) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 mr-2">Edit Order</a>
                @endif

                @if($order->payment_status == 'pending')
                    <a href="{{ route('payments.create', ['order' => $order->id]) }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Process Payment</a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-medium mb-4">Order Information</h2>

                <div class="bg-white p-4 rounded-lg shadow border">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Order Type</p>
                            <p class="font-medium">{{ ucfirst($order->order_type) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">
                                {{ $order->order_type == 'dine-in' ? 'Table' : 'Customer' }}
                            </p>
                            <p class="font-medium">
                                @if($order->order_type == 'dine-in')
                                    {{ $order->table ? $order->table->name : 'N/A' }}
                                @else
                                    {{ $order->customer_name ?: 'Takeout' }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' :
                                       ($order->status == 'new' ? 'bg-blue-100 text-blue-800' :
                                       ($order->status == 'preparing' ? 'bg-yellow-100 text-yellow-800' :
                                       ($order->status == 'ready' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800')))) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Payment Status</p>
                            <p>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' :
                                       ($order->payment_status == 'refunded' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Created By</p>
                            <p class="font-medium">{{ $order->user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Created At</p>
                            <p class="font-medium">{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-medium mb-4">Order Summary</h2>

                <div class="bg-white p-4 rounded-lg shadow border">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Total Items:</span>
                        <span>{{ $order->orderItems->sum('quantity') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Subtotal:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Total:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-medium mb-4">Order Items</h2>

            <div class="bg-white rounded-lg shadow border overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td class="py-3 px-4">{{ $item->menuItem->name }}</td>
                                <td class="py-3 px-4 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 px-4 text-center">{{ $item->quantity }}</td>
                                <td class="py-3 px-4">{{ $item->notes ?: '-' }}</td>
                                <td class="py-3 px-4 text-right">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="py-3 px-4 text-right font-bold">Total:</td>
                            <td class="py-3 px-4 text-right font-bold">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->payments->count() > 0)
            <div>
                <h2 class="text-lg font-medium mb-4">Payment History</h2>

                <div class="bg-white rounded-lg shadow border overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->payments as $payment)
                                <tr>
                                    <td class="py-3 px-4">{{ $payment->id }}</td>
                                    <td class="py-3 px-4">{{ ucfirst($payment->payment_method) }}</td>
                                    <td class="py-3 px-4 text-right">${{ number_format($payment->amount, 2) }}</td>
                                    <td class="py-3 px-4 text-center">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
