<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TableLayout;
use App\Models\TableBooking;

class TableController extends Controller
{
    public function index()
    {
        $tables = TableLayout::all();
        $bookings = TableBooking::with(['user', 'table'])->latest()->paginate(10);
        return view('admin.tables.index', compact('tables', 'bookings'));
    }

    public function store(Request $request)
    {
        $request->validate(['table_number' => 'required|unique:tables_layout', 'capacity' => 'required|integer']);
        TableLayout::create(['table_number' => $request->table_number, 'capacity' => $request->capacity]);
        return back()->with('success', 'Table added successfully');
    }

    public function update(Request $request, $id)
    {
        $booking = TableBooking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        if ($request->status == 'accepted') {
            TableLayout::where('id', $booking->table_id)->update(['status' => 'booked']);
        } else {
            TableLayout::where('id', $booking->table_id)->update(['status' => 'available']);
        }

        return back()->with('success', 'Booking status updated to ' . $request->status);
    }

    public function destroy($id)
    {
        $table = TableLayout::findOrFail($id);
        
        // Cannot delete a table that is currently booked or has active bookings
        $hasActiveBookings = TableBooking::where('table_id', $id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();
            
        if ($table->status == 'booked' || $hasActiveBookings) {
            return back()->with('error', 'Cannot delete this table as it is currently booked.');
        }

        $table->delete();
        return back()->with('success', 'Table removed successfully');
    }
}
