@extends('app.index')
@section('content')
    <h3 class="fw-bold text-uppercase mt-2 ">create worker order</h3>
    <hr class="hr text-dark" />
    @php
        use Illuminate\Support\Facades\DB;
        $products = DB::table('products')
            ->where('quantity', '>', 0)
            ->get(['name']);
        $empty_q = DB::table('products')->where('quantity', '<', 1)->count('name');
        $empty_p = DB::table('products')->get(['*']);
    @endphp
    <style>
        input[readonly] {
            background-color: #fff !important;
            cursor: not-allowed;
            text-transform: uppercase !important;
        }

        select option {
            text-transform: uppercase !important;
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

        <form autocomplete="off" id="form-invoice" action="{{ route('customer.store') }}" method="post">
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <label for="customer" class="d-block">Worker: </label>
                        <select required name="customer" id="customer" class="form-select">
                            <option value="" selected>Select WORKER</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="gender">Gender:</label>
                        <input type="text" class="form-control" id="gender" name="gender" readonly
                            placeholder="Gender">
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="address">Address:</label>
                        <input type="text" class="form-control" id="address" name="address" readonly
                            placeholder="Address">
                    </div>
                    <div class="col-md-3 col-sm-6" style="">
                        <label for="phone">Phone Number:</label>
                        <input type="text" class="form-control" id="phone" name="phone" readonly
                            placeholder="Phone Number">
                    </div>
                </div>
            </div>
            @csrf
            <div class="container-fluid ">
                <div class="row">
                    <div class="col-md-3">
                        <label for="invoice-date">Invoice Date: </label>
                        <input required type="date" value="{{ Date('Y-m-d') }}" class="form-control" id="invoice-date"
                            name="invoice-date" />
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-center">
                            <img src="" alt="customer image" id="customer-img" width="80" height="80" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success mt-4 text-truncate " type="button" data-bs-toggle="modal"
                            data-bs-target="#saved-invoice" title="Saved Orders">Saved
                            Invoices
                        </button>
                    </div>
                </div>
                <div class="my-2">
                    <h5 class="fw-bold text-left text-uppercase mt-2 selected_worker"></h5>
                </div>
            </div>
            <hr class="hr text-dark" />
            @if ($empty_q !== 0)
                <div class="alert alert-danger mt-2 text-center">
                    Some Products are out of stock, please top up now to see
                    them in the list
                </div>
            @endif
            @if (count($empty_p) == 0)
                <div class="alert alert-danger mt-2 text-center">There are no products available, please <a
                        href="{{ route('products.create') }}" class="btn btn-link text-lowercase">Add Products</a> </div>
            @endif
            <h6 class="h4">Create Order</h6>
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
                            <th class="col-md-2 p-3" scope="col">Quantity</th>
                            <th class="col-md-3 p-3" scope="col" title="Price">Price (GHC)</th>
                            <th class="col-md-3 p-3" scope="col">Total (GHC)</th>
                        </tr>
                    </thead>
                    <tbody id="td-parent">
                        <tr class="form_row">
                            <td class="col-md-4">
                                <div class="form-group">
                                    <select @required(true) class="form-select" data-select-product name="product[]"
                                        id="product">
                                        <option selected value=""> Select Product </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-md-2">
                                <div class="form-group">
                                    <input type="number" name="quantity[]" onfocus="this.select()" required
                                        id="quantity" class="form-control qty" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="text" name="price[]" type="number" step=".01"
                                        value="0.00" id="price" class="form-control" />
                                </div>
                            </td>
                            <td class="col-md-3">
                                <div class="form-group">
                                    <input readonly type="number" step=".01" value="0" name="total[]"
                                        id="total" class="form-control" />
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
    @include('modals.saved_invoice')
@endsection
@section('script')
    <script>
        var row = 1;
        $(document).ready(function() {

            var select = $('select[name="customer"]')
            select.select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            // retrieve selected customer from database
            $('img#customer-img').hide();
            select.on('change', (e) => {
                var gender = $('input[name="gender"]'),
                    address = $('input[name="address"]'),
                    phone = $('input[name="phone"]'),
                    id = $('input[name="customer-id"]'),
                    image = $('img#customer-img')[0];
                $.ajax({
                    url: "/create-order/customer/" + e.target.value,
                    success: function(res) {
                        $('img#customer-img').show();
                        console.log(res.data);
                        gender.val(res.data.gender).addClass('active');
                        address.val(res.data.address).addClass('active');
                        phone.val(res.data.contact).addClass('active');
                        id.val(res.data.id);
                        image.src = res.data.image;
                        $('.selected_worker').text(res.data.name);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            });
            window.addNewRow = function() {
                var newInvoiceRow = `<tr class="form_row_${row}">
                    <td class="col-md-4">
                        <div class="">
                            <div class="">
                                <div class="form-group">
                                    <select @required(true) style="padding: 0.375rem 2.25rem 0.375rem 0.75rem !important;" required name="product[]" id="product_${row}"
                                        class="form-select select-product">
                                        <option value="" selected disabled> Select Product </option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->name }}">
                                                {{ $product->name }} </option>
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
                            <input required type="number" readonly name="price[]" value="0.00" step=".01" id="price_${row}" class="form-control"/>
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
                    $(document).on('change', 'select.select-product', function(e) {
                        var selectedValue = e.currentTarget.value,

                            price = e.currentTarget.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[2].children[0].children[0],

                            total = e.currentTarget.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[3].children[0].children[0],

                            quantity = e.currentTarget.parentElement.parentElement.parentElement
                            .parentElement.parentElement.children[1].children[0].children[0];
                        $.ajax({
                            method: "GET",
                            url: "/product/" + selectedValue,
                            success: function(res) {
                                console.log(res);
                                price.value = res.data[0].price;
                                quantity.max = res.data[0].quantity;
                            }
                        });
                        $(document).on('keyup', quantity, function(e) {
                            total.value = (Number.parseFloat(price.value) * Number
                                .parseFloat(quantity.value)).toFixed(2);
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
            $(document).on('change', 'select[data-select-product]', function(e) {

                var selectedValue = e.currentTarget.value,

                    price = e.currentTarget.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[2].children[0]
                    .children[0],

                    total = e.currentTarget.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[3].children[0]
                    .children[0],

                    quantity = e.currentTarget.parentElement.parentElement.parentElement
                    .parentElement.parentElement.children[1].children[0].children[1].children[0]
                    .children[0];
                console.log(selectedValue);
                $.ajax({
                    method: "GET",
                    url: "/product/" + selectedValue,
                    success: function(res) {
                        console.log(res);
                        price.value = res.data[0].price;
                        quantity.max = res.data[0].quantity;
                    },
                });
                $(document).on('keyup', quantity, function(e) {
                    total.value =
                        (Number.parseFloat(price.value) * Number.parseFloat(quantity
                            .value)).toFixed(2);
                    if (isNaN(total.value)) {
                        total.value = 0;
                    }
                })
            });
            $(document).on("click", "#removeRow", function(ev) {
                ev.preventDefault();
                var currentRow = $(this).parent().parent();
                $(currentRow).remove();
                row--;
            });
            $('select>option').text(function(i, oldText) {
                return oldText.toUpperCase();
            });
            $('select>option').each(function() {
                $(this).val($(this).val().toUpperCase());
            });
            $("select[data-select-product]").select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
        });
    </script>
@endsection
