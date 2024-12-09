<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Revenue;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
    }
    public function revenueByCurrentYear()
    {

        $currentYear = now()->year;
        $resultsOfCurrentYear = Revenue::where('year', '=', $currentYear)->get();
        $monthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthData[$month] = [
                "month" => $month,
                "year" =>  $currentYear,
                "totalRevenue" => 0
            ];
        }
        foreach ($resultsOfCurrentYear as $item) {
            $monthData[$item['month']]['totalRevenue'] += $item['totalRevenue'];
        }
        $newResults = array_values($monthData);
        return response()->json([
            "message " => "Get revenue by current year successfully !",
            "results" =>  $newResults,
            "status" => "success"
        ], 200);
    }
    public function revenueByCurrentMonth()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $results  = Revenue::where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear)->get();
        return response()->json([
            "message" => "Get revenue by current month successfully !",
            "results" => $results,
            "status" => "success"
        ]);
    }
    public function revenueByCurrentWeek()
    {
        $currentWeek = now()->week;
        $currentYear = now()->year;
        $results  = Revenue::where('year', '=', $currentYear)
            ->where('week', '=', $currentWeek)->get();
        return response()->json([
            "message" => "Get revenue by current week successfully !",
            "results" =>  $results,
            "status" => "success"
        ]);
    }
    public function revenueByCurrentDay()
    {
        $currentDay = now()->day;
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $currentWeek = now()->week;
        $results = Revenue::where('day', '=', $currentDay)
            ->where('week', "=",   $currentWeek)
            ->where('month', "=", $currentMonth)
            ->where('year', '=', $currentYear)->get();
        return response()->json([
            "message"  => "Get revenue by current day successfully ! ",
            "results" =>  $results,
            "status" => "success"
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function yearOfAllYears()
    {
        $results = Revenue::get();
        $revenueAllYears = [];
        foreach ($results as $item) {
            $revenueAllYears[$item->year] = [
                "year" => $item->year,
                "totalRevenue" => 0
            ];
        }
        foreach ($results as $item) {
            $revenueAllYears[$item->year]["totalRevenue"] += $item['totalRevenue'];
        }
        $newResults = array_values($revenueAllYears);
        return response()->json([
            "message" => "Get revenue all years successfully !",
            "results" =>   $newResults,
            "status" => "success"
        ]);
    }
    public function revenueAllMonthsOfCurrentYear()
    {
        $results = Revenue::where('year', "=", now()->year)->get();
        $newArr = [];
        for ($month = 1; $month <= 12; $month++) {
            $newArr[$month] = [
                "month" => $month,
                "year" => now()->year,
                "totalRevenue" => 0
            ];
        }
        foreach ($results as $item) {
            $newArr[$item['month']]['totalRevenue'] += $item['totalRevenue'];
        }
        $newResults = array_values($newArr);
        return response()->json([
            "message" => "Get revenue all months of current year successfully !",
            "results" =>  $newResults,
            "status" => "success"
        ]);
    }
    public function filterByMonthAndYear(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $type = $request->input('type');
        $newArr = [];
        switch ($type) {
            case 'QUANTITY_SOLD_YEAR':
                $orders = Order::where('year', '=', $year)->get();
                for ($month = 1; $month <= 12; $month++) {
                    $newArr[$month] = [
                        'month' => $month,
                        "year" =>  $year,
                        'total_quantity_sold' => 0
                    ];
                }
                if ($orders->isEmpty()) {
                    return response()->json([
                        "message" => "Year and month not found !",
                        "results" => array_values($newArr),
                        "status" => "failure"
                    ]);
                }
                foreach ($orders as $item) {
                    $yearOfOrder = $item->year;
                    $monthOfOrder = $item->month;
                    foreach ($item->products as $product) {
                        if ($yearOfOrder === $year) {
                            $newArr[$monthOfOrder]['total_quantity_sold'] += $product['quantity'];
                        }
                    }
                }
                $newArr = array_values($newArr);
                break;
            case 'REVENUE_BY_YEAR':
                $revenues = Revenue::where('year', '=', $year)->get();
                for ($month = 1; $month <= 12; $month++) {
                    $newArr[] = [
                        "month" => $month,
                        "year" => $year,
                        "totalRevenue" => 0
                    ];
                }
                if ($revenues->isEmpty()) {
                    return response()->json([
                        "message" => "Year and month not found !",
                        "results" => $newArr,
                        "status" => "failure"
                    ]);
                }
                foreach ($revenues as $item) {
                    if ($item->year === $year) {
                        $newArr[$item->month - 1]['totalRevenue'] = $item->totalRevenue;
                    }
                }
                $newArr = array_values($newArr);
                break;

            default:
                # code...
                break;
        }
        return response()->json([
            "message" => "Filter by month and year successfully !",
            "results" =>  $newArr,
            "status" => "success"
        ]);
    }
    public function create()
    {
        //
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
