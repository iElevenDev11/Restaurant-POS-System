<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventoryItems = Inventory::all();
        return view('admin.inventory.index', compact('inventoryItems'));
    }

    public function create()
    {
        return view('admin.inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'reorder_level' => 'required|numeric|min:0',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory item created successfully');
    }

    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'reorder_level' => 'required|numeric|min:0',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory item updated successfully');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory item deleted successfully');
    }
}
