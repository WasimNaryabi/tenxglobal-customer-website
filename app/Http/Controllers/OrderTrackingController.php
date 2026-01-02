<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class OrderTrackingController extends Controller
{
    /**
     * Show tracking search page or specific order tracking
     */
    public function index(Request $request)
    {
        $orderNumber = $request->query('orderNumber');
        $orderData = null;
        $posStatus = null;

        if ($orderNumber) {
            $orderData = Order::where('order_number', $orderNumber)->first();
            
            if ($orderData && $orderData->pos_order_id) {
                $posStatus = $this->fetchStatusFromPOS($orderData->pos_order_id);
            }
        }

        return Inertia::render('TrackOrder', [
            'initialOrderNumber' => $orderNumber,
            'order' => $orderData,
            'posStatus' => $posStatus
        ]);
    }

    /**
     * Fetch status of a specific order for API/AJAX lookups
     */
    public function track($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $posStatus = null;
        if ($order->pos_order_id) {
            $posStatus = $this->fetchStatusFromPOS($order->pos_order_id);
        }

        return response()->json([
            'order' => $order,
            'posStatus' => $posStatus
        ]);
    }

    /**
     * Internal helper to fetch status from POS
     */
    protected function fetchStatusFromPOS($posOrderId)
    {
        try {
            // TODO: Move to .env
            $posUrl = "http://localhost:8000/api/external/orders/{$posOrderId}/status";
            
            $response = Http::timeout(5)->get($posUrl);

            if ($response && $response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Tracking: Failed to fetch status from POS: ' . $e->getMessage());
        }

        return null;
    }
}
