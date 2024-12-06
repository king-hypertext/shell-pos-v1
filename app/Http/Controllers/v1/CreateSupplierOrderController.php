<?php

namespace App\Http\Controllers\v1;

use App\Models\Products;
use App\Models\Suppliers;
use App\Models\ProductStats;
use Illuminate\Http\Request;
use App\Models\SupplierOrders;
use App\Models\SupplierInvoice;
use App\Http\Controllers\Controller;

include_once '_token.php';
class CreateSupplierOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "CREATE ORDER";
        $suppliers = Suppliers::all();
        return view('pages.create_supplier_order', compact('title', 'suppliers'));
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
            'product.*' => 'required|exists:products,id',
        ]);
        $supplier = Suppliers::find($request->supplier);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $invoice_number = $request->input('supplier-invoice');
        $date = $request->input('invoice-date');
        $days = array('sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $day = now()->dayOfWeek;
        // dd($request->all());
        for ($i = 0; $i < count($products); $i++) {
            $product = Products::find($products[$i]);
            $order = [
                'order_number' => mt_rand(110000, 909009),
                'token' => _token,
                'supplier_id' => $supplier->id,
                'supplier' => $supplier->name,
                'product' => $product->name,
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$day],
                'created_at' => $date
            ];
            ProductStats::insert([
                'qty_received' => $quantity[$i],
                'product' => $products[$i],
                'product_id' => $product->id,
                'from' => $supplier->name,
                'before_qty' => $product->quantity,
                'after_qty' => $product->quantity + $quantity[$i],
                'date' => now()->format('Y-m-d H:i:s')
            ]);
            SupplierOrders::insert($order);
            Products::where('name', $products[$i])->increment('quantity', $quantity[$i]);
            /* update the price of the products when price is changed */
            $product->price = $price[$i];
            $product->save();
        }
        $amount = SupplierOrders::where('token', _token)->sum('amount');
        SupplierInvoice::insert([
            'token' => _token,
            'invoice_number' => $invoice_number,
            'supplier_id' => $supplier->id,
            'supplier' => $supplier->name,
            'amount' => $amount,
            'created_at' => $date
        ]);
        return back()->with('success', 'Order Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = "CREATE ORDER";
        $supplier = Suppliers::where('id', $id)->first();
        return view('pages.create_supplier_order', compact('title', 'supplier'));
        // return response()->json(['data' => $supplier]);
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
