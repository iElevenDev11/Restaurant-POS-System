@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Menu Items</h1>
            <a href="{{ route('menu.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add New Item</a>
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
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="py-2 px-4 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($menuItems as $item)
                        <tr>
                            <td class="py-2 px-4">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . str_replace('menu-items/', 'menu-items/thumbnails/', $item->image)) }}"
                                         alt="{{ $item->name }}" class="h-12 w-16 object-cover rounded">
                                @else
                                    <div class="h-12 w-16 bg-gray-200 flex items-center justify-center rounded">
                                        <span class="text-gray-500 text-xs">No Image</span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-2 px-4">{{ $item->name }}</td>
                            <td class="py-2 px-4">{{ $item->category->name }}</td>
                            <td class="py-2 px-4 text-right">{{ number_format($item->price, 2) }}</td>
                            <td class="py-2 px-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $item->is_available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $item->is_available ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('menu.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                <a href="{{ route('menu.edit', $item) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>
                                <form action="{{ route('menu.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">No menu items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
