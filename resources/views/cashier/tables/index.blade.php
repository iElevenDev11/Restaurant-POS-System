@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Tables</h1>
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

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($tables as $table)
                <a href="{{ route('tables.show', $table) }}" class="block">
                    <div class="aspect-square flex flex-col items-center justify-center rounded-lg shadow border
                        {{ $table->status == 'available' ? 'bg-green-100 border-green-300' :
                           ($table->status == 'occupied' ? 'bg-red-100 border-red-300' : 'bg-yellow-100 border-yellow-300') }}">
                        <span class="text-lg font-bold">{{ $table->name }}</span>
                        <span class="text-sm">{{ ucfirst($table->status) }}</span>
                        <span class="text-xs mt-1">Capacity: {{ $table->capacity }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
