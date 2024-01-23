<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class CreateSupplierOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "CREATE ORDER";
        $suppliers = Suppliers::all();
        $products = Products::all();
        return view('pages.create_supplier_order', compact('title', 'suppliers', 'products'));
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
        $request->dd();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Suppliers::where('id', $id)->get();
        return response()->json(['data' => $supplier]);
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
