@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Order #{{ $order->id }}</h1>
            <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Back to Order</a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('orders.update', $order) }}" method="POST" id="orderForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-lg font-medium mb-4">Order Information</h2>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                        <select name="status" id="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                            <option value="new" {{ $order->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                            <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="served" {{ $order->status == 'served' ? 'selected' : '' }}>Served</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Order Type</label>
                        <div class="text-gray-900 font-medium">{{ ucfirst($order->order_type) }}</div>
                    </div>

                    @if($order->order_type == 'dine-in')
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Table</label>
                            <div class="text-gray-900 font-medium">{{ $order->table ? $order->table->name : 'N/A' }}</div>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <div class="text-gray-900 font-medium">{{ $order->customer_name ?: 'Takeout' }}</div>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 class="text-lg font-medium mb-4">Order Summary</h2>

                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Total Items:</span>
                            <span id="total_items">{{ $order->orderItems->sum('quantity') }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-medium">Subtotal:</span>
                            <span id="subtotal">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span id="total">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium mb-4">Menu Items</h2>

                <div class="mb-4">
                    <div class="flex space-x-2 overflow-x-auto pb-2">
                        <button type="button" class="category-tab px-4 py-2 bg-blue-600 text-white rounded-t-lg" data-category="all">All</button>
                        @foreach($categories as $category)
                            <button type="button" class="category-tab px-4 py-2 bg-gray-200 text-gray-700 rounded-t-lg" data-category="{{ $category->id }}">{{ $category->name }}</button>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border">
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="menu_items_container">
                            @foreach($categories as $category)
                                @foreach($category->menuItems as $item)
                                    <div class="menu-item bg-white p-3 rounded shadow-sm border cursor-pointer"
                                         data-id="{{ $item->id }}"
                                         data-name="{{ $item->name }}"
                                         data-price="{{ $item->price }}"
                                         data-category="{{ $category->id }}">
                                        <div class="font-medium">{{ $item->name }}</div>
                                        <div class="text-sm text-gray-600 mb-1">{{ Str::limit($item->description, 50) }}</div>
                                        <div class="text-blue-600 font-bold">${{ number_format($item->price, 2) }}</div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-medium mb-4">Selected Items</h2>

                <div class="bg-white rounded-lg border overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200" id="selected_items_container">
                            <tr id="no_items_row" style="{{ $order->orderItems->count() > 0 ? 'display: none;' : '' }}">
                                <td colspan="6" class="py-4 px-4 text-center text-gray-500">No items selected</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700" id="submit_order">Update Order</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryTabs = document.querySelectorAll('.category-tab');
        const menuItems = document.querySelectorAll('.menu-item');
        const selectedItemsContainer = document.getElementById('selected_items_container');
        const noItemsRow = document.getElementById('no_items_row');
        const totalItemsElement = document.getElementById('total_items');
        const subtotalElement = document.getElementById('subtotal');
        const totalElement = document.getElementById('total');
        const orderForm = document.getElementById('orderForm');

        // Load existing order items
        @foreach($order->orderItems as $item)
            addItemToSelection({
                id: {{ $item->menu_item_id }},
                name: "{{ $item->menuItem->name }}",
                price: {{ $item->unit_price }},
                quantity: {{ $item->quantity }},
                notes: "{{ $item->notes ?? '' }}",
                itemId: {{ $item->id }}
            });
        @endforeach

        // Handle category tab clicks
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                categoryTabs.forEach(t => t.classList.remove('bg-blue-600', 'text-white'));
                categoryTabs.forEach(t => t.classList.add('bg-gray-200', 'text-gray-700'));
                this.classList.remove('bg-gray-200', 'text-gray-700');
                this.classList.add('bg-blue-600', 'text-white');

                const categoryId = this.dataset.category;

                menuItems.forEach(item => {
                    if (categoryId === 'all' || item.dataset.category === categoryId) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Handle menu item clicks
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                const itemId = this.dataset.id;
                const itemName = this.dataset.name;
                const itemPrice = parseFloat(this.dataset.price);

                // Check if item already exists in the selected items
                const existingItem = document.querySelector(`#selected_items_container tr[data-id="${itemId}"]`);

                if (existingItem) {
                    // Increment quantity
                    const quantityInput = existingItem.querySelector('.quantity-input');
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    updateItemSubtotal(existingItem);
                } else {
                    // Add new item
                    addItemToSelection({
                        id: itemId,
                        name: itemName,
                        price: itemPrice,
                        quantity: 1,
                        notes: ''
                    });
                }
            });
        });

        function addItemToSelection(item) {
            const newRow = document.createElement('tr');
            newRow.dataset.id = item.id;
            newRow.dataset.price = item.price;

            const index = document.querySelectorAll('#selected_items_container tr[data-id]').length;
            const itemIdField = item.itemId ? `<input type="hidden" name="items[${index}][id]" value="${item.itemId}">` : '';

            newRow.innerHTML = `
                <td class="py-2 px-4">${item.name}</td>
                <td class="py-2 px-4 text-right">$${item.price.toFixed(2)}</td>
                <td class="py-2 px-4 text-center">
                    <input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" class="quantity-input w-16 text-center rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <input type="hidden" name="items[${index}][menu_item_id]" value="${item.id}">
                    ${itemIdField}
                </td>
                <td class="py-2 px-4">
                    <input type="text" name="items[${index}][notes]" value="${item.notes}" placeholder="Special instructions" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </td>
                <td class="py-2 px-4 text-right item-subtotal">$${(item.price * item.quantity).toFixed(2)}</td>
                <td class="py-2 px-4 text-center">
                    <button type="button" class="text-red-600 hover:text-red-900 remove-item">Remove</button>
                </td>
            `;

            selectedItemsContainer.appendChild(newRow);

            // Add event listeners to the new row
            const quantityInput = newRow.querySelector('.quantity-input');
            quantityInput.addEventListener('change', function() {
                if (parseInt(this.value) < 1) this.value = 1;
                updateItemSubtotal(newRow);
            });

            const removeButton = newRow.querySelector('.remove-item');
            removeButton.addEventListener('click', function() {
                newRow.remove();
                updateOrderSummary();
                checkNoItems();
                reindexItems();
            });

            updateOrderSummary();
            checkNoItems();
        }

        // Update item subtotal
        function updateItemSubtotal(row) {
            const price = parseFloat(row.dataset.price);
            const quantity = parseInt(row.querySelector('.quantity-input').value);
            const subtotal = price * quantity;
            row.querySelector('.item-subtotal').textContent = `$${subtotal.toFixed(2)}`;
            updateOrderSummary();
        }

        // Update order summary
        function updateOrderSummary() {
            const items = selectedItemsContainer.querySelectorAll('tr[data-id]');
            let totalItems = 0;
            let subtotal = 0;

            items.forEach(item => {
                const quantity = parseInt(item.querySelector('.quantity-input').value);
                const price = parseFloat(item.dataset.price);
                totalItems += quantity;
                subtotal += price * quantity;
            });

            totalItemsElement.textContent = totalItems;
            subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
            totalElement.textContent = `$${subtotal.toFixed(2)}`;
        }

        // Check if no items are selected
        function checkNoItems() {
            const items = selectedItemsContainer.querySelectorAll('tr[data-id]');
            if (items.length === 0) {
                noItemsRow.style.display = 'table-row';
            } else {
                noItemsRow.style.display = 'none';
            }
        }

        // Reindex items when one is removed
        function reindexItems() {
            const items = selectedItemsContainer.querySelectorAll('tr[data-id]');
            items.forEach((item, index) => {
                const quantityInput = item.querySelector('input[name^="items"][name$="[quantity]"]');
                const menuItemIdInput = item.querySelector('input[name^="items"][name$="[menu_item_id]"]');
                const notesInput = item.querySelector('input[name^="items"][name$="[notes]"]');
                const itemIdInput = item.querySelector('input[name^="items"][name$="[id]"]');

                if (quantityInput) quantityInput.name = `items[${index}][quantity]`;
                if (menuItemIdInput) menuItemIdInput.name = `items[${index}][menu_item_id]`;
                if (notesInput) notesInput.name = `items[${index}][notes]`;
                if (itemIdInput) itemIdInput.name = `items[${index}][id]`;
            });
        }

        // Form submission validation
        orderForm.addEventListener('submit', function(e) {
            const items = selectedItemsContainer.querySelectorAll('tr[data-id]');
            if (items.length === 0) {
                e.preventDefault();
                alert('Please add at least one item to the order');
                return;
            }
        });
    });
</script>
@endsection
