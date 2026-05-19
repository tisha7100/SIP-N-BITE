<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TableBooking;

class AdminBookingController extends Controller
{
    public function update(Request $request, $id)
    {
        $booking = TableBooking::findOrFail($id);

        $booking->status = $request->status;
        $booking->save();

        return back()->with('success', 'Booking updated successfully');
    }
}