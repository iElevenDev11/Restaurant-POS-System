<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('cashier.tables.index', compact('tables'));
    }

    public function create()
    {
        return view('cashier.tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tables,name',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        Table::create($request->all());

        return redirect()->route('tables.index')->with('success', 'Table created successfully');
    }

    public function show(Table $table)
    {
        // Get active order for this table if any
        $activeOrder = Order::where('table_id', $table->id)
                    ->whereIn('status', ['new', 'preparing', 'ready', 'served'])
                    ->with(['orderItems.menuItem'])
                    ->first();

        // Get menu categories and items for creating new orders
        $categories = Category::with(['menuItems' => function ($query) {
            $query->where('is_available', true);
        }])->get();

        return view('cashier.tables.show', compact('table', 'activeOrder', 'categories'));
    }

    public function edit(Table $table)
    {
        return view('cashier.tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:tables,name,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,reserved',
        ]);

        $table->update($request->all());

        return redirect()->route('tables.index')->with('success', 'Table updated successfully');
    }

    public function destroy(Table $table)
    {
        // Check if table has any orders
        $hasOrders = $table->orders()->exists();

        if ($hasOrders) {
            return redirect()->route('tables.index')->with('error', 'Cannot delete table as it has associated orders');
        }

        $table->delete();

        return redirect()->route('tables.index')->with('success', 'Table deleted successfully');
    }
}
