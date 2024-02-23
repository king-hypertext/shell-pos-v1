@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container fluid">
        <form autocomplete="off" id="form-invoice" action="{{ route('order.edit.save') }}" method="post">
            @csrf
            <input type="hidden" name="customer_id" value="{{ $customer_id }}" />
            <input type="hidden" name="invoice-date" value="{{ Carbon::parse($date)->format('Y-m-d') }}" />
            <input type="hidden" name="order_token" value="{{ $order_token }}" />
            <input type="hidden" name="invoice-number" value="{{ $invoice_number }}" />

            <hr class="hr text-dark" />

            <h6 class="h4">Edit Worker Order for {{ $customer }}</h6>
            <a href="#" role="button" class="link-delete d-none " onclick="confirmDelete(event)">Delete selected</a>
            <div class="table-responsive text-nowrap">
                <style type="text/css">
                    .link-delete {
                        text-decoration: none;
                    }

                    .link-delete:hover {
                        text-decoration: underline;
                    }

                    #table-create-order> :not(caption)>*>* {
                        padding: 0;
                    }

                    table,
                    #table-create-order .form-control,
                    #table-create-order .form-select,
                    #table-create-order .form-check-box {
                        border-radius: 0 !important;
                        box-shadow: none;
                        border: 0 none;
                    }
                </style>
                <table id="table-create-order" class="table table-bordered mb-0">
                    <thead>
                        <tr class="p-3">
                            <th class="col-md-1 py-3 px-0"></th>
                            <th class="col-md-4 p-3" scope="col">Product Name</th>
                            <th class="col-md-2 p-3" scope="col">Quantity</th>
                            <th class="col-md-2 p-3" scope="col" title="Price">Price (GHC)</th>
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
                                <td class="col-1 col-md-1">
                                    <div class="row justify-content-center align-items-center mb-2 pt-3">
                                        <input type="checkbox" name="check-box" style="cursor: pointer;"
                                            id="{{ $order->id }}" value="{{ $order->id }}"
                                            data-order_product="{{ $order->product }}"
                                            data-order_quantity="{{ $order->quantity }}" />
                                    </div>
                                </td>
                                <td class="col-md-4">
                                    <div class="form-group">
                                        <select @required(true) class="form-select" name="product[]" id="product">
                                            <option selected value="{{ $order->product }}">{{ $order->product }}</option>
                                            <input type="hidden" value="{{ $order->product }}" name="old_product[]">
                                    </div>
                                </td>
                                <td class="col-md-2">
                                    <div class="form-group">
                                        <input readonly type="number" name="quantity[]" value="{{ $order->quantity }}"
                                            onfocus="this.select()" required id="quantity" class="form-control qty" />
                                        <input type="hidden" value="{{ $order->quantity }}" name="old_qty[]">
                                    </div>
                                </td>
                                <td class="col-md-2">
                                    <div class="form-group">
                                        <input readonly type="text" name="price[]" onfocus="this.select()"
                                            type="number" step=".01" value="{{ $order->price }}" id="price"
                                            class="form-control" />
                                            <input type="hidden" name="old_price" value="{{ $order->price }}">
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
                <button type="submit" class="btn btn-primary text-capitalize" title="add invoice">Update</button>
            </div>
        </form>
    </div>
    @if (session('success'))
        <script>
            var showSuccessAlert = Swal.mixin({
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
@endsection
@section('script')
    <script>
        const showSuccessAlert = Swal.mixin({
            position: 'top-end',
            toast: true,
            timer: 6500,
            showConfirmButton: false,
            timerProgressBar: false,
        });
        var ids = [];
        var quantity = [];
        var product = [];
        $('input[name="check-box"]').on('change', function(e) {
            if (e.currentTarget.checked) {
                ids.push(e.currentTarget.value);
                quantity.push(e.currentTarget.dataset.order_quantity);
                product.push(e.currentTarget.dataset.order_product);
                console.log(ids, quantity, product);
            } else {
                ids.pop(e.currentTarget.value);
                quantity.pop(e.currentTarget.dataset.order_quantity);
                product.pop(e.currentTarget.dataset.order_product);
                console.log(ids, quantity, product);
            }
            if (ids.length != 0) {
                $('.link-delete').removeClass('d-none')
                console.log('not empty');
            } else {
                $('.link-delete').addClass('d-none')
                console.log('empty');
            }
        });
        window.confirmDelete = function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Delete Selected!",
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/orders/edit/delete',
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: ids,
                            product: product,
                            quantity: quantity
                        },
                        success: function(res) {
                            if (res.success) {
                                showSuccessAlert.fire({
                                    icon: 'success',
                                    text: res.success,
                                    padding: '10px',
                                    width: 'auto'
                                });
                                window.location.reload();
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                }
            })
        }
        $(document).on("click", "#removeRow", function(ev) {
            ev.preventDefault();
            var currentRow = $(this).parent().parent();
            $(currentRow).remove();
            row--;
        });
        // retrieve product info from database
        var row = 1;
        window.addNewRow = function() {
            var newInvoiceRow = `<tr class="form_row_${row}">
                    <td class="col-md-1">
                        <div class="form-group">
                        </div>
                    </td>
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
                            <input required onfocus="this.select()" type="number" name="quantity[]" id="quantity_${row}"
                                class="form-control qty" />
                        </div>
                    </td>                         
                    <td class="col-md-2">
                        <div class="form-group">
                            <input readonly required type="number" name="price[]" value="0.00" step=".01" id="price_${row}" class="form-control"/>
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
                $(document).on('change', 'select.select-product', function(e) {
                    var selectedValue = e.currentTarget.value,

                        total = e.currentTarget.parentElement.parentElement.parentElement
                        .parentElement.parentElement.children[4].children[0].children[0],

                        quantity = e.currentTarget.parentElement.parentElement.parentElement
                        .parentElement.parentElement.children[2].children[0].children[0],

                        price = e.currentTarget.parentElement.parentElement.parentElement
                        .parentElement.parentElement.children[3].children[0].children[0];
                    console.log(selectedValue);
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
                        total.value =
                            Number.parseFloat(price.value) * Number.parseFloat(quantity
                                .value);
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
            $("select[data-select-product]").select2();
        }

        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection
