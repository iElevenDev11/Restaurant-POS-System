@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Payment #{{ $payment->id }}</h1>
            <div>
                <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2">Back to Payments</a>
                <a href="{{ route('orders.show', $payment->order) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">View Order</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-medium mb-4">Payment Information</h2>

                <div class="bg-white p-4 rounded-lg shadow border">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Payment ID</p>
                            <p class="font-medium">{{ $payment->id }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Order</p>
                            <p class="font-medium">
                                <a href="{{ route('orders.show', $payment->order) }}" class="text-blue-600 hover:text-blue-900">
                                    Order #{{ $payment->order_id }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Amount</p>
                            <p class="font-medium">${{ number_format($payment->amount, 2) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Payment Method</p>
                            <p class="font-medium">{{ ucfirst($payment->payment_method) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Date</p>
                            <p class="font-medium">{{ $payment->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    @if($payment->payment_details)
                        <div class="mt-4 pt-4 border-t">
                            <h3 class="font-medium mb-2">Payment Details</h3>

                            <dl>
                                @foreach($payment->payment_details as $key => $value)
                                    <div class="grid grid-cols-3 gap-4 py-2">
                                        <dt class="text-sm font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="text-sm text-gray-900 col-span-2">{{ $value }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="text-lg font-medium mb-4">Order Summary</h2>

                <div class="bg-white p-4 rounded-lg shadow border">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Order Type</p>
                            <p class="font-medium">{{ ucfirst($payment->order->order_type) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">
                                {{ $payment->order->order_type == 'dine-in' ? 'Table' : 'Customer' }}
                            </p>
                            <p class="font-medium">
                                @if($payment->order->order_type == 'dine-in')
                                    {{ $payment->order->table ? $payment->order->table->name : 'N/A' }}
                                @else
                                    {{ $payment->order->customer_name ?: 'Takeout' }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $payment->order->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($payment->order->status == 'cancelled' ? 'bg-red-100 text-red-800' :
                                       ($payment->order->status == 'new' ? 'bg-blue-100 text-blue-800' :
                                       ($payment->order->status == 'preparing' ? 'bg-yellow-100 text-yellow-800' :
                                       ($payment->order->status == 'ready' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800')))) }}">
                                    {{ ucfirst($payment->order->status) }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600">Payment Status</p>
                            <p>
                                <span class="px-2 py-1 rounded text-xs font-semibold
                                    {{ $payment->order->payment_status == 'paid' ? 'bg-green-100 text-green-800' :
                                       ($payment->order->payment_status == 'refunded' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($payment->order->payment_status) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Total Order Amount:</span>
                            <span>${{ number_format($payment->order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">This Payment:</span>
                            <span>${{ number_format($payment->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
