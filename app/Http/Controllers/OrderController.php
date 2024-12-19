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
        $getAllOrder = Order::orderBy('id', 'desc')->with('User')->get();
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
            $year = $request->input('year');
            $month  = $request->input('month');
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
                "customerId" => $request->input('customerId'),
                "methodPayment" => $request->input('methodPayment'),
                "orderStatus" => $request->input('orderStatus'),
                "products" => $orderItems,
                "day" => $currentDay,
                "week" => $currentWeek,
                "month" => $month ?? $currentMonth,
                "year" =>   $year ?? $currentYear,
                "totalOrder" => $totalOrder
            ]);
            $revenue = Revenue::firstOrCreate(
                ['day' => $currentDay, "week" => $currentWeek, 'month' => $month ?? $currentMonth, "year" => $year ?? $currentYear],
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
    public function getOne(String $orderId)
    {
        $order = Order::find($orderId);
        return response()->json([
            "message" => "Get order successfully !",
            "results" => $order,
            "status" => "success"
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    // Output : [{customerId : "#xyz" , year : 2024 , monthlyPurchaseQuantity : [ {month : 1 , purchaseCount : 1} ]}]
    public function getBuyersInfo()
    {
        $orders = Order::get();
        $usersPurchaseInfo = $orders->map(fn($order) => [
            "customerId" => $order->customerId,
            "purchaseCount" => 0
        ]);
        return response()->json([
            "message" => "Fetch users purchase information successfully !",
            "results" =>  $usersPurchaseInfo,
            "status" => "success"
        ]);
    }
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
