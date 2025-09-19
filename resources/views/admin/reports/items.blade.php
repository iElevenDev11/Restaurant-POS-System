@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Menu Items Report</h1>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Reports</a>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <form action="{{ route('admin.reports.items') }}" method="GET" class="flex flex-wrap items-end gap-4">
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

        <!-- Category Performance -->
        <div class="bg-white p-4 rounded-lg shadow border mb-6">
            <h2 class="text-lg font-medium mb-4">Category Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Items Sold</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="py-2 px-4">{{ $category->name }}</td>
                                        <td class="py-2 px-4 text-right">{{ $category->total_quantity }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($category->total_sales, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 px-4 text-center text-gray-500">No category data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="h-64">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Items -->
        <div class="bg-white p-4 rounded-lg shadow border">
            <h2 class="text-lg font-medium mb-4">Top Selling Items</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                            <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity Sold</th>
                            <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                            <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($topItems as $item)
                            <tr>
                                <td class="py-2 px-4">{{ $item->name }}</td>
                                <td class="py-2 px-4">{{ $item->category_name }}</td>
                                <td class="py-2 px-4 text-right">{{ $item->total_quantity }}</td>
                                <td class="py-2 px-4 text-right">${{ number_format($item->total_sales, 2) }}</td>
                                <td class="py-2 px-4 text-center">
                                    <a href="{{ route('menu.show', $item->id) }}" class="text-blue-600 hover:text-blue-900">View Item</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">No item data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'pie',
            data: {
                labels: [
                    @foreach($categories as $category)
                        '{{ $category->name }}',
                    @endforeach
                ],
                datasets: [{
                    data: [
                        @foreach($categories as $category)
                            {{ $category->total_sales }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(139, 92, 246, 0.7)',
                        'rgba(244, 114, 182, 0.7)',
                        'rgba(251, 191, 36, 0.7)',
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(139, 92, 246, 1)',
                        'rgba(244, 114, 182, 1)',
                        'rgba(251, 191, 36, 1)',
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
