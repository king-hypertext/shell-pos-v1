<?php

namespace App\Http\Controllers\v1;

use App\Models\Orders;
use App\Models\Products;
use App\Models\OrderReturns;
use App\Models\ProductStats;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CustomerOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = "CUSTOMER ORDERS";
        if ($request->ajax()) {
            $data = Orders::query()->where('quantity', '>=', 0)->latest();
            if ($request->filled('from_date') && $request->filled('to_date')) {
                $data = $data->whereBetween('created_at', [$request->from_date, $request->to_date])->latest();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('Action', function ($row) {
                        $rtn = "<button type='button' id='$row->returns' data-order_id='$row->id' class='btn btn-sm btn-danger btn-reset text-capitalize ms-1'>Reset</button>";

                        $div = "<form id='form-return' method='post'>
                        <div class='form-group d-flex mb-1'>
                            <input style='min-width: 45px;' required type='number' min='0' id='$row->quantity' class='return_input form-control form-control-sm me-1' placeholder='quantity' />
                            <button type='submit' class='btn btn-sm btn-primary btn-return text-capitalize ms-1' data-order_id='$row->id'>Return</button>
                        </div>
                    </form>";
                        $div1 = "<form id='form-return' method='post'>
                        <div class='form-group d-flex mb-1'>
                            <input style='min-width: 45px;' required type='number' min='0' data-order_id='$row->id' id='$row->quantity' class='return_input form-control form-control-sm me-1' placeholder='quantity' />
                        </div>
                        <div class='form-group d-flex justify-content-start'>
                            $rtn
                            <button type='submit' class='btn btn-sm btn-primary btn-return text-capitalize ms-1' data-order_id='$row->id'>Return</button>
                        </div>
                    </form>";
                        if ($row->returns !== 0) {
                            return  $div1;
                        } else {
                            return $div;
                        }
                    })
                    ->rawColumns(['Action'])
                    ->make(true);
            }
        }
        return view('pages.order_customers', compact('title'));
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
        //
    }

    /**
     * This method is been used to show the returns page.
     */
    public function show()
    {
        $title = "RETURNED ORDERS";
        $data = OrderReturns::all();
        return view('pages.return_orders', compact('title', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * This method is been used to return an order
     */
    public function update(int $order_id, Request $request, Orders $orders)
    {
        $order = $orders->find($order_id);
        $quantity_to_return = $request->quantity_to_return;
        $before_qty = Products::where('name', $order->product)->value('quantity');
        ProductStats::insert([
            'qty_received' => $quantity_to_return,
            'product' => $order->product,
            'product_id' => Products::where('name', $order->product)->value('id'),
            'from' => $order->customer,
            'before_qty' => $before_qty,
            'after_qty' => $before_qty + $quantity_to_return,
            'date' => now()->format('Y-m-d H:i')
        ]);
        Orders::where('id', $order_id)->update([
            'quantity' => (floatval($order->quantity) - floatval($quantity_to_return)),
            'amount' => (floatval($order->price) * (floatval($order->quantity) - floatval($quantity_to_return))),
            'returns' => $quantity_to_return,
            'updated_at' => now()->format('Y-m-d H:i:s')
        ]);
        OrderReturns::insert([
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'customer' => $order->customer,
            'product' => $order->product,
            'quantity' => $quantity_to_return,
            'price' => $order->price,
            'amount' => (floatval($order->price) * floatval($quantity_to_return)),
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        Products::where('name', $order->product)->increment('quantity', $quantity_to_return);
        return response()->json(['success' => 'Return Success']);
    }

    /**
     * This method is been used to reset orders
     */
    public function destroy(int $order_id, Request $request, Orders $orders)
    {
        $order = $orders->find($order_id);
        $quantity_returned = $request->quantity_returned;
        Orders::where('id', $order_id)->update([
            'quantity' => (floatval($order->quantity) + floatval($quantity_returned)),
            'amount' => (floatval($order->price) * (floatval($order->quantity) + floatval($quantity_returned))),
            'returns' => 0,
            'updated_at' => now()->format('Y-m-d')
        ]);
        OrderReturns::where('order_id', $order->id)->delete();
        Products::where('name', $order->product)->decrement('quantity', $quantity_returned);
        return response()->json(['success' => 'Order Reset Success']);
    }
}
