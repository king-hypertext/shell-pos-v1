<?php

namespace App\Http\Controllers\v1;

use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Invoice;
use App\Models\Orders;

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
        // $request->dd();
        $customer = Customers::find($request->customer);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $date = $request->input('invoice-date');
        $days = array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $day = now()->dayOfWeek;

        for ($i = 0; $i < count($products); $i++) {
            $order = [
                'order_number' => mt_rand(000011, 990099),
                'customer_id' => $customer->id,
                'order_token' => _token,
                'customer' => $customer->name,
                'product' => $products[$i],
                'price' => $price[$i],
                'quantity' => $quantity[$i],
                'day' => $days[$day],
                'amount' => ($price[$i] * $quantity[$i]),
                'created_at' => $date
            ];
            Orders::insert($order);
            Products::where('name', $products[$i])->decrement('quantity', $quantity[$i]);
        }
        $amount = Orders::where('order_token', _token)->sum('amount');
        Invoice::insert([
            "invoice_number" => mt_rand(1110001, 9990999),
            "token" => _token,
            "customer" => $customer->name,
            "amount" => $amount,
            "created_at" => $date
        ]);
        return back()->with('success', 'Order Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Customers $customers)
    {
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
