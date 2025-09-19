<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Menu Item') }}
            </h2>
            <a href="{{ route('menu.show', $menu->id) }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                View Item
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-8">
                    <!-- Form Header -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Edit "{{ $menu->name }}"</h3>
                        <p class="text-gray-600">Update your menu item information below.</p>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</h4>
                                    <ul class="text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('menu.update', $menu->id) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Menu Item Name *
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $menu->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror"
                                   placeholder="Enter menu item name"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-300 @enderror"
                                      placeholder="Describe your menu item...">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                Price ($) *
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" 
                                       name="price" 
                                       id="price" 
                                       step="0.01"
                                       value="{{ old('price', $menu->price) }}"
                                       class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-300 @enderror"
                                       placeholder="0.00"
                                       required>
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category *
                            </label>
                            <select name="category_id" 
                                    id="category_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-300 @enderror"
                                    required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if ($category->id == $menu->category_id) selected @endif>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Image</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- File Upload -->
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload New Image
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="image" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Click to upload</span> or drag and drop
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                            <input type="file" 
                                                   name="image" 
                                                   id="image" 
                                                   class="hidden"
                                                   accept="image/*">
                                        </label>
                                    </div>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Current Image -->
                                @if ($menu->image)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Current Image
                                        </label>
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $menu->image) }}" 
                                                 alt="{{ $menu->name }}"
                                                 class="w-full h-32 object-cover rounded-lg shadow-sm">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-opacity duration-200 rounded-lg"></div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Current image will be replaced if you upload a new one</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Is Available -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Availability Status
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_available" 
                                       id="is_available"
                                       value="1" 
                                       @if ($menu->is_available) checked @endif
                                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="is_available" class="ml-2 text-sm text-gray-700">
                                    Available for ordering
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Uncheck to mark this item as unavailable</p>
                            @error('is_available')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Menu Item
                            </button>
                            
                            <a href="{{ route('menu.index') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Menu List
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>