<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Revenue;
use App\Models\Product;

class RevenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
    }
    public function getDaysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
    public function revenueOverview()
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $currentWeek = now()->week;
        $currentDay = now()->day;

        // [Revenue By Year]
        $resultsOfCurrentYear = Revenue::where('year', '=', $currentYear)->get();
        $monthData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthData[$month] = [
                "month" => $month,
                "year" => $currentYear,
                "totalRevenue" => 0
            ];
        }
        foreach ($resultsOfCurrentYear as $item) {
            $monthData[$item['month']]['totalRevenue'] += $item['totalRevenue'];
        }
        $revenueByYear = array_values($monthData);

        // [Revenue By Month]
        $resultsByMonth = Revenue::where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear)->get();
        // [Revenu number of days in current month]
        $revenueByDaysInCurrentMonth = [];
        $numberOfDays = $this->getDaysInMonth($currentMonth, $currentYear);
        for ($day = 1; $day <= $numberOfDays; $day++) {
            $revenueByDaysInCurrentMonth[] = [
                "day" => $day,
                "month" => $currentMonth,
                "year" => $currentYear,
                "totalRevenue" => 0
            ];
        }
        $resultsByDaysInCurrentMonth = Revenue::where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear)->get();
        for ($index = 0; $index < count($revenueByDaysInCurrentMonth); $index++) {
            foreach ($resultsByDaysInCurrentMonth as $result) {
                if (
                    $result['month'] === $currentMonth
                    && $result['year'] === $currentYear
                    && $result['day'] === $revenueByDaysInCurrentMonth[$index]['day']
                ) {
                    $revenueByDaysInCurrentMonth[$index]['totalRevenue'] += $result['totalRevenue'];
                }
            }
        }
        // [Revenue By Week]
        $resultsByWeek = Revenue::where('year', '=', $currentYear)
            ->where('week', '=', $currentWeek)->get();

        // [Revenue By Day]
        $resultsByDay = Revenue::where('day', '=', $currentDay)
            ->where('week', '=', $currentWeek)
            ->where('month', '=', $currentMonth)
            ->where('year', '=', $currentYear)->get();

        // [Revenue All Years]
        $resultsOfAllYears = Revenue::get();
        $revenueAllYears = [];
        foreach ($resultsOfAllYears as $item) {
            $revenueAllYears[$item->year] = [
                "year" => $item->year,
                "totalRevenue" => 0
            ];
        }
        foreach ($resultsOfAllYears as $item) {
            $revenueAllYears[$item->year]["totalRevenue"] += $item['totalRevenue'];
        }
        $revenueAllYears = array_values($revenueAllYears);
        //[ method payment]
        $methodPayments = ['momo', 'zalo', 'visa', 'cash'];
        $revenueMethodPayment = [];
        for ($index =  0; $index < count($methodPayments); $index++) {
            $revenueMethodPayment[] = [
                "methodPayment" => $methodPayments[$index],
                "orderQuantity" => 0
            ];
        }
        $orders = Order::get();

        foreach ($orders as $order) {
            for ($index = 0; $index < count($revenueMethodPayment); $index++) {
                if ($revenueMethodPayment[$index]['methodPayment'] === $order['methodPayment']) {
                    $revenueMethodPayment[$index]['orderQuantity'] += 1;
                }
            }
        }

        return response()->json([
            "message" => "Get all revenue data successfully!",
            "results" => [
                "revenueByYear" => $revenueByYear,
                "revenueByMonth" => $resultsByMonth,
                "revenueByDaysInCurrentMonth" =>   $revenueByDaysInCurrentMonth,
                "revenueByWeek" => $resultsByWeek,
                "revenueByDay" => $resultsByDay,
                "methodPayments" =>   $revenueMethodPayment,
                "revenueAllYears" => $revenueAllYears,
            ],
            "status" => "success"
        ], 200);
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
            case 'QUANTITY_SOLD_BY_YEAR':
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
                        "payload" => [
                            'type' => $type,
                            'month' => $month,
                            'year' => $year,
                        ],
                        "results" => $newArr,
                        "status" => "failure"
                    ]);
                }
                foreach ($revenues as $item) {
                    for ($month = 1; $month <= 12; $month++) {
                        if ($item->month === $month) {
                            $newArr[$month - 1]['totalRevenue'] += $item->totalRevenue;
                        }
                    }
                }
                $newArr = array_values($newArr);
                break;
            case 'REVENUE_BY_MONTH':
                $revenues = Revenue::where('year', '=', $year)
                    ->where('month', '=', $month)->get();
                $numberOfDays = $this->getDaysInMonth($month, $year);
                for ($day = 1; $day <= $numberOfDays; $day++) {
                    $newArr[] = [
                        "day" => $day,
                        "month" => $month,
                        "year" => $year,
                        "totalRevenue" => 0
                    ];
                }
                if ($revenues->isEmpty()) {
                    return response()->json([
                        "message" => "Year and month not found !",
                        "payload" => [
                            'type' => $type,
                            'month' => $month,
                            'year' => $year,
                        ],
                        "results" => $newArr,
                        "status" => "failure"
                    ]);
                }
                foreach ($revenues as $item) {
                    if ($item->year === $year && $item->month === $month) {
                        $newArr[$item->day - 1]['totalRevenue'] = $item->totalRevenue;
                    }
                }
                $newArr = array_values($newArr);
                break;
            case 'QUANTITY_SOLD_BY_MONTH':
                $orders = Order::where('year', '=', $year)
                    ->where('month', '=', $month)->get();
                $numberOfDays = $this->getDaysInMonth($month, $year);
                for ($day = 1; $day <= $numberOfDays; $day++) {
                    $newArr[] = [
                        "day" => $day,
                        "month" => $month,
                        "year" => $year,
                        "total_quantity_sold" => 0
                    ];
                }
                if ($orders->isEmpty()) {
                    return response()->json([
                        "message" => "Year and month not found !",
                        "payload" => [
                            'type' => $type,
                            'month' => $month,
                            'year' => $year,
                        ],
                        "results" => $newArr,
                        "status" => "failure"
                    ]);
                }
                foreach ($orders as $item) {
                    $yearOfOrder = $item->year;
                    $monthOfOrder = $item->month;
                    foreach ($item->products as $product) {
                        if ($yearOfOrder === $year && $monthOfOrder === $month) {
                            $newArr[$item->day - 1]['total_quantity_sold'] += $product['quantity'];
                        }
                    }
                }

                break;
            default:
                # code...
                break;
        }
        return response()->json([
            "message" => "Filter by month and year successfully !",
            "payload" => [
                'type' => $type,
                'month' => $month,
                'year' => $year,
            ],
            "results" =>  $newArr,
            "status" => "success"
        ]);
    }
    public function create()
    {
        //
    }


    public function revenueProduct()
    {
        $productId = request()->input('productId');
        $productName = request()->input('productName');
        $orders = Order::get();
        $arrYears = [];
        foreach ($orders as $order) {
            if (!isset($arrYears[$order['year']])) {
                $arrYears[$order['year']] = [
                    'year' => $order['year'],
                    'productId'  => $productId,
                    'productName' =>  $productName,
                    'totalRevenue' => 0
                ];
            }
        }

        foreach ($arrYears as $item) {
            foreach ($orders as $order) {
                foreach ($order['products'] as $product) {
                    if ($item['year'] === $order['year'] && $product['productId'] === $item['productId']) {
                        $arrYears[$item['year']]['totalRevenue'] += $product['productPrice'] * $product['quantity'];
                    }
                }
            }
        }

        return response()->json([
            "message" => "Filter by month and year successfully !",
            "results" =>   array_values($arrYears),
            "status" => "success"
        ], 200);
    }
    // Output :  [{year : number , monthlyRevenue : [{month : 1 , totalRevenue : 1000 },...] }]
    public function fetchProductData(Request $request)
    {
        $orders = Order::get();
        $productId = $request->input('productId');
        $yearlyRevenue = [];
        $defaultMonthlyRevenue = array_map(fn($month) =>
        [
            "month" => $month,
            'total_quantity_sold' => 0,
            'totalRevenue' => 0
        ], range(1, 12));
        foreach ($orders as $order) {
            if (!isset($yearlyRevenue[$order['year']])) {
                $yearlyRevenue[$order['year']] = [
                    'year' => $order['year'],
                    "monthlyRevenue" => $defaultMonthlyRevenue
                ];
            }
        }
        ksort($yearlyRevenue);
        $yearlyRevenue = array_values($yearlyRevenue);

        foreach ($yearlyRevenue as &$yearData) {
            foreach ($orders as $order) {
                if ($yearData['year']  === $order['year']) {
                    foreach ($order['products'] as $product) {
                        if ($product['productId'] === $productId) {
                            foreach ($yearData['monthlyRevenue'] as &$monthlyRevenue) {
                                if ($monthlyRevenue['month'] === $order['month']) {
                                    $monthlyRevenue['total_quantity_sold'] +=  $product['quantity'];
                                    $monthlyRevenue['totalRevenue'] += $product['productPrice'] * $product['quantity'];
                                }
                            }
                        }
                    }
                }
            }
        }
        return response()->json([
            "message" => "Fetch all monthly revenue of years successfully !",
            "results" =>   $yearlyRevenue,
            "status" => "success"
        ]);
    }
    public function searchData()
    {
        $type_search = request()->input('type_search');
        $results = [];
        switch ($type_search) {
            case 'REVENUE_METHOD_PAYMENT':
                $orders = Order::get();
                $methodPayments = ['momo', 'zalo', 'visa', 'cash'];
                $revenueMethodPayment = [];
                for ($index =  0; $index < count($methodPayments); $index++) {
                    $revenueMethodPayment[] = [
                        "methodPayment" => $methodPayments[$index],
                        "totalRevenue" => 0
                    ];
                }
                foreach ($orders as $order) {
                    for ($index = 0; $index < count($revenueMethodPayment); $index++) {
                        if ($revenueMethodPayment[$index]['methodPayment'] === $order['methodPayment']) {
                            $revenueMethodPayment[$index]['totalRevenue'] += $order['totalOrder'];
                        }
                    }
                }
                $results = $revenueMethodPayment;

                break;
            case 'BEST_SELLING_PRODUCT':
                $orders = Order::get();
                $products = Product::get();
                $bestSellingProduct = [];
                $nonSellingProduct = [];
                foreach ($products as $product) {
                    $bestSellingProduct[] = [
                        "productName" => $product['productName'],
                        "totalQuantitySold" => 0
                    ];
                    $nonSellingProduct[] = [
                        "productName" => $product['productName'],
                        "totalQuantitySold" => 0
                    ];
                }
                foreach ($orders as $order) {
                    foreach ($order->products as $product) {
                        for ($index = 0; $index < count($bestSellingProduct); $index++) {
                            if ($bestSellingProduct[$index]['productName'] === $product['productName']) {
                                $bestSellingProduct[$index]['totalQuantitySold'] += $product['quantity'];
                                $nonSellingProduct[$index]['totalQuantitySold'] += $product['quantity'];
                            }
                        }
                    }
                }
                $bestSellingProduct = array_filter($bestSellingProduct, function ($product) {
                    return $product['totalQuantitySold'] > 10;
                });
                $nonSellingProduct = array_filter($nonSellingProduct, function ($product) {
                    return $product['totalQuantitySold'] <= 10;
                });
                $results = [
                    'bestSellingProduct' => array_values($bestSellingProduct),
                    'nonSellingProduct' => array_values($nonSellingProduct)
                ];
                break;
        }
        return response()->json([
            "message" => "Search data successfully !",
            "results" => $results,
            "status" => "success"
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
