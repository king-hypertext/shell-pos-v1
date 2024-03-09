<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Invoice;
use App\Models\Orders;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "ALL WORKERS";
        $customers = Customers::all();
        // return $customers;
        return view('pages.customers', compact('title', 'customers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $request->dd();
        $request->validate([
            'customer-name' => 'required|string',
            'contact' => 'required|numeric',
        ]);

        if ($request->hasFile("customer-image")) {
            $request->validate([
                'customer-image' => 'required|file|mimes:png,jpg,jpeg,webp',
            ]);
            $path = $request->file("customer-image")->store('/public/customers');
        }
        Customers::insert([
            "name" => $request->input("customer-name"),
            "gender" => $request->input("gender"),
            "date_of_birth" => $request->input("dob"),
            "address" => $request->input("address"),
            "contact" => $request->input("contact"),
            "image" => '/storage/customers/' . str_replace(['public/', 'customers/'], '', $path),
            "created_at" => now()->format('Y-m-d')
        ]);
        return response()->json(['success' => 'Worker added Successfully']);
        // return back()->with("success", "New Worker Added");
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Customers $customers)
    {
        $customer = $customers->find($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customers $customers)
    {
        // $request->dd();
        $customer = $customers->find($request->id);
        if ($request->hasFile("customer-image")) {
            $request->validate([
                "customer-image" => "required|file|mimes:png,jpg,jpeg,webp",
            ]);
            if (file_exists(public_path("$customer->image"))) {
                unlink(public_path("$customer->image"));
            }
            $path = $request->file("customer-image")->store('/public/customers');
            Customers::where('id', $request->id)->update([
                'name' => $request->input('customer-name'),
                'date_of_birth' => $request->input('dob'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'image' => '/storage/customers/' . str_replace(['public/', 'customers/'], '', $path),
                'updated_at' => now()->format('Y-m-d')
            ]);
            Invoice::where('customer_id', $request->id)->update([
                'customer' => $request->input('customer-name'),
            ]);
            Orders::where('customer_id', $request->id)->update([
                'customer' => $request->input('customer-name'),
            ]);
        } else {
            Customers::where('id', $request->id)->update([
                'name' => $request->input('customer-name'),
                'date_of_birth' => $request->input('dob'),
                'address' => $request->input('address'),
                'contact' => $request->input('contact'),
                'updated_at' => now()->format('Y-m-d')
            ]);
            Invoice::where('customer_id', $request->id)->update([
                'customer' => $request->input('customer-name'),
            ]);
            Orders::where('customer_id', $request->id)->update([
                'customer' => $request->input('customer-name'),
            ]);
        }
        return back()->with('success', 'Worker Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customers $customers, Request $request, $id)
    {
        $customer = $customers->find($id);
        if (file_exists(public_path("$customer->image"))) {
            unlink(public_path("$customer->image"));
        }
        Customers::destroy($customer->id);
        return back()->with('success', 'Worker Deleted');
    }
}
