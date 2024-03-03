@extends('app.index')
@section('content')
    <h3 class="fw-bold text-uppercase mt-2 ">create supplier order</h3>
    <hr class="hr text-dark" />
    <style>
        input[readonly] {
            background-color: #fff !important;
            cursor: not-allowed;
        }
    </style>
    <div class="container-fluid ">
        <div class="container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="list-unstyled d-flex justify-content-center">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @php
            // dd(request());
            use App\Models\Suppliers;
            use App\Models\Products;
            $suppliers = Suppliers::all();
            $products = Products::where('supplied_by', $supplier->name)->get('name');
        @endphp
        <form autocomplete="off" id="form-invoice" action="{{ route('supplier.store') }}" method="post">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <label for="supplier" class="d-block">Supplier: </label>
                        <select required name="supplier" id="supplier" class="form-select">
                            <option value="{{ $supplier->id }}" selected>{{ $supplier->name }}</option>
                            @foreach ($suppliers as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="category">Category:</label>
                        <input type="text" class="form-control" value="{{ $supplier->category }}" id="category"
                            name="category" readonly placeholder="Category">
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" value="{{ $supplier->address }}" id="address"
                            name="address" readonly placeholder="Address">
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="phone">Phone Number:</label>
                        <input type="text" class="form-control" value="{{ $supplier->contact }}" id="phone"
                            name="phone" readonly placeholder="Phone Number">
                    </div>
                </div>
            </div>
            @csrf
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-md-3" style="">
                        <label for="supplier-invoice">Invoice Number: </label>
                        <input required type="number" placeholder="Invoice Number" class="form-control"
                            id="supplier-invoice" name="supplier-invoice" />
                    </div>
                    <div class="col-md-3">
                        <label for="invoice-date">Invoice Date: </label>
                        <input required type="date" value="{{ Date('Y-m-d') }}" class="form-control datepicker"
                            id="invoice-date" name="invoice-date" />
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success mt-4 d-inline-block text-lowercase " type="button"
                            data-bs-toggle="modal" data-bs-target="#saved-invoice">Saved
                            Invoices
                        </button>
                    </div>
                    <div class="col-md-3"></div>
                </div>
                <div class="mt-2">
                    <h5 class="fw-bold text-left text-uppercase mt-2 selected_worker"></h5>
                </div>
            </div>
            <hr class="hr text-dark" />
            <h6 class="h4">Create Invoice</h6>
            <div class="table-responsive text-nowrap">
                <style>
                    #table-create-order> :not(caption)>*>* {
                        padding: 0;
                    }

                    select,
                    #table-create-order .form-control,
                    #table-create-order .form-select {
                        border-radius: 0 !important;
                        box-shadow: none;
                        border: 0 none;
                    }

                    select,
                    option,
                    input {
                        text-transform: uppercase !important;
                    }
                </style>
                <table id="table-create-order" class="table table-bordered mb-0">
                    <thead>
                        <tr class="p-3">
                            <th class="col-md-4 p-3" scope="col">Product Name</th>
                            <th class="col-md-2 p-3" scope="col">Quantity</th>
                            <th class="col-md-3 p-3" scope="col" title="Price">Price (GHC)</th>
                            <th class="col-md-3 p-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    <tbody id="td-parent" class="text-upppercase">
                        <tr class="form_row text-uppercase ">
                            <td class="col-md-4">
                                <div class="form-group">
                                    <select @required(true) class="form-select" data-select-product name="product[]"
                                        id="product">
                                        <option value=""> Select Product </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input required type="number" name="quantity[]" onfocus="this.select()"
                                        id="quantity" class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="price[]" type="number" onfocus="this.select()"
                                        step=".01" value="0.00" id="price" class="form-control" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" value="0" name="total[]" id="total"
                                        class="form-control" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer justify-content-between mb-5">
                <div class="px-0">
                    <button type="button" onclick="addNewRow()" class="btn btn-warning add-row"><i
                            class="bx bx-plus"></i>
                        Add Row
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" title="reset inputs">Reset</button>
                </div>
                <button type="submit" class="btn btn-primary text-capitalize" title="add invoice">Save</button>
            </div>
        </form>
    </div>
    @if (session('success'))
        <script>
            const showSuccessAlert = Swal.mixin({
                position: 'top-end',
                toast: true,
                timer: 6500,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showSuccessAlert.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                padding: '10px',
                width: 'auto'
            });
        </script>
    @endif
    @include('modals.saved_invoice_supplier')
@endsection
@section('script')
    <script>
        var row = 1;
        $(document).ready(function() {
            var url = window.location.href, // or any URL
                lastPart = url.substring(url.lastIndexOf('/') + 1);
            var select = $('select[name="supplier"]');
            select.select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            $('select>option').text(function(i, oldText) {
                return oldText.toUpperCase();
            });
            $('select>option').each(function() {
                $(this).val($(this).val().toUpperCase());
            });
            $(document).on('change', '[data-select-product]', function(e) {
                var selectedValue = e.target.value,

                    price = e.target.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[2].children[0]
                    .children[0],

                    total = e.target.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[3].children[0]
                    .children[0],

                    quantity = e.target.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[1].children[0]
                    .children[0];
                $.ajax({
                    method: "GET",
                    url: "/product/" + selectedValue,
                    success: function(res) {
                        // console.log(res);
                        price.value = res.data[0].price;
                    }
                });
                $(document).on('keyup', quantity, function(e) {
                    total.value = Number.parseFloat(price.value) * Number.parseFloat(quantity.value)
                        .toFixed(2);
                    if (isNaN(total.value)) {
                        total.value = 0;
                    }
                });
            });
            var path = location.pathname.replace(/\/[^\/]*$/, '/');
            // retrieve selected supplier from database
            select.on('change', (e) => {
                id = e.currentTarget.value;
                window.location.href = path + id;
            });
            window.addNewRow = function() {
                var newInvoiceRow = `<tr class="form_row_${row}">
                    <td class="col-md-4">
                        <div class="">
                            <div class="">
                                <div class="form-group">
                                    <select @required(true) style="padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;" required name="product[]" id="product_${row}"
                                        class="form-select select-product">
                                        <option selected value=""> Select Product </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">{{ $product->name }}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </td>                           
                    <td class="col-md-2">
                        <div class="form-group">
                            <input required type="number" name="quantity[]" onfocus="this.select()" id="quantity_${row}"
                                class="form-control qty" />
                        </div>
                    </td>                   
                    <td class="col-md-3">
                        <div class="form-group">
                            <input required type="number" onfocus="this.select()" name="price[]" value="0.00" step=".01" id="price_${row}" class="form-control"/>
                        </div>
                    </td>  
                    <td class="col-md-3">
                        <div class="form-group">
                            <input readonly type="text" value="0" name="total[]" id="total_${row}"
                                class="form-control total" />
                        </div>
                    </td>
                    <td>
                        <button style="font-weight: 900;font-size: 20px; margin: 0;" type="button" class="btn btn-sm btn-danger" id="removeRow" title="Click to remove row"        >
                            <span style="padding-top: 8px">&times;</span>
                        </button>
                    </td>
                </tr>`;
                if (row > 0) {
                    $("tbody#td-parent").append(newInvoiceRow);
                    row++;
                    $('select>option').text(function(i, oldText) {
                        return oldText.toUpperCase();
                    });
                    $('select>option').each(function() {
                        $(this).val($(this).val().toUpperCase());
                    });
                    $(document).on('change', '.select-product', function(e) {
                        var selectedValue = e.target.value,

                            price = e.target.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[2].children[0].children[0],

                            total = e.target.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[3].children[0].children[0],

                            quantity = e.target.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[1].children[0].children[0];
                        $.ajax({
                            method: "GET",
                            url: "/product/" + selectedValue,
                            success: function(res) {
                                // console.log(res);
                                price.value = res.data[0].price;
                            }
                        });
                        $(document).on('keyup', quantity, function(e) {
                            total.value = Number.parseFloat(price.value) *
                                Number.parseFloat(quantity.value).toFixed(2);
                            if (isNaN(total.value)) {
                                total.value = 0;
                            }
                        });
                    });
                    $(function() {
                        $("select.select-product").select2();
                        $(document).on('select2:open', () => {
                            document.querySelector('.select2-search__field').focus();
                        });
                    })
                }
            }
            // retrieve products info from database

            $(document).on("click", "#removeRow", function(ev) {
                ev.preventDefault();
                var currentRow = $(this).parent().parent();
                $(currentRow).remove();
                row--;
            });
            // retrieve product info from database
            $("select[data-select-product]").select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
    </script>
@endsection
