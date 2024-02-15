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
        $suppliers = Suppliers::orderBy('created_at', 'DESC')->get();
        return view('pages.suppliers', compact('title', 'suppliers'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:suppliers,name'
            ],
            [
                'name.unique' => 'Supplier already exist'
            ]
        );
        Suppliers::insert([
            "name" => $request->name,
            "category" => $request->category,
            "address" => $request->address,
            "contact" => $request->contact,
            "created_at" => now()->format('Y-m-d H:i:s')
        ]);
        return response()->json(['success' => 'Success, Supplier Added']);
        // return back()->with("success", "New Supplier Added");
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
        Suppliers::where('id', $id)->update([
            "name" => $request->input("edit-supplier"),
            "category" => $request->input("edit-category"),
            "address" => $request->input("edit-address"),
            "contact" => $request->input("edit-contact"),
            "created_at" => now()->format('Y-m-d H:i:s')
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
