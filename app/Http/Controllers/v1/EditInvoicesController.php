<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

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
            // dd($worker_name, $order_date);
            return count($data) > 0 ? response()->json(['data' => route('order.edit', [$worker_name, $order_date])]) : response()->json(['empty' => 'No Data Found']);
        } //  count($data) > 1 ? response()->json(['data' => $data]) : response()->json(['data' => $data]);
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
        $worker_name = $request->name;
        $order_date = $request->date;
        $data = Orders::where('customer', $worker_name)->where(function ($date) use ($order_date) {
            $date->where('created_at', $order_date);
        })->get();

        return view("pages.saved_order", ['data' => $data]);
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
