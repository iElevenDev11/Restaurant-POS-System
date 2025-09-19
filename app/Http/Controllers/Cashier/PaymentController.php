<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order'])
                    ->latest()
                    ->paginate(10);

        return view('cashier.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $orderId = $request->input('order');

        if (!$orderId) {
            return redirect()->route('orders.index')
                ->with('error', 'No order selected for payment');
        }

        $order = Order::with(['orderItems.menuItem', 'payments'])
                ->findOrFail($orderId);

        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order has already been paid');
        }

        // Calculate remaining amount to be paid
        $paidAmount = $order->payments->sum('amount');
        $remainingAmount = $order->total_amount - $paidAmount;

        return view('cashier.payments.create', compact('order', 'paidAmount', 'remainingAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,mobile',
            'payment_details' => 'nullable|array',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Check if order is already paid
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('error', 'This order has already been paid');
        }

        // Calculate remaining amount to be paid
        $paidAmount = $order->payments->sum('amount');
        $remainingAmount = $order->total_amount - $paidAmount;

        // Validate payment amount
        if ($request->amount > $remainingAmount) {
            return back()->with('error', 'Payment amount cannot exceed the remaining balance')->withInput();
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Create the payment
            $payment = new Payment();
            $payment->order_id = $request->order_id;
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;

            // Store payment details if provided
            if ($request->filled('payment_details')) {
                $payment->payment_details = $request->payment_details;
            }

            $payment->save();

                        // Update order payment status if fully paid
            $newPaidAmount = $order->payments->sum('amount') + $request->amount;

            if ($newPaidAmount >= $order->total_amount) {
                $order->payment_status = 'paid';

                // If order is served and now paid, mark it as completed
                if ($order->status === 'served') {
                    $order->status = 'completed';
                }

                $order->save();
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Payment processed successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing payment: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Payment $payment)
    {
        $payment->load('order');
        return view('cashier.payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        // Only allow refunding payments if the order is not completed
        if ($payment->order->status === 'completed') {
            return redirect()->route('payments.index')
                ->with('error', 'Cannot refund payment for a completed order');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Mark the payment as refunded
            $payment->delete();

            // Update order payment status
            $order = $payment->order;
            $order->payment_status = 'refunded';
            $order->save();

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Payment refunded successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error refunding payment: ' . $e->getMessage());
        }
    }
}
