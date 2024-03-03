<?php

namespace App\Http\Controllers\v1;

use App\Models\Orders;
use App\Models\Invoice;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Suppliers;
use App\Models\ProductStats;
use Illuminate\Http\Request;
use App\Models\SupplierOrders;
use App\Models\SupplierInvoice;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

include_once '_token.php';
class EditInvoicesController extends Controller
{
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

    public function store(Request $request)
    {
        // $request->dd();
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $customer = Customers::findOrFail($request->customer_id);
        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $date = $request->input('invoice-date');
        $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $day = now()->dayOfWeek;
        $invoice_number = $request->input('invoice-number');

        if (is_array($request->old_product)) {
            for ($i = 0; $i < count($request->old_product); $i++) {
                ProductStats::where(
                    'to',
                    $customer->name,
                )->where(
                    'supplied',
                    $request->old_qty[$i]
                )->where(
                    'before_qty',
                    Products::where('name', $request->old_product[$i])->value('quantity')
                )->delete();
                $product = Products::where('name', $request->old_product[$i])->first();
                $product->increment('quantity', $request->old_qty[$i]);
            }
        } else {
            $product = Products::where('name', $request->old_product)->first();
            ProductStats::where(
                'to',
                $customer->name,
            )->where(
                'supplied',
                $request->old_qty
            )->where(
                'before_qty',
                Products::where('name', $request->old_product)->value('quantity')
            )->delete();
            if ($product) {
                $product->increment('quantity', $request->old_qty);
            } else {
                return redirect()->route('order.edit', ['worker' => $customer->name, 'order_date' => $date])->with('error', 'product not found');
                // Handle error: product not found or quantity insufficient
            }
        }
        Orders::query()->where('order_token', $request->order_token)->delete();
        Invoice::query()->where('token', $request->order_token)->delete();
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
            ProductStats::insert([
                'supplied' => $quantity[$i],
                'product' => $products[$i],
                'product_id' => Products::where('name', $products[$i])->value('id'),
                'to' => $customer->name,
                'before_qty' => intval(Products::where('name', $products[$i])->value('quantity')),
                'after_qty' => intval(Products::where('name', $products[$i])->value('quantity')) + $quantity[$i],
                'date' => now()->format('Y-m-d H:i:s')
            ]);
        }
        $amount = Orders::where('order_token', _token)->sum('amount');
        Invoice::insert([
            "invoice_number" => $invoice_number,
            "token" => _token,
            "customer_id" => $request->customer_id,
            "customer" => $customer->name,
            "amount" => $amount,
            "created_at" => $date,
            "updated_at" => now()->format('Y-m-d')
        ]);
        return redirect()->route('order.edit', ['worker' => $customer->name, 'order_date' => $date])->with('success', 'Order Updated');
    }

    public function storeSupplier(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product.*' => 'required|exists:products,name',
        ]);
        $supplier = Suppliers::findOrFail($request->supplier_id);

        $products = $request->product;
        $price = $request->price;
        $quantity = $request->quantity;
        $invoice_number = $request->input('invoice-number');

        $date = $request->input('invoice-date');
        $days = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $day = now()->dayOfWeek;
        if (is_array($request->old_product)) {
            for ($i = 0; $i < count($request->old_product); $i++) {
                ProductStats::where(
                    'from',
                    $supplier->name,
                )->where(
                    'qty_received',
                    $request->old_qty[$i]
                )->where(
                    'before_qty',
                    Products::where('name', $request->old_product[$i])->value('quantity')
                )->delete();
                $product = Products::where('name', $request->old_product[$i])->first();
                if ($product && $product->quantity >= $request->old_qty[$i]) {
                    $product->decrement('quantity', $request->old_qty[$i]);
                } else {
                    return redirect()->route('order.supplier.edit', ['supplier' => $supplier->name, 'order_date' => $date])->with('error', 'products quantity insufficient');
                    // Handle error: product not found or quantity insufficient
                }
            }
        } else {
            $product = Products::where('name', $request->old_product)->first();
            ProductStats::where(
                'from',
                $supplier->name,
            )->where(
                'qty_received',
                $request->old_qty
            )->where(
                'before_qty',
                Products::where('name', $request->old_product)->value('quantity')
            )->delete();
            if ($product && $product->quantity >= $request->old_qty) {
                $product->decrement('quantity', $request->old_qty);
            } else {
                return redirect()->route('order.supplier.edit', ['supplier' => $supplier->name, 'order_date' => $date])->with('error', 'product quantity insufficient');
                // Handle error: product not found or quantity insufficient
            }
        }

        SupplierOrders::query()->where('token', $request->order_token)->delete();
        SupplierInvoice::query()->where('token', $request->order_token)->delete();
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
                'qty_received' => $quantity[$i],
                'product' => $products[$i],
                'product_id' => Products::where('name', $products[$i])->value('id'),
                'from' => $supplier->name,
                'before_qty' => $before_qty,
                'after_qty' => $before_qty + $quantity[$i],
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

        return redirect()->route('order.supplier.edit', ['supplier' => $supplier->name, 'order_date' => $date])->with('success', 'Order Updated');
    }

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

        $token = $data->first()->order_token;
        $id = $data->first()->customer_id;
        $customer = $data->first()->customer;
        $date = $data->first()->created_at;
        $invoice_number = Invoice::where('customer', $worker_name)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->first()->invoice_number;
        return view("pages.saved_order", ['data' => $data, 'invoice_number' => $invoice_number, 'order_token' => $token, 'customer_id' => $id, 'customer' => $customer, 'date' => $date]);
    }

    public function editSupplier(Request $request)
    {
        $supplier = $request->supplier;
        $order_date = $request->order_date;
        $data = SupplierOrders::where('supplier', $supplier)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        });
        $all = $data->get();
        // dd($data->count());
        if ($data->count() == 0) {
            return "Empty data " . "<a href='/dashboard'>Go to dashboard</a>";
        }
        $token = $data->first()->token;
        $id = $data->first()->supplier_id;
        $supplier = $data->first()->supplier;
        $date = $data->first()->created_at;
        $invoice_number = SupplierInvoice::where('supplier', $supplier)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->first()->invoice_number;
        return view("pages.saved_order_supplier", ['data' => $all, 'invoice_number' => $invoice_number, 'order_token' => $token, 'supplier_id' => $id, 'supplier' => $supplier, 'date' => $date]);
    }
    public function destroy(Request $request)
    {
        $ids = $request->id;
        $products = $request->product;
        $quantities = $request->quantity;
        // Orders::destroy($ids);
        $ids = array_unique($ids); // Remove duplicate IDs if any
        for ($i = 0; $i < count($ids); $i++) {
            try {
                $order = Orders::findOrFail($ids[$i]);
            } catch (ModelNotFoundException $e) {
                continue; // Skip if order not found
            }

            $qty = $order->quantity;
            $from = $order->customer;
            // $customer_id = $order->customer_id;

            try {
                $product = Products::where('name', $products[$i])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                continue; // Skip if product not found
            }

            $before_qty = $product->quantity;

            // Invoice::where('customer', $from)->where('customer_id', $customer_id)->delete();

            ProductStats::insert([
                'product' => $product->name,
                'product_id' => $product->id,
                'qty_received' => $qty,
                'from' => $from,
                'before_qty' => $before_qty,
                'after_qty' => $before_qty + $qty,
                'date' => now()->format('Y-m-d H:i:s')
            ]);

            $product->increment('quantity', $quantities[$i]);

            $order->delete();
        }

        return response()->json(['success' => 'Deleted']);
    }

    public function destroySupplier(Request $request)
    {
        // $request->dd();
        // $ids = $request->id;
        // $products = $request->product;
        // $quantities = $request->quantity;
        // for ($i = 0; $i < count($ids); $i++) {
        //     $product = SupplierOrders::where('id', $ids[$i])->value('product');
        //     $before_qty = Products::where('name', $product)->value('quantity');
        //     if (($quantities[$i] > $before_qty)) {
        //         return response()->json(['error' => 'Available product quantity is less than the supplied quantity, Please return the orders made for these products']);
        //         exit;
        //     }
        // }
        // for ($i = 0; $i < count($ids); $i++) {
        //     $qty = SupplierOrders::where('id', $ids[$i])->value('quantity');
        //     $supplier_id = SupplierOrders::where('id', $ids[$i])->value('supplier_id');
        //     $supplier = SupplierOrders::where('id', $ids[$i])->value('supplier');
        //     $product = SupplierOrders::where('id', $ids[$i])->value('product');
        //     $from = SupplierOrders::where('id', $ids[$i])->value('supplier');
        //     $before_qty = Products::where('name', $product)->value('quantity');
        //     ProductStats::insert([
        //         'product' => $product,
        //         'product_id' => Products::where('name', $product)->value('id'),
        //         'qty_received' => (0 - intval($qty)),
        //         'from' => $from,
        //         'before_qty' => $before_qty,
        //         'after_qty' => $before_qty - $qty,
        //         'date' => now()->format('Y-m-d H:i:s')
        //     ]);

        //     // SupplierInvoice::where('supplier_id', $supplier_id)->where('supplier', $supplier)->delete();
        //     Products::where('name', $products[$i])->decrement('quantity', $quantities[$i]);
        //     SupplierOrders::destroy($ids[$i]);
        // }
        $ids = $request->id;
        $products = $request->product;
        $quantities = $request->quantity;

        foreach ($ids as $index => $id) {
            $supplierOrder = SupplierOrders::find($id);
            if (!$supplierOrder) continue;
            $product = Products::where('name', $supplierOrder->product)->first();

            if ($quantities[$index] > $product->quantity) {
                return response()->json(['error' => 'Available product quantity is less than the supplied quantity, Please return the orders made for these products']);
            }
        }

        foreach ($ids as $index => $id) {
            $supplierOrder = SupplierOrders::find($id);
            if (!$supplierOrder) continue;
            $product = Products::where('name', $supplierOrder->product)->first();

            ProductStats::insert([
                'product' => $supplierOrder->product,
                'product_id' => $product->id,
                'qty_received' => (0 - intval($supplierOrder->quantity)),
                'from' => $supplierOrder->supplier,
                'before_qty' => $product->quantity,
                'after_qty' => $product->quantity - $supplierOrder->quantity,
                'date' => now()->format('Y-m-d H:i:s')
            ]);

            $product->decrement('quantity', $quantities[$index]);
            $supplierOrder->delete();
        }

        return response()->json(['success' => 'Deleted']);
    }
}
