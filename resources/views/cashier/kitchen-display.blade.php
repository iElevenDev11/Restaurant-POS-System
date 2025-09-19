@extends('layouts.cashier')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Kitchen Display</h1>
            <div class="text-sm text-gray-500">
                Auto-refreshes every 60 seconds
            </div>
        </div>

        @if($orders->isEmpty())
            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                <p class="text-blue-700">No orders to prepare at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-lg shadow border p-4 {{ $order->status == 'new' ? 'border-blue-500' : 'border-yellow-500' }}" id="order-card-{{ $order->id }}">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-lg font-semibold">
                                Order #{{ $order->id }}
                                <span id="order-status-{{ $order->id }}" class="ml-2 px-2 py-1 text-xs rounded-full {{ $order->status == 'new' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </h2>
                            <span class="text-sm text-gray-500">
                                {{ $order->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="mb-2">
                            <span class="text-sm font-medium">
                                {{ $order->table ? 'Table: ' . $order->table->name : 'Takeout' }}
                            </span>
                        </div>

                        <div class="border-t border-gray-200 pt-2 mt-2">
                            <h3 class="text-sm font-medium mb-2">Items:</h3>
                            <ul class="space-y-2">
                                @foreach($order->orderItems as $item)
                                    <li class="flex justify-between">
                                        <div>
                                            <span class="font-medium">{{ $item->quantity }}x</span>
                                            {{ $item->menuItem->name }}
                                            @if($item->notes)
                                                <p class="text-xs text-gray-500 mt-1">Note: {{ $item->notes }}</p>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-4 flex justify-end">
                            @if($order->status == 'new')
                                <button type="button"
                                        class="start-preparing-btn px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600"
                                        data-order-id="{{ $order->id }}">
                                    Start Preparing
                                </button>
                            @elseif($order->status == 'preparing')
                                <button type="button"
                                        class="mark-ready-btn px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                                        data-order-id="{{ $order->id }}">
                                    Mark as Ready
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    // Auto-refresh the page every 60 seconds
    setTimeout(function() {
        location.reload();
    }, 60000);

    // Add CSRF token to all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle Start Preparing button clicks
    document.querySelectorAll('.start-preparing-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            updateOrderStatus(orderId, 'preparing', this);
        });
    });

    // Handle Mark as Ready button clicks
    document.querySelectorAll('.mark-ready-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            updateOrderStatus(orderId, 'ready', this);
        });
    });

    // Function to update order status via AJAX
    function updateOrderStatus(orderId, status, button) {
        // Disable button to prevent double-clicks
        button.disabled = true;
        button.classList.add('opacity-50', 'cursor-not-allowed');

        // Show loading text
        const originalText = button.textContent;
        button.textContent = 'Updating...';

        // Create form data for POST request
        const formData = new FormData();
        formData.append('_method', 'PATCH'); // For method spoofing
        formData.append('status', status);
        formData.append('_token', csrfToken);

        // Log the request for debugging
        console.log(`Updating order ${orderId} to ${status}`);

        // Use the full URL path to avoid any routing issues
        const baseUrl = window.location.origin;

        fetch(`${baseUrl}/cashier/orders/${orderId}/status`, {
            method: 'POST', // Use POST with _method=PATCH for Laravel method spoofing
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update the status display
                const statusElement = document.getElementById(`order-status-${orderId}`);

                if (statusElement) {
                    statusElement.textContent = status.charAt(0).toUpperCase() + status.slice(1);

                    // Update status colors
                    if (status === 'preparing') {
                        statusElement.classList.remove('bg-blue-100', 'text-blue-800');
                        statusElement.classList.add('bg-yellow-100', 'text-yellow-800');

                        // Replace the button with a "Mark as Ready" button
                        const newButton = document.createElement('button');
                        newButton.type = 'button';
                        newButton.className = 'mark-ready-btn px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600';
                        newButton.dataset.orderId = orderId;
                        newButton.textContent = 'Mark as Ready';

                        // Replace the old button with the new one
                        button.parentNode.replaceChild(newButton, button);

                        // Add event listener to the new button
                        newButton.addEventListener('click', function() {
                            updateOrderStatus(orderId, 'ready', this);
                        });
                    }
                    else if (status === 'ready') {
                        statusElement.classList.remove('bg-yellow-100', 'text-yellow-800');
                        statusElement.classList.add('bg-green-100', 'text-green-800');

                        // Remove the button as there's no next status from the kitchen
                        button.remove();

                        // Fade out and remove the order card after a delay
                        const orderCard = document.getElementById(`order-card-${orderId}`);
                        if (orderCard) {
                            orderCard.style.transition = 'opacity 2s';
                            orderCard.style.opacity = '0.5';
                            setTimeout(() => {
                                orderCard.style.display = 'none';
                            }, 3000);
                        }
                    }
                }
            } else {
                // Show error and re-enable button
                alert('Error updating order status: ' + (data.message || 'Unknown error'));
                button.disabled = false;
                button.classList.remove('opacity-50', 'cursor-not-allowed');
                button.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating order status. Please try again. ' + error.message);
            button.disabled = false;
            button.classList.remove('opacity-50', 'cursor-not-allowed');
            button.textContent = originalText;
        });
    }
</script>
@endsection
