<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductStats;
use Illuminate\Http\Request;

class ProductStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = ProductStats::orderBy('created_at', 'DESC')->get();
        return view('product.index', ['title' => 'PRODUCT STATS', 'stats' => $stats]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $product)
    {
        // return Products::query()->where('id', $id)->get();
        $stats = ProductStats::where('product_id', $product->id)->orderBy('date', 'DESC')->get();
        // dd($stats);
        return view('product.index', ['title' => 'PRODUCT STATS', 'product' => $product, 'stats' => $stats]);
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
