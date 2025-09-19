@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Category: {{ $category->name }}</h1>
            <div>
                <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2">Back to Categories</a>
                <a href="{{ route('categories.edit', $category) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit Category</a>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <h2 class="text-lg font-medium mb-2">Category Details</h2>
            <p><strong>ID:</strong> {{ $category->id }}</p>
            <p><strong>Name:</strong> {{ $category->name }}</p>
            <p><strong>Description:</strong> {{ $category->description ?: 'No description' }}</p>
            <p><strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i') }}</p>
            <p><strong>Last Updated:</strong> {{ $category->updated_at->format('M d, Y H:i') }}</p>
        </div>

        <h2 class="text-xl font-medium mb-4">Menu Items in this Category</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($category->menuItems as $item)
                        <tr>
                            <td class="py-2 px-4">{{ $item->id }}</td>
                            <td class="py-2 px-4">{{ $item->name }}</td>
                            <td class="py-2 px-4 text-right">${{ number_format($item->price, 2) }}</td>
                            <td class="py-2 px-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('menu.show', $item) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">No menu items in this category</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
