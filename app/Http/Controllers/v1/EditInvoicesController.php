<?php

namespace App\Http\Controllers\v1;

use App\Models\Orders;
use App\Models\Invoice;
use App\Models\Products;
use App\Models\Customers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SupplierOrders;

use function PHPUnit\Framework\isEmpty;

include_once '_token.php';
class EditInvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $worker_name = $request->worker;
        $order_date = $request->date;
        $data = Orders::where('customer', $worker_name)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->get();
        if ($request->ajax()) {
            return count($data) > 0 ? response()->json(['data' => route('order.edit', ['worker' => $worker_name, 'order_date' => $order_date])]) : response()->json(['empty' => 'No Data Found']);
        }
    }
    public function indexSupplier(Request $request)
    {
        $supplier = $request->supplier;
        $order_date = $request->date;
        $data = SupplierOrders::where('supplier', $supplier)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->get();
        if ($request->ajax()) {
            return count($data) > 0 ? response()->json(['data' => route('order.supplier.edit', ['supplier' => $supplier, 'order_date' => $order_date])]) : response()->json(['empty' => 'No Data Found']);
        }
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
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $customer = Customers::find($request->customer_id);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $date = $request->input('invoice-date');
        $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $day = now()->dayOfWeek;

        if ($request->has('order_token')) {
            $request->validate(
                [
                    'order_token' => 'string|exists:customer_orders,order_token'
                ],
                [
                    'order_token.exists' => 'Token mismatched'
                ]
            );
            for ($i = 0; $i < count($products); $i++) {
                Products::where('name', $products[$i])->increment('quantity', $quantity[$i]);
            }
            Orders::query()->where('order_token', $request->order_token)->delete();
            Invoice::query()->where('token', $request->order_token)->delete();
        }
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
                'created_at' => $date,
                'updated_at' => now()->format('Y-m-d')
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
            "created_at" => $date,
            "updated_at" => now()->format('Y-m-d')
        ]);
        return redirect()->route('order.edit', ['worker' => $customer->name, 'order_date' => $date])->with('success', 'Order Updated');
    }

    public function storeSupplier(Request $request)
    {
        // $request->dd();
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $customer = Customers::find($request->customer_id);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $date = $request->input('invoice-date');
        $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
        $day = now()->dayOfWeek;

        if ($request->has('order_token')) {
            $request->validate(
                [
                    'order_token' => 'string|exists:customer_orders,order_token'
                ],
                [
                    'order_token.exists' => 'Token mismatched'
                ]
            );
            for ($i = 0; $i < count($products); $i++) {
                Products::where('name', $products[$i])->increment('quantity', $quantity[$i]);
            }
            Orders::query()->where('order_token', $request->order_token)->delete();
            Invoice::query()->where('token', $request->order_token)->delete();
        }
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
                'created_at' => $date,
                'updated_at' => now()->format('Y-m-d')
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
            "created_at" => $date,
            "updated_at" => now()->format('Y-m-d')
        ]);
        return redirect()->route('order.supplier.edit', ['supplier' => $customer->name, 'order_date' => $date])->with('success', 'Order Updated');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $worker_name = $request->worker;
        $order_date = $request->order_date;
        $data = Orders::where('customer', $worker_name)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->get();
        if ($data->count() == 0) {
            return "Empty data " . "<a href='/dashboard'>Go to dashboard</a>";
        }
        foreach ($data as $order) {
            $token = $order->order_token;
            $id = $order->customer_id;
            $customer = $order->customer;
            $date = $order->created_at;
        }
        return view("pages.saved_order", ['data' => $data, 'order_token' => $token, 'customer_id' => $id, 'customer' => $customer, 'date' => $date]);
    }

    public function editSupplier(Request $request)
    {
        $supplier = $request->supplier;
        $order_date = $request->order_date;
        $data = SupplierOrders::where('supplier', $supplier)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->get();
        // dd($data);
        if ($data->count() == 0) {
            return "Empty data " . "<a href='/dashboard'>Go to dashboard</a>";
        }
        foreach ($data as $order) {
            $token = $order->token;
            $id = $order->supplier_id;
            $supplier = $order->supplier;
            $date = $order->created_at;
        }
        return view("pages.saved_order_supplier", ['data' => $data, 'order_token' => $token, 'supplier_id' => $id, 'supplier' => $supplier, 'date' => $date]);
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
    public function destroy(Request $request)
    {
        $ids = $request->id;
        for ($i = 0; $i < count($ids); $i++) {
            $qty = Orders::where('id', $ids[$i])->first()->value('quantity');
            Products::where('id', $ids[$i])->increment('quantity', $qty);
        }
        Orders::destroy($ids);
        return response()->json(['success' => 'Deleted']);
    }

    public function destroySupplier(Request $request)
    {
        $ids = $request->id;
        for ($i = 0; $i < count($ids); $i++) {
            $qty = SupplierOrders::where('id', $ids[$i])->first()->value('quantity');
            Products::where('id', $ids[$i])->decrement('quantity', $qty);
        }
        SupplierOrders::destroy($ids);
        return response()->json(['success' => 'Deleted']);
    }
}
