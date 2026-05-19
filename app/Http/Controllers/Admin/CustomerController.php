<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
       $customers = User::where('role', '!=', 'admin')->orderBy('id', 'asc')->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return back()->with('success', 'Customer status updated to ' . $request->status);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Customer removed successfully');
    }
}
