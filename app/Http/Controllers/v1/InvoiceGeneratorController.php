<?php

namespace App\Http\Controllers\v1;

use App\Models\Orders;
use App\Models\Invoice;
use App\Models\Customers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Support\Facades\Response;

class InvoiceGeneratorController extends Controller
{
    /**
     * This method is been used to generate the pdf for open stock.
     */
    public function index(Request $request)
    {
        $products = Products::all();
        $file = now()->format('Y-M-d') . '_open_stock' . '.pdf';
        $date = now()->format('Y M d D');
        // return view('templates.open_stock', compact('products', 'date'));
        Pdf::loadView('templates.open_stock', compact('products', 'date'))->save('open_stocks/' . $file, 'public')->stream($file);
        return Response::make(file_get_contents(public_path('storage/open_stocks/' . $file), true), 200, ['content-type' => 'application/pdf']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
    public function show(string $token)
    {
        // dd($token);
        if (empty($token)) {
            return back();
        }
        $data = Orders::where('order_token', $token)->get();
        foreach ($data as $key => $value) {
            $date = $value->created_at;
            $id = $value->customer_id;
        }
        $invoice_number = Invoice::where('token', $token)->first();

        $total = Orders::where('order_token', $token)->sum('amount');
        $customer = Customers::find($id);
        $file = $customer->name . '-' . $date->format('Y-M-d') . '-invoice' . '.pdf';
        // return view('templates.customer_invoice', compact('data', 'total', 'date', 'invoice_number', 'customer'));

        Pdf::loadView(
            'templates.customer_invoice',
            compact('data', 'total', 'date', 'invoice_number', 'customer')
        )->save('invoices/' . $file, 'public');
        // return 'sucess';
        return Response::make(file_get_contents(public_path('/storage/invoices/' . $file), true), 200, ['content-type' => 'application/pdf']);
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
