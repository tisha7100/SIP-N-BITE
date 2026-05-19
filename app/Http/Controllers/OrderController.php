<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\TableBooking;
use App\Models\Bill;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders   = Order::where('user_id', Auth::id())->latest()->paginate(10);
        $bookings = TableBooking::with('table')->where('user_id', Auth::id())->latest()->get();
        return view('orders.index', compact('orders', 'bookings'));
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.food', 'orderItems.addons.addon', 'deliveryPartner'])->where('user_id', Auth::id())->findOrFail($id);
        
        $statusFlow = [
            'Pending'           => 10,
            'Preparing'         => 30,
            'Under Preparation' => 30,
            'Ready'             => 50,
            'Assigned'          => 70,
            'Out for Delivery'  => 85,
            'Delivered'         => 100,
            'Cancelled'         => 100
        ];
        
        $progress = $statusFlow[$order->status] ?? 0;

        return view('orders.show', compact('order', 'progress'));
    }

    public function bill($id)
    {
        $order = Order::with(['orderItems.food', 'orderItems.addons.addon', 'user'])->where('user_id', Auth::id())->findOrFail($id);
        
        // Auto-generate bill if not exists
        $bill = Bill::where('order_id', $order->id)->first();
        if (!$bill) {
            $bill = Bill::create([
                'order_id' => $order->id,
                'bill_number' => 'BILL-' . strtoupper(substr(uniqid(), -6)),
                'subtotal' => $order->total_amount,
                'gst' => $order->gst,
                'delivery_charge' => $order->delivery_charge,
                'final_amount' => $order->final_amount,
                'payment_method' => $order->payment_method,
            ]);
        }

        return view('orders.bill', compact('order', 'bill'));
    }
}
