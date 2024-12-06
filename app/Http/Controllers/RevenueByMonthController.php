<?php

namespace App\Http\Controllers;

use App\Models\Revenue_by_month;
use App\Http\Requests\StoreRevenue_by_monthRequest;
use App\Http\Requests\UpdateRevenue_by_monthRequest;
use Illuminate\Http\Request;

class RevenueByMonthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $currentYear = now()->year;
        $getAll = Revenue_by_month::get();
        $results = [];
        for($month = 1 ; $month <= 12 ; $month++){
            $results[$month] = [
                "month"=> $month,
                "year"=> $currentYear,
                "totalRevenue"=> 0 
            ];
        }
        foreach($getAll as $item){
                $results[$item['month']]['totalRevenue'] += $item['totalRevenue'];
        }
        $newResults = array_values($results);
        return response()->json([
            "message"=> 'Get all revenue by month successfully !',
            "results"=>  $newResults,
            "status"=> "success"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRevenue_by_monthRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Revenue_by_month $revenue_by_month)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Revenue_by_month $revenue_by_month)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRevenue_by_monthRequest $request, Revenue_by_month $revenue_by_month)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Revenue_by_month $revenue_by_month)
    {
        //
    }
}
