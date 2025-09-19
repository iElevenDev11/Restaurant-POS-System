@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Inventory Item Details</h1>
            <div>
                <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 mr-2">Back to Inventory</a>
                <a href="{{ route('inventory.edit', $inventory) }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit Item</a>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-lg border mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Name</p>
                    <p class="font-medium">{{ $inventory->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p>
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            {{ $inventory->quantity <= $inventory->reorder_level ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $inventory->quantity <= $inventory->reorder_level ? 'Low Stock' : 'In Stock' }}
                        </span>
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Quantity</p>
                    <p class="font-medium">{{ $inventory->quantity }} {{ $inventory->unit }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Reorder Level</p>
                    <p class="font-medium">{{ $inventory->reorder_level }} {{ $inventory->unit }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Created At</p>
                    <p class="font-medium">{{ $inventory->created_at->format('M d, Y H:i') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Last Updated</p>
                    <p class="font-medium">{{ $inventory->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <h2 class="text-xl font-medium">Stock Management</h2>
        </div>

        <div class="bg-white p-4 rounded-lg shadow border mt-4">
            <h3 class="font-medium mb-4">Update Stock</h3>

            <form action="{{ route('inventory.update', $inventory) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @csrf
                @method('PUT')

                <input type="hidden" name="name" value="{{ $inventory->name }}">
                <input type="hidden" name="unit" value="{{ $inventory->unit }}">
                <input type="hidden" name="reorder_level" value="{{ $inventory->reorder_level }}">

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Current Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ $inventory->quantity }}" step="0.01" min="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>

                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
