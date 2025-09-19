@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Payments</h1>
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

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment ID</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="py-2 px-4">{{ $payment->id }}</td>
                            <td class="py-2 px-4">
                                <a href="{{ route('orders.show', $payment->order) }}" class="text-blue-600 hover:text-blue-900">
                                    Order #{{ $payment->order_id }}
                                </a>
                            </td>
                            <td class="py-2 px-4">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($payment->amount, 2) }}</td>
                            <td class="py-2 px-4 text-center">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>

                                @if($payment->order->status != 'completed')
                                    <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to refund this payment?')">Refund</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">No payments found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
