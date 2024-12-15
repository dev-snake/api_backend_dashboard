<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\UserController;

Route::prefix('/product')->controller(ProductController::class)->group(function () {
        Route::get('/getAll', 'index');
        Route::get('/get_quantity_sold', 'getQuantitySold');
        Route::get('/quantity-sold/current-year', 'getQuantitySoldByCurrentYear');
        Route::post('/create', 'create');
        Route::put('/{projectId}/edit', 'edit');
        Route::get('/getOne/{projectId}', 'getOne');
        Route::delete('/{productId}/delete', 'destroy');
        Route::get('/inventory', 'inventory');
});
Route::prefix('/user')->controller(UserController::class)->group(function () {
        Route::get('/getAll', 'index');
        Route::post('/create', 'create');
        Route::put('/{userId}/edit', 'edit');
        Route::delete('/{userId}/delete', 'destroy');
        Route::get('/getOne/{userId}', 'getOne');
});

Route::prefix('revenue')->controller(RevenueController::class)->group(function () {
        Route::get('/all-months/current-year', action: 'revenueAllMonthsOfCurrentYear');
        Route::get('/revenue_by_year/current-year', 'revenueByCurrentYear');
        Route::get('/revenue_by_month/current-year', 'revenueByCurrentMonth');
        Route::get('/revenue_by_week/current-year', 'revenueByCurrentWeek');
        Route::get('/revenue_by_day/current-year', 'revenueByCurrentDay');
        Route::get('/years', 'yearOfAllYears');
        Route::post('/filter', 'filterByMonthAndYear');
        Route::post('/search', 'searchData');
        Route::post('/search/v2', 'fetchProductData');
        Route::get('/overview', 'revenueOverview');
        Route::post('/revenue-product', 'revenueProduct');
});
Route::prefix('/orders')->controller(OrderController::class)->group(function () {
        Route::get('/getAll', 'index');
        Route::post('/create', 'create');
        Route::get('/getOne/{orderId}', 'getOne');
        Route::get('/buyers', 'getBuyersInfo');
});
