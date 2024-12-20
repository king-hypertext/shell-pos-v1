<?php

namespace App\Http\Controllers\v1;

use App\Models\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Invoice;
use App\Models\Orders;
use App\Models\ProductStats;

include_once '_token.php';
class createCustomerOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "CREATE ORDER";
        $products = Products::all();
        $customers = Customers::all();
        return view('pages.create_customer_order', compact('title', 'customers', 'products'));
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
        $customer = Customers::find($request->customer);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $date = $request->input('invoice-date');
        $days = array('sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $day = now()->dayOfWeek;
        // dd($request->all());
        for ($i = 0; $i < count($products); $i++) {
            $product = Products::find($products[$i]);
            $order = [
                'order_number' => mt_rand(000011, 990099),
                'customer_id' => $customer->id,
                'order_token' => _token,
                'customer' => $customer->name,
                'product' => $product->name,
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'day' => $days[$day],
                'amount' => ($price[$i] * $quantity[$i]),
                'created_at' => $date
            ];
            ProductStats::insert([
                'product' => $products[$i],
                'product_id' => $product->id,
                'supplied' => $quantity[$i],
                'to' => $customer->name,
                'before_qty' => $product->quantity,
                'after_qty' => $product->quantity - $quantity[$i],
                'date' => now()->format('Y-m-d H:i:s')
            ]);
            Orders::insert($order);
            Products::find($products[$i])->decrement('quantity', $quantity[$i]);
        }
        $amount = Orders::where('order_token', _token)->sum('amount');
        Invoice::insert([
            "invoice_number" => mt_rand(1110001, 9990999),
            "token" => _token,
            "customer" => $customer->name,
            "customer_id" => $customer->id,
            "amount" => $amount,
            "created_at" => $date
        ]);

        return back()->with('success', 'Order Saved');
    }

    /** 
     * Display the specified resource.
     */
    public function show(Customers $customer)
    {
        return $customer;
        $customer = $customers->find($id);
        return response()->json(['data' => $customer]);
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
    public function update(Request $request)
    {
        $request->dd();
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $token = $request->order_token;


        return back()->with('success', 'Order Saved');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
