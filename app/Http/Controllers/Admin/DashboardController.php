<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPartner;
use App\Models\User;
use App\Models\Order;
use App\Models\TableBooking;
use App\Models\TableLayout;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            // Total customers
            'total_users' => User::where('role', 'customer')->count(),

            // Total orders
            'total_orders' => Order::count(),

            // Total delivery partners
            'total_partners' => DeliveryPartner::count(),
            'available_partners' => DeliveryPartner::where('status', 'Available')->count(),
            'busy_partners' => DeliveryPartner::where('status', 'Busy')->count(),

            // Total table bookings
            'total_bookings' => TableBooking::count(),
            'pending_bookings' => TableBooking::where('status', 'pending')->count(),
            'booked_tables' => TableLayout::where('status', 'booked')->count(),
            'available_tables' => TableLayout::where('status', 'available')->count(),
            'tables' => TableLayout::orderByRaw('CAST(table_number AS UNSIGNED), table_number')
                ->take(12)
                ->get(),

            // Recent 5 orders
            'recent_orders' => Order::with('user')
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
