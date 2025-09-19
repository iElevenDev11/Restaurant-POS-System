@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Sales Report</h1>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Reports</a>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <form action="{{ route('admin.reports.sales') }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Apply Filter</button>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Total Sales</h2>
                <p class="text-2xl font-bold text-blue-600">${{ number_format($totalSales, 2) }}</p>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Total Orders</h2>
                <p class="text-2xl font-bold text-green-600">{{ $totalOrders }}</p>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Average Order Value</h2>
                <p class="text-2xl font-bold text-purple-600">
                    ${{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 2) : '0.00' }}
                </p>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border">
                <h2 class="text-lg font-medium text-gray-700 mb-2">Date Range</h2>
                <p class="text-md font-medium">
                    {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white p-4 rounded-lg shadow border mb-6">
            <h2 class="text-lg font-medium mb-4">Sales Trend</h2>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Daily Sales Table -->
        <div class="bg-white p-4 rounded-lg shadow border mb-6">
            <h2 class="text-lg font-medium mb-4">Daily Sales</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Average Order</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sales as $day)
                            <tr>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</td>
                                <td class="py-2 px-4 text-right">{{ $day->order_count }}</td>
                                <td class="py-2 px-4 text-right">${{ number_format($day->total_sales, 2) }}</td>
                                <td class="py-2 px-4 text-right">
                                    ${{ number_format($day->total_sales / $day->order_count, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-center text-gray-500">No sales data found for the selected period</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="bg-white p-4 rounded-lg shadow border">
            <h2 class="text-lg font-medium mb-4">Payment Methods</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($paymentMethods as $method)
                                    <tr>
                                        <td class="py-2 px-4">{{ ucfirst($method->payment_method) }}</td>
                                        <td class="py-2 px-4 text-right">{{ $method->count }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($method->total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-gray-500">No payment data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="h-64">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sales Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($sales as $day)
                        '{{ \Carbon\Carbon::parse($day->date)->format("M d") }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Daily Sales',
                    data: [
                        @foreach($sales as $day)
                            {{ $day->total_sales }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Payment Methods Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($paymentMethods as $method)
                        '{{ ucfirst($method->payment_method) }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($paymentMethods as $method)
                            {{ $method->total }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(139, 92, 246, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: $${value.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
