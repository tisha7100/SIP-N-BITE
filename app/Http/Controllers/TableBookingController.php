<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TableLayout;
use App\Models\TableBooking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TableBookingController extends Controller
{
    public function index(Request $request)
    {
        $tables = TableLayout::where('is_active', true)->get();

        $selectedDate = $request->booking_date ?? now()->toDateString();
        $selectedTime = $request->booking_time ?? null;

        // 🔥 FIXED LOGIC: better booking detection
        $query = TableBooking::whereDate('booking_date', $selectedDate)
            ->whereIn('status', ['pending', 'accepted']);

        if ($selectedTime) {
            $query->where('booking_time', $selectedTime);
        }

        $bookedTableIds = $query->pluck('table_id')->toArray();

        return view('booking.index', compact(
            'tables',
            'bookedTableIds',
            'selectedDate',
            'selectedTime'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'     => 'required|exists:tables_layout,id',
            'name'         => 'required|min:2',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'duration_hours' => 'required|integer|min:1',
            'purpose'      => 'required|string',
        ]);

        $requestedStart = Carbon::parse(
    $request->booking_date . ' ' . $request->booking_time
);

$requestedEnd = (clone $requestedStart)
    ->addHours((int) $request->duration_hours);

$bookings = TableBooking::where('table_id', $request->table_id)
    ->whereDate('booking_date', $request->booking_date)
    ->whereIn('status', ['pending', 'accepted'])
    ->get();

$exists = false;

foreach ($bookings as $booking)
{
    $bookingStart = Carbon::parse(
        $booking->booking_date . ' ' . $booking->booking_time
    );

    $bookingEnd = (clone $bookingStart)
        ->addHours($booking->duration_hours);

    if (
        $requestedStart < $bookingEnd &&
        $requestedEnd > $bookingStart
    ) {
        $exists = true;
        break;
    }
}

        if ($exists) {
            return back()->withErrors([
                'table_id' => 'This table is already booked for selected time.'
            ]);
        }

        TableBooking::create([
            'user_id'      => Auth::id(),
            'table_id'     => $request->table_id,
            'name'         => $request->name,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'duration_hours' => $request->duration_hours,
            'persons'      => 1,
            'purpose'      => $request->purpose,
            'status'       => 'pending',
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', 'Table booked successfully!');
    }
}