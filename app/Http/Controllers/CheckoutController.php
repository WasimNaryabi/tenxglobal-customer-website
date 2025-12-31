<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        return Inertia::render('Checkout', [
            'auth' => [
                'user' => Auth::user(),
            ],
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'orderType' => ['required', 'in:delivery,pickup'],
                'paymentMethod' => ['required', 'in:card,cash'],
                'name' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:20'],
                'email' => ['nullable', 'email', 'max:255'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.id' => ['required'],
                'items.*.name' => ['required', 'string'],
                'items.*.price' => ['required', 'numeric', 'min:0'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                
                // Delivery fields (required if delivery)
                'address1' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:255'],
                'city' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:255'],
                'postcode' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:20'],
                
                // Optional fields
                'address2' => ['nullable', 'string', 'max:255'],
                'instructions' => ['nullable', 'string'],
                'pickupTime' => ['nullable', 'string'],
                'specialInstructions' => ['nullable', 'string'],
            ]);

            // Calculate totals
            $subtotal = collect($validated['items'])->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            $deliveryFee = $validated['orderType'] === 'delivery' ? 2.50 : 0;
            $total = $subtotal + $deliveryFee;
            $vat = $total * 0.2; // 20% VAT
            $totalWithVat = $total + $vat;

            // Prepare delivery address
            $deliveryAddress = null;
            if ($validated['orderType'] === 'delivery') {
                $addressParts = array_filter([
                    $validated['address1'] ?? null,
                    $validated['address2'] ?? null,
                    $validated['city'] ?? null,
                    $validated['postcode'] ?? null,
                ]);
                $deliveryAddress = implode(', ', $addressParts);
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'type' => $validated['orderType'],
                'status' => 'pending',
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'delivery_address' => $deliveryAddress,
                'delivery_instructions' => $validated['instructions'] ?? null,
                'pickup_time' => $validated['pickupTime'] ?? null,
                'payment_method' => $validated['paymentMethod'],
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'vat' => $vat,
                'total' => $totalWithVat,
                'special_instructions' => $validated['specialInstructions'] ?? null,
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['id'] ?? null,
                    'name' => $item['name'],
                    'description' => $item['description'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'customizations' => isset($item['customizations']) ? json_encode($item['customizations']) : null,
                ]);
            }

            // TODO: Send confirmation SMS/Email
            // TODO: Process payment if card selected
            // TODO: Notify restaurant

            return redirect()->route('order.confirmation', ['orderNumber' => $order->order_number])
                ->with('success', 'Order placed successfully!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check the form and try again.');
        } catch (\Exception $e) {
            //Log::error('Order creation failed: ' . $e->getMessage());
            return back()
                ->with('error', 'Order failed. Please try again or contact support.')
                ->withInput();
        }
    }

    /**
     * Show order confirmation page
     */
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        // Verify ownership for logged-in users
        // if (Auth::check() && $order->user_id !== Auth::id()) {
        //     abort(403);
        // }

        return Inertia::render('OrderConfirmation', [
            'order' => $order,
        ]);
    }
}