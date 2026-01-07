<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        return Inertia::render('Checkout', [
            'auth' => [
                'user' => Auth::guard('customer')->user() ?: Auth::user(),
            ],
            'stripePublicKey' => env('STRIPE_PUBLIC'),
        ]);
    }

    /**
     * Process checkout and create order
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('--- CHECKOUT ATTEMPT ---');
        \Illuminate\Support\Facades\Log::info('Request Data:', $request->all());

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
                'items.*.customizations' => ['nullable', 'array'],
                'items.*.description' => ['nullable', 'string'],

                // Delivery fields (required if delivery)
                'address1' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:255'],
                'city' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:255'],
                'postcode' => ['required_if:orderType,delivery', 'nullable', 'string', 'max:20'],

                // Optional fields
                'address2' => ['nullable', 'string', 'max:255'],
                'instructions' => ['nullable', 'string'],
                'pickupTime' => ['nullable', 'string'],
                'specialInstructions' => ['nullable', 'string'],
                'paymentIntentId' => ['required_if:paymentMethod,card', 'nullable', 'string'],
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

            // Verify payment if card
            if ($validated['paymentMethod'] === 'card') {
                Stripe::setApiKey(env('STRIPE_SECRET'));
                $paymentIntent = PaymentIntent::retrieve($validated['paymentIntentId']);
                if ($paymentIntent->status !== 'succeeded') {
                    throw new \Exception('Payment verification failed.');
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => Auth::guard('customer')->id() ?: Auth::id(),
                'type' => $validated['orderType'],
                'status' => 'pending',
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'delivery_address' => $deliveryAddress,
                'delivery_instructions' => $validated['instructions'] ?? null,
                'pickup_time' => $validated['pickupTime'] ?? null,
                'payment_method' => $validated['paymentMethod'],
                'payment_status' => $validated['paymentMethod'] === 'card' ? 'paid' : 'pending',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'vat' => $vat,
                'total' => $totalWithVat,
                'special_instructions' => $validated['specialInstructions'] ?? null,
                'stripe_payment_id' => $validated['paymentIntentId'] ?? null,
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

            // Sync order to POS system
            $this->syncOrderToPOS($order, $validated);

            // Send confirmation Email
            if ($order->customer_email) {
                try {
                    \Illuminate\Support\Facades\Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmation($order));
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Order confirmation email failed: '.$e->getMessage());
                }
            }

            return redirect()->route('order.confirmation', ['orderNumber' => $order->order_number])
                ->with('success', 'Order placed successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Please check the form and try again.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Order creation failed: '.$e->getMessage());

            return back()
                ->with('error', 'Order failed. Please try again or contact support.')
                ->withInput();
        }
    }

    /**
     * Push order details to POS system
     */
    protected function syncOrderToPOS($order, $validated)
    {
        try {
            // Map website items to POS expected format
            $posItems = [];
            foreach ($validated['items'] as $item) {
                // Fetch the website menu item to get its api_id (the POS ID)
                $websiteItem = \App\Models\MenuItem::find($item['id']);
                $posItemId = $websiteItem ? $websiteItem->api_id : $item['id'];

                $customizations = $item['customizations'] ?? null;
                if (isset($customizations['addons']) && is_array($customizations['addons'])) {
                    foreach ($customizations['addons'] as &$addon) {
                        $websiteAddon = \App\Models\Addon::find($addon['id']);
                        if ($websiteAddon && $websiteAddon->api_id) {
                            $addon['id'] = $websiteAddon->api_id;
                        }
                        $addon['price'] = round($addon['price'], 2);
                    }
                }

                $posItems[] = [
                    'id' => $posItemId, // POS MenuItem ID (mapped from api_id)
                    'name' => $item['name'],
                    'price' => round($item['price'], 2),
                    'quantity' => $item['quantity'],
                    'customizations' => $customizations,
                ];
            }

            $posOrderData = [
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'order_type' => $validated['orderType'] === 'delivery' ? 'Delivery' : 'Collection',
                'payment_method' => $validated['paymentMethod'],
                'payment_status' => $order->payment_status,
                'payment_intent_id' => $order->stripe_payment_id,
                'subtotal' => $order->subtotal,
                'delivery_fee' => $order->delivery_fee,
                'vat' => $order->vat,
                'total' => $order->total,
                'items' => $posItems,
                'delivery_address' => $order->delivery_address,
                'instructions' => $order->special_instructions,
            ];
            // https://smashngrub.10xglobal.co.uk/api/external/orders
            $posUrl = env('POS_URL', 'https://smashngrub.10xglobal.co.uk/api/external/orders');

            \Illuminate\Support\Facades\Log::info('Attempting POS sync to: '.$posUrl);
            \Illuminate\Support\Facades\Log::info('POS Payload:', $posOrderData);

            try {
                $response = \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders(['Accept' => 'application/json'])
                    ->post($posUrl, $posOrderData);

                if ($response && $response->successful()) {
                    $responseData = $response->json();
                    \Illuminate\Support\Facades\Log::info('POS sync successful:', (array) $responseData);
                    if (isset($responseData['pos_order_id'])) {
                        $order->update(['pos_order_id' => $responseData['pos_order_id']]);
                    }
                } else {
                    $status = $response ? $response->status() : 'unknown';
                    $body = $response ? $response->body() : 'no response body';
                    \Illuminate\Support\Facades\Log::error("POS sync failed | Status: $status | Body: $body");
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                $status = $e->response ? $e->response->status() : 'unknown';
                $body = $e->response ? $e->response->body() : 'no response body';
                \Illuminate\Support\Facades\Log::error("POS sync Exception | Status: $status | Body: $body | Error: ".$e->getMessage());
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to sync order to POS (General Exception): '.$e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
        }
    }

    /**
     * Create a Stripe PaymentIntent
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:0.5',
            ]);

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($validated['amount'] * 100), // Convert to cents
                'currency' => 'gbp',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PaymentIntent creation failed: '.$e->getMessage());

            return response()->json(['error' => $e->getMessage()], 500);
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
