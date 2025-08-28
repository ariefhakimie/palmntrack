<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Commodity;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::with('commodity')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // If this is a stock out (from the modal), only commodity_id and quantity are present
        if ($request->has('commodity_id') && $request->has('quantity') && !$request->has('supplier')) {
            $request->validate([
                'commodity_id' => 'required|exists:commodities,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $commodity = Commodity::findOrFail($request->commodity_id);
            if ($commodity->quantity < $request->quantity) {
                return back()->with('error', 'Not enough stock to remove the requested quantity.');
            }
            $commodity->quantity -= $request->quantity;
            $commodity->save();

            return redirect()->route('commodities.fsindex')->with('success', 'Stock out successful!');
        }

        // Otherwise, it's a supplier order
        $validated = $request->validate([
            'commodity_id' => 'required|exists:commodities,id',
            'quantity' => 'required|integer|min:1',
            'supplier' => 'required|string|max:255',
            'expected_delivery' => 'required|date|after_or_equal:today',
            'urgency' => 'required|in:normal,high,urgent',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create($validated);

        return redirect()->route('orders.index')
            ->with('success', 'Order created successfully!');
    }
}