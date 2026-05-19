<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartAddon;
use App\Models\Food;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['food', 'addons'])->where('user_id', Auth::id())->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'food_id' => 'required|exists:foods,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'food_id' => $request->food_id,
            'quantity' => $request->quantity,
        ]);

        if ($request->has('addons')) {
            foreach ($request->addons as $addonId) {
                CartAddon::create([
                    'cart_id' => $cart->id,
                    'addon_id' => $addonId,
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart');
    }

    public function remove($id)
    {
        Cart::where('user_id', Auth::id())->where('id', $id)->delete();
        return back()->with('success', 'Item removed');
    }
}
