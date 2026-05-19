<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bill;

class BillController extends Controller
{
    public function generate($order_id)
    {
        $order = Order::with(['orderItems.food', 'orderItems.addons.addon', 'user'])->findOrFail($order_id);

        $bill = Bill::where('order_id', $order_id)->first();

        if (!$bill) {
            $bill = Bill::create([
                'order_id' => $order->id,
                'bill_number' => 'BILL-' . time(),
                'subtotal' => $order->total_amount,
                'gst' => $order->gst,
                'delivery_charge' => $order->delivery_charge,
                'final_amount' => $order->final_amount,
                'payment_method' => $order->payment_method,
            ]);
        }

        return view('admin.bill.show', compact('order', 'bill'));
    }
}
