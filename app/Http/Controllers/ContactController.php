<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Renders the contact form view structure
    public function index()
    {
        return view('contact');
    }

    // Handles form actions and provides response alerts
    public function store(Request $request)
    {
        // Form Validation constraints
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:5',
        ]);

        // Returns message feedback banner to view session container
        return redirect()->back()->with('success', 'Thank you for reaching out! Your message has been sent successfully.');
    }
}