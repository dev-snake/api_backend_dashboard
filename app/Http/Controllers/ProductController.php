<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order; 
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAll = Product::get();
        return response()->json([
            'message'=> 'Get all products successfully !',
            "results"=> $getAll,
            'status'=> 'success'
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request  $request)
    {
        
        $newProduct = Product::create($request->all());
        return response()->json([
            "message" => "Created a product successfully !",
            "results"=> $newProduct,
            "status"=> "success"
        ],200);
    }
    public function getQuantitySold(){
        $orderItems = Order::get();
        $totalSoldByProduct = [];
        foreach($orderItems as $order){
            foreach($order['products'] as $product){
                $productId = $product['productId'];
                $quantity = $product['quantity'];
                if(isset($totalSoldByProduct[$productId])){
                    $totalSoldByProduct[$productId] += $quantity;
                }else {
                    $totalSoldByProduct[$productId] = $quantity;
                }
            }
        }
        $productIds = array_keys($totalSoldByProduct);
        $products = Product::whereIn('id', values: $productIds)->get(['id' , 'productName']);
        $results = [];
        foreach($products as $product){
            $results[] = [
                "productId"=> $product->id,
                "productName"=> $product->productName,
                "quantitySold"=>$totalSoldByProduct[$product->id] ?? 0 ,
            ];
        }
        return response()->json([
            "message"=> "Get the number of products sold successfully !",
            "results"=>   $results, 
            "status"=> "success"
        ]);
    }
    public function inventory(){
        $products = Product::get();
        $orders = Order::get();
        $newArr = [];
        foreach($products as $item){
            $newArr[$item->id]=[
                "productId"=> $item->id,
                "productName"=>$item->productName,
                "storage"=> $item->storage
            ];
        }
        foreach($orders as $order){
            foreach($order->products as $item){
                if(isset($newArr[$item['productId']])){
                    $newArr[$item['productId']]['storage'] -= $item['quantity'];
                }
            }
        }
        $newResults = array_values($newArr);
        return response()->json([
            "message"=> "Get number of products inventory successfully !",
            "results"=>  $newResults ,
            "status"=> "success"
        ]);
    }
    public function getQuantitySoldByCurrentYear(){
        $results =[];
        $orders = Order::all();
        for($month = 1 ; $month <= 12 ; $month++){
            $results[$month]=[
                "month"=> $month,
                "year"=> now()->year,
                "total_quantity_sold"=> 0 
            ];
        }
        foreach($orders as $order){
            $year = $order->year;
            $month = $order->month;
            foreach($order->products as $product){
                $results[$month]['total_quantity_sold'] += $product['quantity'];
            }
        }
        $newResults = array_values($results);
        return response()->json([
            "message" => "Get number of products by month successfully !",
            "results"=>    $newResults,
            "status"=> "success"
        ]);
        
    }

    public function getOne(string $productId)
    {
        $product = Product::where('id','=',$productId)->first();
        if(!$product){
            return response()->json([
                "message"=> "Id product not found !",
                "status"=> "error"
            ]);
        }
        return response()->json([
            "message" => "Get one product information successfully !",
            "results"=> $product,
            "status"=> "success"
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $productId , Request $request)
    {
         $product = Product::where('id','=',$productId)->update($request->all());
         return response()->json([
            "message"=> "Updated product successfully !",
            "results"=>$product ,
            "status"=> "success"
         ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId)
    {
        $product = Product::where('id','=',$productId)->delete();
        if(!$product){
            return response()->json(
                [
                    'message'=> "id Not Found !",
                    "status"=> 'error'
                ]
                );
        }
        return response()->json([
            "message"=> "Deleted product successfully !",
            "results"=>  $product,
            "status"=> "success"
        ]);
    }
}
