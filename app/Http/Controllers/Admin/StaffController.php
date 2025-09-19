<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::all();
        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,cashier'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    public function show(User $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $staff->id],
            'role' => ['required', 'in:admin,cashier'],
            'status' => ['required', 'in:active,inactive'],
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Update user data
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->role = $request->role;
        $staff->status = $request->status;

        // Update password if provided
        if ($request->filled('password')) {
            $staff->password = Hash::make($request->password);
        }

        $staff->save();

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully');
    }

    public function destroy(User $staff)
    {
        // Prevent deleting yourself
        if ($staff->id === Auth::user()->id) {
            return redirect()->route('staff.index')
                ->with('error', 'You cannot delete your own account');
        }

        // Check if user has associated orders
        if ($staff->orders()->exists()) {
            return redirect()->route('staff.index')
                ->with('error', 'Cannot delete staff member with associated orders');
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully');
    }
}
