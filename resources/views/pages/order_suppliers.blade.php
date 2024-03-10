@extends('app.index')
@section('content')
    <div class="container mt-4">
        <h2 class="h2 text-uppercase">supplier Orders</h2>
        <div class="d-flex justify-content-end me-1 mb-2">
            <div class="mt-1">
                Select date range:
            </div>
            <div class="mx-2">
                <input type="text" value="{{ Date('Y-m-d') }} - {{ Date('Y-m-d') }}" id="order-daterange"
                    name="order-daterange" class="form-control">
            </div>
            <div class="mx-2">
                <button id="filter" class="btn btn-success text-capitalize">Filter</button>
            </div>
            <div class="mx-2">
                <button id="reset" class="btn btn-warning text-capitalize">Reset</button>
            </div>
        </div>
        <style>
            table,
            tr,
            td {
                text-transform: uppercase !important;
            }
        </style>
        <div class="table-responsive">
            <table class="table table-hover" id="supplier_orders" class="display">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">WORKER</th>
                        <th scope="col">PRODUCT</th>
                        <th title="Qauntity Added" scope="col">QTY ADDED</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">AMOUNT</th>
                        <th scope="col">DAY</th>
                        <th scope="col">DATE</th>
                    </tr>
                </thead>
                <tbody class="text-uppercase">
                </tbody>
                <tfoot>
                    <tr class="text-uppercase">
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th style="text-align: right">Total:</th>
                        <th id="total_order"></th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.confirmDelete = function(e) {
            e.preventDefault();
            var link = e.target;
            console.log(link, link.id);
            Swal.fire({
                title: "Confirm Delete!",
                text: "Are you sure you want to delete?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/order/delete/" + link.id;
                }
            })
        }
        const showSuccessAlert = Swal.mixin({
            position: 'top-end',
            toast: true,
            timer: 6500,
            showConfirmButton: false,
            timerProgressBar: false,
        });
        $(document).ready(function() {
            $('input[name="order-daterange" ]').daterangepicker({
                startDate: moment().subtract(1, 'M'),
                endDate: moment()
            });

            function addCell(tr, content, colSpan = 1) {
                let td = document.createElement('th');
                td.colSpan = colSpan;
                td.textContent = content;
                tr.appendChild(td);
            }
            var table = new DataTable('#supplier_orders', {
                order: [
                    [3, 'asc']
                ],
                rowGroup: {
                    startRender: null,
                    endRender: function(rows, group) {
                        let totalAmount =
                            rows
                            .data()
                            .pluck('amount').map((str) => parseFloat(str)).reduce((a, b) => a + b, 0);

                        totalAmount = $.fn.dataTable.render
                            .number(',', '.', 2, 'GHS ')
                            .display(totalAmount);

                        let totalQuantity =
                            rows
                            .data()
                            .pluck('quantity')
                            .reduce(function(a, b) {
                                return a + b
                            }, 0);

                        let tr = document.createElement('tr');

                        addCell(tr, 'Sum for ' + group, 3);
                        addCell(tr, totalQuantity.toFixed(0));
                        addCell(tr, '');
                        addCell(tr, totalAmount);

                        addCell(tr, '');
                        addCell(tr, '');
                        // addCell(tr, '');
                        // addCell(tr, '');


                        return tr;
                    },
                    dataSrc: 'product'
                },
                drawCallback: function() {
                    var api = this.api(),
                        last = null;
                    var rows = api.rows({
                        page: 'current'
                    }).nodes();
                    api.column(2, {
                        page: 'current'
                    }).data().each(function(group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr><th style="background-color: #d1d1d1;" colspan="8">' +
                                group + '</th></tr>');
                            last = group;
                        }
                    })
                },
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api(),
                        data;
                    var intVal = function(i) {
                        if (typeof i === 'string') {
                            return i.replace(/[\$,]/g, '') * 1.00;
                        } else if (typeof i === 'number') {
                            return i;
                        }
                    };
                    var total = api.column(5).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0.00);
                    var formatter = new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'GHS',
                    });
                    var pageTotal = api.column(5, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0.00);
                    $(api.column(5).footer()).html(formatter.format(pageTotal));
                },
                scrollY: false,
                processing: true,
                serverSide: true,
                pageLength: 100,
                ajax: {
                    url: "{{ route('orders.suppliers') }}",
                    data: function(data) {
                        data.from_date = $('input[name="order-daterange" ]')
                            .data('daterangepicker')
                            .startDate.format('YYYY-MM-DD');
                        data.to_date = $('input[name="order-daterange" ]')
                            .data('daterangepicker')
                            .endDate.format('YYYY-MM-DD');
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'price',
                        name: 'price'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'day',
                        name: 'day'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(val) {
                            return moment(val).format('Do MMMM, YYYY')
                        }
                    },

                ],
                search: {
                    return: true,
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export Excel <i class="fas fa-file-excel"></i>',
                        className: 'btn btn-success text-capitalize',
                        footer: true,
                        title: 'SUPPLIER_ORDERS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Save <i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger text-capitalize',
                        footer: true,
                        title: 'SUPPLIER_ORDERS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print <i class="fas fa-print"></i>',
                        className: 'btn btn-primary text-capitalize',
                        footer: true,
                        title: 'SUPPLIER_ORDERS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                ]
            });
            $('button#filter').click(function() {
                table.draw();
            });
            $('button#reset').click(function() {
                location.reload();
            });
            $('input[aria-controls="supplier_orders"]')[0].placeholder = "search table...";
            $('input[aria-controls="supplier_orders"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
