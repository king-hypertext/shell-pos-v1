<?php

namespace App\Http\Controllers\v1;

use App\Models\Products;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Orders;
use App\Models\Suppliers;

class IndexController extends Controller
{
    public function index()
    {
        $orders = Orders::all()->count();
        $today_orders = Orders::query()->where('created_at', today())->count();
        $products = Products::all()->count();
        $out_of_stock = Products::query()->where('quantity', '<', 1)->count();
        $low_stock = Products::query()->where('quantity', '>', 1)->where('quantity', '<=', 5)->count();
        $expired_products = Products::query()->where('created_at', '>=', now()->addDays(5))->count();
        $workers = Customers::all()->count();
        $suppliers = Suppliers::all()->count();
        return view('pages.dashboard', compact('orders', 'today_orders', 'products', 'out_of_stock', 'low_stock', 'expired_products', 'workers', 'suppliers'));
    }
}
