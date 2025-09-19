@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Staff Management</h1>
            <a href="{{ route('staff.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add Staff</a>
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
                        <th class="py-2 px-4 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($staff as $user)
                        <tr>
                            <td class="py-2 px-4">{{ $user->id }}</td>
                            <td class="py-2 px-4">{{ $user->name }}</td>
                            <td class="py-2 px-4">{{ $user->email }}</td>
                            <td class="py-2 px-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <a href="{{ route('staff.show', $user) }}" class="text-blue-600 hover:text-blue-900 mr-2">View</a>
                                <a href="{{ route('staff.edit', $user) }}" class="text-green-600 hover:text-green-900 mr-2">Edit</a>

                                @if($user->id !== auth()->id())
                                    <form action="{{ route('staff.destroy', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-4 text-center text-gray-500">No staff members found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
