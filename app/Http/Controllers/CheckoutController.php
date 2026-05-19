<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAddon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $cartItems = Cart::with(['food', 'addons.addon'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home');
        }

        $subtotal = 0;

        foreach ($cartItems as $item) {

            $itemTotal = $item->food->price;

            foreach ($item->addons as $addon) {
                $itemTotal += $addon->addon->price;
            }

            $subtotal += $itemTotal * $item->quantity;
        }

        return view('checkout', compact('cartItems', 'subtotal'));
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK DISTANCE
    |--------------------------------------------------------------------------
    */

    public function checkDistance(Request $request)
    {
        $request->validate([
            'address' => 'required|string'
        ]);

        try {

            $address = $request->address;

            // RESTAURANT LOCATION
            $origin = "Athwa, Surat, Gujarat";

           // DISTANCEMATRIX.AI API KEY
            $apiKey = env('DISTANCE_MATRIX_API_KEY');

            // API REQUEST
            $response = Http::timeout(20)->get(
            'https://api.distancematrix.ai/maps/api/distancematrix/json',
            [
                'origins' => $origin,
                'destinations' => $address . ', Surat',
                'units' => 'metric',
                'key' => $apiKey
            ]
        );

            // RESPONSE DATA
            $data = $response->json();

            // LOG RESPONSE
            Log::info('Distance Matrix API Response', $data);

            // DISTANCEMATRIX API STATUS CHECK
            if (
                !isset($data['status']) ||
                $data['status'] !== 'OK'
            ) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        $data['error_message']
                        ?? $data['status']
                        ?? 'Distance Matrix API Error',
                    'google_response' => $data
                ]);
            }

            // DESTINATION STATUS CHECK
            $elementStatus =
                $data['rows'][0]['elements'][0]['status'] ?? null;

            if ($elementStatus !== 'OK') {

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid destination address'
                ]);
            }

            // DISTANCE IN METERS
            $distanceMeters =
                $data['rows'][0]['elements'][0]['distance']['value'];

            // CONVERT TO KM
            $distanceKm = $distanceMeters / 1000;

            // DELIVERY FEE
            $deliveryFee = ceil($distanceKm) * 10;

            // MINIMUM DELIVERY CHARGE
            if ($deliveryFee < 20) {
                $deliveryFee = 20;
            }

            return response()->json([
                'success' => true,
                'distance' => round($distanceKm, 2),
                'fee' => $deliveryFee
            ]);

        } catch (\Exception $e) {

            Log::error('Distance API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS ORDER
    |--------------------------------------------------------------------------
    */

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'phone' => 'required|string',
            'distance' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::with(['food', 'addons.addon'])
            ->where('user_id', Auth::id())
            ->get();

        // CHECK EMPTY CART
        if ($cartItems->isEmpty()) {

            return redirect()
                ->back()
                ->with('error', 'Cart is empty');
        }

        $subtotal = 0;

        foreach ($cartItems as $item) {

            $itemTotal = $item->food->price;

            foreach ($item->addons as $addon) {
                $itemTotal += $addon->addon->price;
            }

            $subtotal += $itemTotal * $item->quantity;
        }

        // DISTANCE
        $distance = (float) $request->distance;

        // DELIVERY CHARGE
        $deliveryCharge = ceil($distance) * 10;

        // MINIMUM DELIVERY CHARGE
        if ($deliveryCharge < 20) {
            $deliveryCharge = 20;
        }

        // GST 5%
        $gst = $subtotal * 0.05;

        // FINAL TOTAL
        $finalAmount =
            $subtotal +
            $deliveryCharge +
            $gst;

        // PAYMENT METHOD
        $paymentMethod =
            $request->payment_method === 'Online'
            ? 'Paid Online'
            : 'COD';

        // PAYMENT STATUS
        $paymentStatus =
            $paymentMethod === 'Paid Online'
            ? 'paid'
            : 'pending';

        // CREATE ORDER
        $order = Order::create([

            'user_id' => Auth::id(),

            'total_amount' => $subtotal,

            'payment_method' => $paymentMethod,

            'payment_status' => $paymentStatus,

            'shipping_address' => $request->address,

            'phone' => $request->phone,

            'distance' => $distance,

            'delivery_charge' => $deliveryCharge,

            'gst' => $gst,

            'final_amount' => $finalAmount,

            'status' => 'Under Preparation',
        ]);

        // SAVE ORDER ITEMS
        foreach ($cartItems as $item) {

            $orderItem = OrderItem::create([

                'order_id' => $order->id,

                'food_id' => $item->food_id,

                'quantity' => $item->quantity,

                'price' => $item->food->price,

                'subtotal' =>
                    $item->food->price *
                    $item->quantity,
            ]);

            // SAVE ADDONS
            foreach ($item->addons as $addon) {

                OrderAddon::create([

                    'order_item_id' => $orderItem->id,

                    'addon_id' => $addon->addon_id,

                    'price' => $addon->addon->price,
                ]);
            }
        }

        // CLEAR CART
        Cart::where('user_id', Auth::id())->delete();

        return redirect()
            ->route('orders.show', $order->id)
            ->with(
                'success',
                'Order placed successfully!'
            );
    }
}