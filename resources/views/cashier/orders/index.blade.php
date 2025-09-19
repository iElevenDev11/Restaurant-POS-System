@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">

        {{-- ----------------------------------------- --}}
        <div class="flex justify-between items-center mb-6">

            <h1 class="text-2xl font-semibold">Orders</h1>

            <a href="{{ route('orders.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">New Order</a>

        </div>
        {{-- ----------------------------------------- --}}

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- ----------------------------------------- --}}

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Table/Customer</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr>
                            <td class="py-2 px-4">{{ $order->id }}</td>
                            <td class="py-2 px-4">{{ ucfirst($order->order_type) }}</td>
                            <td class="py-2 px-4">
                                @if($order->order_type == 'dine-in')
                                    {{ $order->table ? $order->table->name : 'N/A' }}
                                @else
                                    {{ $order->customer_name ?: 'Takeout' }}
                                @endif
                            </td>
                            <td class="py-2 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' :
                                       ($order->status == 'new' ? 'bg-blue-100 text-blue-800' :
                                       ($order->status == 'preparing' ? 'bg-yellow-100 text-yellow-800' :
                                       ($order->status == 'ready' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800')))) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' :
                                       ($order->payment_status == 'refunded' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>


                            <td class="py-2 px-4 text-right">${{ number_format($order->total_amount, 2) }}</td>


                            <td class="py-2 px-4 text-center">{{ $order->created_at->format('M d, Y H:i') }}</td>


                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>

                                @if(!in_array($order->status, ['completed', 'cancelled']))
                                    <a href="{{ route('orders.edit', $order) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                                @endif

                                @if($order->payment_status == 'pending')
                                    <a href="{{ route('payments.create', ['order' => $order->id]) }}" class="text-purple-600 hover:text-purple-900">Payment</a>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 px-4 text-center text-gray-500">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>

    </div>
</div>
@endsection
