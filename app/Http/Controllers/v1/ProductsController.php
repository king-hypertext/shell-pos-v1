<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $products = Products::all();
        $title = "ALL PRODUCTS";
        $products = Products::all();
        return view('pages.products', compact('title', 'products'));
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
        $request->validate([
            'customer-name' => 'required|string',
            'contact' => 'required|numeric',
            'product-image' => 'required|file|mimes:png,jpg,jpeg,webp',
        ]);

        if ($request->hasFile("product-image")) {
            $path = $request->file("product-image")->store('/public/customers');
        }
        Products::insert([
            "name" => $request->input("customer-name"),
            "gender" => $request->input("gender"),
            "date_of_birth" => $request->input("dob"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "image" => '/storage/customers/' . str_replace(['public/', 'customers/'], '', $path),
            "created_at" => now()->format('Y-m-d')
        ]);

        return back()->with("success", "New Worker Added");
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $data = Products::where('id', $id)->first();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $products)
    {

        $product = $products->find($request->id);
        if ($request->hasFile('product-image')) {
            $request->validate([
                "product-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            if (file_exists(public_path("$product->image"))) {
                unlink(public_path("$product->image"));
            }
            $path = $request->file("product-image")->store('/public/products');
            Products::where('id', $product->id)->update([
                'name' => $request->input('product-name'),
                'price' => $request->price,
                'batch_number' => $request->input('batch-number'),
                'supplied_by' => $request->supplier,
                'category' => $request->category,
                'prod_date' => $request->prod_date,
                'expiry_date' => $request->expiry_date,
                'image' => '/storage/products/' . str_replace(['public/', 'products/'], '', $path),
                'updated_at' => now()->format('Y-m-d')
            ]);
        } else {
            Products::where('id', $product->id)->update([
                'name' => $request->input('product-name'),
                'price' => $request->price,
                'batch_number' => $request->input('batch-number'),
                'supplied_by' => $request->supplier,
                'category' => $request->category,
                'prod_date' => $request->prod_date,
                'expiry_date' => $request->expiry_date,
                'updated_at' => now()->format('Y-m-d')
            ]);
        }
        return back()->with('success', 'Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Products $products)
    {
        $product = $products->find($request->id);
        if (file_exists(public_path("$product->image"))) {
            unlink(public_path("$product->image"));
        }
        Products::destroy($product->id);
        return back()->with('success', 'Product Deleted');
    }
    public function fetch(string $name)
    {
        $data = Products::where('name', $name)->get();
        return response()->json(['data' => $data]);
    }
}
