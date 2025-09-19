@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Staff Details</h1>
            <div>
                <a href="{{ route('staff.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2">Back to Staff</a>
                <a href="{{ route('staff.edit', $staff) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit Staff</a>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-medium">{{ $staff->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Email</p>
                    <p class="font-medium">{{ $staff->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Role</p>
                    <p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $staff->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($staff->role) }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $staff->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($staff->status) }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Created At</p>
                    <p class="font-medium">{{ $staff->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Last Updated</p>
                    <p class="font-medium">{{ $staff->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <h2 class="text-xl font-medium mb-4">Recent Activity</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($staff->orders()->latest()->limit(10)->get() as $order)
                        <tr>
                            <td class="py-2 px-4">
                                <a href="{{ route('orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">
                                    Order #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-2 px-4">{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td class="py-2 px-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $order->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($order->status == 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-right">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-4 text-center text-gray-500">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
