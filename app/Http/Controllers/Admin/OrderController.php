<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'deliveryPartner'])->latest()->paginate(10);
        $availablePartners = \App\Models\DeliveryPartner::where('status', 'Available')->take(3)->get();
        return view('admin.orders.index', compact('orders', 'availablePartners'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'orderItems.food', 'orderItems.addons.addon'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($request->has('delivery_partner_id') && $request->delivery_partner_id) {
            $order->update([
                'delivery_partner_id' => $request->delivery_partner_id,
                'status' => 'Assigned'
            ]);
            
            $partner = \App\Models\DeliveryPartner::find($request->delivery_partner_id);
            if($partner) {
                $partner->update(['status' => 'Busy']);
            }
            
            return back()->with('success', 'Delivery Partner assigned successfully.');
        }

        $order->update(['status' => $request->status]);

        if (in_array($request->status, ['Delivered', 'Cancelled']) && $order->delivery_partner_id) {
            $order->deliveryPartner()->update(['status' => 'Available']);
        }

        return back()->with('success', 'Order status updated to ' . $request->status);
    }
}
