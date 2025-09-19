@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Process Payment for Order #{{ $order->id }}</h1>
            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Order</a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-medium mb-4">Order Summary</h2>

                <div class="bg-white p-4 rounded-lg shadow border">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Order Type:</span>
                        <span>{{ ucfirst($order->order_type) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">
                            {{ $order->order_type == 'dine-in' ? 'Table:' : 'Customer:' }}
                        </span>
                        <span>
                            @if($order->order_type == 'dine-in')
                                {{ $order->table ? $order->table->name : 'N/A' }}
                            @else
                                {{ $order->customer_name ?: 'Takeout' }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Status:</span>
                        <span>{{ ucfirst($order->status) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Total Amount:</span>
                        <span>${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="font-medium">Amount Paid:</span>
                        <span>${{ number_format($paidAmount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>Remaining:</span>
                        <span>${{ number_format($remainingAmount, 2) }}</span>
                    </div>
                </div>

                @if($order->payments->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-md font-medium mb-2">Previous Payments</h3>
                        <div class="bg-white rounded-lg shadow border overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="py-2 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="py-2 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($order->payments as $payment)
                                        <tr>
                                            <td class="py-2 px-4">{{ ucfirst($payment->payment_method) }}</td>
                                            <td class="py-2 px-4 text-right">${{ number_format($payment->amount, 2) }}</td>
                                            <td class="py-2 px-4 text-center">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <h2 class="text-lg font-medium mb-4">Payment Details</h2>

                <form action="{{ route('payments.store') }}" method="POST" class="bg-white p-4 rounded-lg shadow border">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">

                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Payment Amount</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">$</span>
                            </div>
                            <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $remainingAmount }}" value="{{ $remainingAmount }}"
                                   class="pl-7 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">Maximum amount: ${{ number_format($remainingAmount, 2) }}</p>
                    </div>

                    <div class="mb-4">
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                        <select name="payment_method" id="payment_method"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile">Mobile Payment</option>
                        </select>
                    </div>

                    <div id="card_details" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Details</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="card_number" class="block text-xs text-gray-500 mb-1">Card Number</label>
                                <input type="text" name="payment_details[card_number]" id="card_number" placeholder="**** **** **** ****"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label for="card_holder" class="block text-xs text-gray-500 mb-1">Card Holder</label>
                                <input type="text" name="payment_details[card_holder]" id="card_holder"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <div id="mobile_details" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Payment Details</label>
                        <div>
                            <label for="transaction_id" class="block text-xs text-gray-500 mb-1">Transaction ID</label>
                            <input type="text" name="payment_details[transaction_id]" id="transaction_id"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="payment_details[notes]" id="notes" rows="2"
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">Process Payment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethod = document.getElementById('payment_method');
        const cardDetails = document.getElementById('card_details');
        const mobileDetails = document.getElementById('mobile_details');

        paymentMethod.addEventListener('change', function() {
            if (this.value === 'card') {
                cardDetails.classList.remove('hidden');
                mobileDetails.classList.add('hidden');
            } else if (this.value === 'mobile') {
                cardDetails.classList.add('hidden');
                mobileDetails.classList.remove('hidden');
            } else {
                cardDetails.classList.add('hidden');
                mobileDetails.classList.add('hidden');
            }
        });
    });
</script>
@endsection
