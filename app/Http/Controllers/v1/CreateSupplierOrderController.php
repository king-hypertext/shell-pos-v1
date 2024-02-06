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
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $supplier = Suppliers::find($request->supplier);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $invoice_number = $request->input('supplier-invoice');
        $date = $request->input('invoice-date');
        $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $day = now()->dayOfWeek;
        for ($i = 0; $i < count($products); $i++) {
            $order = [
                'order_number' => mt_rand(110000, 909009),
                'token' => _token,
                'supplier_id' => $supplier->id,
                'supplier' => $supplier->name,
                'product' => $products[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'amount' => ($price[$i] * $quantity[$i]),
                'day' => $days[$day],
                'created_at' => $date
            ];
            $before_qty = Products::where('name', $products[$i])->value('quantity');
            ProductStats::insert([
                'product' => $products[$i],
                'product_id' => Products::where('name', $products[$i])->value('id'),
                'supplied' => $quantity[$i],
                'to' => $supplier->name,
                'before_qty' => $before_qty,
                'after_qty' => $before_qty - $quantity[$i],
                'qty' => $quantity[$i],
                'date' => now()->format('Y-m-d H:i:s')
            ]);
            SupplierOrders::insert($order);
            Products::where('name', $products[$i])->increment('quantity', $quantity[$i]);
            /* update the price of the products when price is changed */
            Products::where('name', $products[$i])->update([
                'price' => $price[$i],
            ]);
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
