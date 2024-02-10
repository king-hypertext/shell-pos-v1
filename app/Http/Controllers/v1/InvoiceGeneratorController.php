<?php

namespace App\Http\Controllers\v1;

use App\Models\Orders;
use App\Models\Invoice;
use App\Models\Customers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\SupplierInvoice;
use App\Models\SupplierOrders;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Response;

class InvoiceGeneratorController extends Controller
{
    public function invoice(string $token)
    {
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
    
    public function invoice_supplier(string $token)
    {
        $data = SupplierOrders::where('token', $token)->get();
        foreach ($data as $key => $value) {
            $date = $value->created_at;
            $id = $value->supplier_id;
        }
        $invoice_number = SupplierInvoice::where('token', $token)->value('invoice_number');
        $total = SupplierInvoice::where('token', $token)->value('amount');
        $supplier = Suppliers::find($id);
        $file = $supplier->name . '-' . $date->format('Y-M-d') . '-invoice' . '.pdf';
        // return view('templates.supplier_invoice', compact('data', 'invoice_number', 'total', 'supplier', 'date'));
        Pdf::loadView(
            'templates.supplier_invoice',
            compact('data', 'invoice_number', 'total', 'supplier', 'date')
        )->save('invoices/' . $file, 'public');
        return Response::make(file_get_contents(public_path('/storage/invoices/' . $file), true), 200, ['content-type' => 'application/pdf']);
    }

    public function returns(string $token)
    {
        return view('', compact(''));
        dd('' . $token);
    }

    public function open_stock()
    {
        
        $products = Products::all();
        $file = now()->format('Y-M-d') . '_open_stock' . '.pdf';
        $date = now()->format('Y M d D');
        // return view('templates.open_stock', compact('products', 'date'));
        Pdf::loadView('templates.open_stock', compact('products', 'date'))->save('open_stocks/' . $file, 'public')->stream($file);
        return Response::make(file_get_contents(public_path('storage/open_stocks/' . $file), true), 200, ['content-type' => 'application/pdf']);
    }
}
