@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Categories</h1>
            <a href="{{ route('categories.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Category</a>
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
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Menu Items</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                        <tr>
                            <td class="py-2 px-4">{{ $category->id }}</td>
                            <td class="py-2 px-4">{{ $category->name }}</td>
                            <td class="py-2 px-4">{{ Str::limit($category->description, 50) }}</td>
                            <td class="py-2 px-4 text-center">{{ $category->menu_items_count }}</td>
                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('categories.show', $category) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                <a href="{{ route('categories.edit', $category) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-4 px-4 text-center text-gray-500">No categories found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
