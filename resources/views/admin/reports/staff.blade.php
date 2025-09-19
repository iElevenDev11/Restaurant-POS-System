@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Staff Performance Report</h1>
            <a href="{{ route('admin.reports') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Reports</a>
        </div>

        <!-- Date Range Filter -->
        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <form action="{{ route('admin.reports.staff') }}" method="GET" class="flex flex-wrap items-end gap-4">
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

        <!-- Staff Performance -->
        <div class="bg-white p-4 rounded-lg shadow border mb-6">
            <h2 class="text-lg font-medium mb-4">Staff Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                                    <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg. Order Value</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($staffPerformance as $staff)
                                    <tr>
                                        <td class="py-2 px-4">{{ $staff->name }}</td>
                                        <td class="py-2 px-4 text-right">{{ $staff->total_orders }}</td>
                                        <td class="py-2 px-4 text-right">${{ number_format($staff->total_sales, 2) }}</td>
                                        <td class="py-2 px-4 text-right">
                                            ${{ $staff->total_orders > 0 ? number_format($staff->total_sales / $staff->total_orders, 2) : '0.00' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-4 text-center text-gray-500">No staff performance data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <div class="h-64">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Staff Performance Chart
        const staffCtx = document.getElementById('staffChart').getContext('2d');
        const staffChart = new Chart(staffCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($staffPerformance as $staff)
                        '{{ $staff->name }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Total Sales',
                    data: [
                        @foreach($staffPerformance as $staff)
                            {{ $staff->total_sales }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
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
    });
</script>
@endsection
