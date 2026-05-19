<?php

namespace App\Http\Controllers\DeliveryPartner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\DeliveryPartner;

class DashboardController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('delivery_partner')->check()) {
            return redirect()->route('delivery-partner.dashboard');
        }

        return view('delivery_partner.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $partner = DeliveryPartner::where('email', $request->email)->first();

        if (! $partner) {
            return back()->with('error', 'Invalid credentials')->withInput();
        }

        $validPassword = false;

        if (is_string($partner->password) && str_starts_with($partner->password, '$2y$')) {
            $validPassword = Hash::check($request->password, $partner->password);
        } else {
            $validPassword = trim($request->password) === trim($partner->password);
        }

        if (! $validPassword) {
            return back()->with('error', 'Invalid password')->withInput();
        }

        Auth::guard('delivery_partner')->login($partner, true);

        return redirect()->route('delivery-partner.dashboard');
    }

   public function index()
{
    $partner = \Illuminate\Support\Facades\Auth::guard('delivery_partner')->user();

    if (!$partner) {
        return redirect()->route('delivery-partner.login');// fallback safety
    }

    $assignedOrders = \App\Models\Order::with('user')
        ->where('delivery_partner_id', $partner->id)
        ->whereNotIn('status', ['Delivered', 'Cancelled'])
        ->latest()
        ->get();

    $historyOrders = \App\Models\Order::with('user')
        ->where('delivery_partner_id', $partner->id)
        ->whereIn('status', ['Delivered', 'Cancelled'])
        ->latest()
        ->get();

    return view('delivery_partner.dashboard', [
        'partner' => $partner,
        'assignedOrders' => $assignedOrders,
        'historyOrders' => $historyOrders
    ]);
}

    public function show($id)
    {
        $partner = \App\Models\DeliveryPartner::findOrFail($id);
        $assignedOrders = Order::with('user', 'orderItems.food')
                            ->where('delivery_partner_id', $partner->id)
                            ->whereNotIn('status', ['Delivered', 'Cancelled'])
                            ->latest()
                            ->get();

        $historyOrders = Order::with('user', 'orderItems.food')
                            ->where('delivery_partner_id', $partner->id)
                            ->whereIn('status', ['Delivered', 'Cancelled'])
                            ->latest()
                            ->get();

    return view('delivery_partner.show', compact('partner', 'assignedOrders', 'historyOrders'));
    }

    public function profile()
    {
        $partner = Auth::guard('delivery_partner')->user();

        if (! $partner) {
            return redirect()->route('delivery-partner.login');
        }

        return $this->show($partner->id);
    }

    public function toggleStatus(Request $request, $id)
    {
        $partner = \App\Models\DeliveryPartner::findOrFail($id);
        $partner->update([
            'status' => $partner->status == 'Available' ? 'Busy' : 'Available'
        ]);

        return back()->with('success', 'Status updated successfully.');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Delivered']);
        
        if($order->deliveryPartner) {
            $order->deliveryPartner->update(['status' => 'Available']);
        }
        
        return back()->with('success', 'Order marked as Delivered!');
    }

    public function allPartners()
{
    $partners = \App\Models\DeliveryPartner::all();
    return view('delivery_partner.all_partners', compact('partners'));
}
}
