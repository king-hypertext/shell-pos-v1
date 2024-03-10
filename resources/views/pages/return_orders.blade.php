@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid mt-4">
        <h2 class="h2 text-uppercase">All Return Orders</h2>
        <div class="table-responsive">
            <table class="table table-hover" id="return-table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">WORKER</th>
                        <th scope="col">PRODUCT</th>
                        <th scope="col">PRICE(GHS)</th>
                        <th scope="col">QTY RETURN</th>
                        <th scope="col">AMOUNT(GHS)</th>
                        <th scope="col">RETURN DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $return)
                        <tr>
                            <td scope="row">{{ $index + 1 }}</td>
                            <td>{{ $return->customer }}</td>
                            <td>{{ $return->product }}</td>
                            <td>{{ $return->price }}</td>
                            <td>{{ $return->quantity }}</td>
                            <td>{{ $return->amount }}</td>
                            <td>{{ Carbon::parse($return->created_at)->format('Y-M-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var table = new DataTable('#return-table', {
                scrollY: false,
                processing: true,
                pageLength: 100,
                search: {
                    return: true,
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export Excel <i class="fas fa-file-excel"></i>',
                        className: 'btn btn-success text-capitalize',
                        footer: true,
                        title: 'RETURNS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Save <i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger text-capitalize',
                        footer: true,
                        title: 'RETURNS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print <i class="fas fa-print"></i>',
                        className: 'btn btn-primary text-capitalize',
                        footer: true,
                        title: 'RETURNS ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                ]
            });

            document.querySelector('input[aria-controls="return-table"]').placeholder = "Search table";
            $('input[aria-controls="return-table"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
