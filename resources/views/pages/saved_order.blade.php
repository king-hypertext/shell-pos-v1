@extends('app.index')
@section('content')
    <div class="container fluid">
        <form autocomplete="off" id="form-invoice" action="{{ route('customer.store') }}" method="post">
            @csrf
            <hr class="hr text-dark" />

            <h6 class="h4">Edit Order</h6>
            <div class="table-responsive text-nowrap">
                <style>
                    #table-create-order> :not(caption)>*>* {
                        padding: 0;
                    }

                    table,
                    #table-create-order .form-control,
                    #table-create-order .form-select {
                        border-radius: 0 !important;
                        box-shadow: none;
                        border: 0 none;
                    }
                </style>
                <table id="table-create-order" class="table table-bordered mb-0">
                    <thead>
                        <tr class="p-3">
                            <th class="col-md-4 p-3" scope="col">Product Name</th>
                            <th class="col-md-3 p-3" scope="col" title="Price">Price (GHC)</th>
                            <th class="col-md-2 p-3" scope="col">Quantity</th>
                            <th class="col-md-3 p-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    @php
                        use Illuminate\Support\Facades\DB;
                        $products = DB::table('products')
                            ->where('quantity', '>', 0)
                            ->get(['name']);
                    @endphp
                    <tbody id="td-parent">
                        @foreach ($data as $order)
                            <tr class="form_row">
                                <td class="col-md-4">
                                    <div class="form-group">
                                        <select @required(true) class="form-select" data-select-product name="product[]"
                                            id="product">
                                            <option selected value="{{ $order->product }}">{{ $order->product }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->name }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        <input readonly type="text" name="price[]" type="number" step=".01"
                                            value="{{ $order->price }}" id="price" class="form-control" />
                                    </div>
                                </td>
                                <td class="col-md-2">
                                    <div class="form-group">
                                        <input type="number" name="quantity[]" value="{{ $order->quantity }}"
                                            onfocus="this.select()" required id="quantity" class="form-control qty" />
                                    </div>
                                </td>
                                <td class="col-md-3">
                                    <div class="form-group">
                                        <input readonly type="text" value="{{ $order->amount }}" name="total[]"
                                            id="total" class="form-control" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between mb-5">
                <div class="px-0">
                    <button type="button" onclick="addNewRow()" class="btn btn-warning add-row"><i class="bx bx-plus"></i>
                        Add Row
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" title="reset inputs">Reset</button>
                </div>
                <button type="submit" class="btn btn-primary text-capitalize" title="add invoice">Save</button>
            </div>
        </form>
    </div>
@endsection
@section('script')
    
@endsection