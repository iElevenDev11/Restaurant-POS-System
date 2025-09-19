@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Reports</h1>
            <p class="text-gray-600">View and analyze your restaurant's performance</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.reports.sales') }}" class="block bg-white p-6 rounded-lg shadow border hover:shadow-md transition-shadow">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 text-blue-600 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-medium mb-2">Sales Report</h2>
                <p class="text-gray-600">View sales data, trends, and payment methods</p>
            </a>

            <a href="{{ route('admin.reports.items') }}" class="block bg-white p-6 rounded-lg shadow border hover:shadow-md transition-shadow">
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 text-green-600 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h2 class="text-lg font-medium mb-2">Menu Items Report</h2>
                <p class="text-gray-600">View top selling items and category performance</p>
            </a>

            <a href="{{ route('admin.reports.staff') }}" class="block bg-white p-6 rounded-lg shadow border hover:shadow-md transition-shadow">
                <div class="flex items-center justify-center w-12 h-12 bg-purple-100 text-purple-600 rounded-full mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-medium mb-2">Staff Performance</h2>
                <p class="text-gray-600">View staff sales and order statistics</p>
            </a>
        </div>
    </div>
</div>
@endsection
