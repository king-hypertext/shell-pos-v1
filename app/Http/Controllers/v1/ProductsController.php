<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Psy\CodeCleaner\ReturnTypePass;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "ALL PRODUCTS";
        $heading = $title;
        if ($request && $request->query('query') === 'out-of-stock') {
            $heading = "products out of stock";
            $products = Products::query()->where('quantity', '<', 1)->get();
        } elseif ($request && $request->query('query') === 'low-stock') {
            $heading = "products low stock";
            $products = Products::query()->where('quantity', '>', 1)->where('quantity', '<=', 5)->get();
        } elseif ($request && $request->query('query') === 'expired') {
            $heading = "Expired Products";
            $products =  Products::query()->where('expiry_date', '<=', now()->addWeek()->format("Y-m-d"))->get();
        } else {
            $products = Products::query()->latest('created_at')->get();
            // return $products;
        }
        return view('pages.products', compact('title', 'products', 'heading'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "CREATE NEW PRODUCT";
        $products = Products::query()->latest('created_at')->get();
        return view('pages.create_product', compact('title', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product-name' => 'required|string|unique:products,name',
            'supplier' => 'required|string|exists:suppliers,name',
        ], [
            'product-name.unique' => 'The Product Already Exists',
            'supplier.exists' => 'The Supplier Does Not Exist'
        ]);
        $path = "";
        if ($request->hasFile("product-image")) {
            $path = $request->file("product-image")->store('/public/products');
        }
        Products::insert([
            'name' => $request->input('product-name'),
            'price' => $request->price,
            'batch_number' => $request->input('batch-number'),
            'supplied_by' => $request->supplier,
            'category' => $request->category,
            'prod_date' => $request->prod_date,
            'expiry_date' => $request->expiry_date,
            'image' => '/storage/products/' . str_replace(['public/', 'products/'], '', $path) ?? null,
            'created_at' => now()->format('Y-m-d')
        ]);

        return back()->with("success", "New Product Added");
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $product)
    {
        return $product;
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
        $ids = $request->id;
        $products = Products::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if (file_exists(public_path("$product->image"))) {
                unlink(public_path("$product->image"));
            }
        }
        Products::destroy($ids);
        return response()->json(['success' => 'Products Deleted']);
    }
    public function fetch(string $name)
    {
        $data = Products::where('name', $name)->get();
        return response()->json(['data' => $data]);
    }
    public function fetchProductSupplier(int $id)
    {
        $supplier = Suppliers::where('id', $id)->first()->name;
        $products = Products::where('supplied_by', $supplier)->get('name');
        return response()->json($products);
    }
}
