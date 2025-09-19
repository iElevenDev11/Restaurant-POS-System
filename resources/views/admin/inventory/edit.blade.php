@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Inventory Item</h1>
            <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Inventory</a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventory.update', $inventory) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $inventory->name) }}"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $inventory->quantity) }}" step="0.01" min="0"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>

                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                    <input type="text" name="unit" id="unit" value="{{ old('unit', $inventory->unit) }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                </div>
            </div>

            <div class="mb-4">
                <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                <input type="number" name="reorder_level" id="reorder_level" value="{{ old('reorder_level', $inventory->reorder_level) }}" step="0.01" min="0"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                <p class="text-sm text-gray-500 mt-1">The minimum quantity at which you should reorder this item</p>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update Inventory Item</button>
            </div>
        </form>
    </div>
</div>
@endsection
