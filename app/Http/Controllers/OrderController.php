<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Revenue;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAllOrder = Order::orderBy('id', 'desc')->get();
        return response()->json([
            "message" => "Get all orders successfully !",
            "results" => $getAllOrder,
            "status" => "success"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $currentYear = now()->year;
            $currentMonth = now()->month;
            $currentWeek = now()->week;
            $currentDay = now()->day;
            $orderItems = $request->input('products');
            $totalOrder = 0;
            if (!$orderItems || !is_array($orderItems)) {
                return response()->json([
                    "message" => "Invalid products data",
                    "results" => $request->all(),
                    "status" => "error"
                ], 400);
            }
            foreach ($orderItems as $item) {
                $totalOrder += $item['productPrice'] * $item['quantity'];
            }
            $newOrder = Order::create(attributes: [
                "products" => $orderItems,
                "day" => $currentDay,
                "week" => $currentWeek,
                "month" => $currentMonth,
                "year" => $currentYear,
                "totalOrder" => $totalOrder
            ]);
            $revenue = Revenue::firstOrCreate(
                ['day' => $currentDay, "week" => $currentWeek, 'month' => $currentMonth, "year" => $currentYear],
                ['totalRevenue' => 0]
            );
            $revenue->totalRevenue += $totalOrder;
            $revenue->save();
            return response()->json([
                "message" => "Created a order successfully !",
                "results" =>   [
                    "newOrder" =>  $newOrder,
                ],
                "status" => "success"
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'An error occurred while processing the order.',
                "error" => $error->getMessage(),
                "status" => 'error'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
