@extends('app.index')
@section('content')
    @php
        use App\Models\Products;
        use Carbon\Carbon;
        $products = Products::get(['name', 'id']);
    @endphp
    <div class="row d-flex justify-content-between">
        <div class="col-md-8">
            <h4 class="h4 fw-semibold text-capitalize ">
                product ({{ $product->name }}) stats
            </h4>
            <ul class="list-inline">
                <li class="list-inline-item"><span class="h6">Price: </span>{{ $product->price }}</li>
                <li class="list-inline-item"><span class="h6">Quantity: </span>{{ $product->quantity }}</li>
                <li class="list-inline-item"><span class="h6">Supplier: </span>{{ $product->supplied_by }}</li>
                <li class="list-inline-item"><span class="h6">Category: </span>{{ $product->category }}</li>
            </ul>
        </div>
        <div class="col-md-4">
            <div class="mt-3">
                <div class="form-group">
                    <label for="products">Change Product</label>
                    <select name="product" id="product" class="form-select">
                        {{-- <option value="" selected>Select Product</option> --}}
                        @foreach ($products as $product)
                            <option {{ request()->product->id === $product->id ? 'selected' : '' }}
                                value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover " id="stats">
            <thead>
                <tr>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase">#</th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase">date</th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase"
                        title="Recieved (how much were received)">received qty</th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase">from</th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase"
                        title="(How much were given out)">qty supply
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase"
                        title="(who was it given out to?)">to
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase" title="Before Quanity">
                        before qty
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase" title="After Quantity">
                        after qty
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none text-uppercase" title="After Quantity">
                        aval. qty
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $key => $stat)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ Carbon::parse($stat->date)->format('Y-M-d H:i') }}</td>
                        <td>{{ $stat->qty_received }}</td>
                        <td>{{ $stat->from }}</td>
                        <td>{{ $stat->supplied }}</td>
                        <td>{{ $stat->to }}</td>
                        <td>{{ $stat->before_qty }}</td>
                        <td>{{ $stat->after_qty }}</td>
                        <td>{{ $stat->after_qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var select = $('select#product')
            select.select2();
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            var path = location.pathname.replace(/\/[^\/]*$/, '/');
            $(document).on('change', '[name="product"]', function(e) {
                var selectedValue = e.currentTarget.value;
                $.ajax({
                    method: "GET",
                    url: "/products/" + selectedValue,
                    success: function(res) {
                        window.location.href = path + res.id;
                    },
                });
            });
            var table = new DataTable('#stats', {
                processing: true,
                search: {
                    return: true,
                },
                order: [
                    [0, 'asc']
                ],
                pageLength: 100,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export Excel <i class="fas fa-file-excel" ></i>',
                        className: 'btn btn-success text-capitalize',
                        title: '{{ $product->name }} analysis',
                        filename: '{{ $product->name }}_analysis',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        text: 'Save <i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger text-capitalize',
                        title: '{{ $product->name }} analysis',
                        filename: '{{ $product->name }}_analysis',
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 0]
                        }
                    },
                    {
                        text: 'Print <i class="fas fa-print"></i>',
                        className: 'btn btn-primary text-capitalize',
                        title: '{{ $product->name }} analysis',
                        filename: '{{ $product->name }}_analysis',
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 0]
                        }
                    },
                ]
            });
            $('input[aria-controls="stats"]')[0].placeholder = 'Search table';
            $('input[aria-controls="stats"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
