<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "WORKERS VAN";
        $suppliers = Suppliers::all();
        return view('pages.suppliers', compact('title', 'suppliers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Suppliers::insert([
            "name" => $request->input("supplier-name"),
            "category" => $request->input("category"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "created_at" => now()->format('Y-m-d H:i:s')
        ]);

        return back()->with("success", "New Supplier Added");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Suppliers::find($id);
        return response()->json($supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $supplier = Suppliers::find($id);
        Suppliers::where('id', $supplier->id)->update([
            "name" => $request->input("edit-supplier"),
            "category" => $request->input("edit-gender"),
            "address" => $request->input("edit-address"),
            "contact" => $request->input("edit-contact"),
            "created_at" => now()->format('Y-m-d')
        ]);
        return back()->with("success", "Supplier Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Suppliers::destroy($id);
        return back()->with("success", "Supplier Deleted");
    }
}
