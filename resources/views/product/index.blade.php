@extends('app.index')
@section('content')
    <h4 class="h4 fw-semibold text-capitalize ">
        product ({{ $data->name }}) stats
    </h4>
    <ul class="list-inline">
        <li class="list-inline-item"><span class="h6">Price: </span>{{ $data->price }}</li>
        <li class="list-inline-item"><span class="h6">Quantity: </span>{{ $data->quantity }}</li>
        <li class="list-inline-item"><span class="h6">Supplier: </span>{{ $data->supplied_by }}</li>
        <li class="list-inline-item"><span class="h6">Category: </span>{{ $data->category }}</li>
    </ul>
    <div class="table-responsive">
        <table class="table table-hover " id="stats">
            <thead>
                <tr>
                    <th role="columnheader" scope="col" class="user-select-none">#</th>
                    <th role="columnheader" scope="col" class="user-select-none">date</th>
                    <th role="columnheader" scope="col" class="user-select-none"
                        title="Recieved (how much were received)">rcvd</th>
                    <th role="columnheader" scope="col" class="user-select-none">from</th>
                    <th role="columnheader" scope="col" class="user-select-none" title="(How much were given out)">supply
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none" title="(who was it given out to?)">to
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none" title="Before Quanity">bf qty
                    </th>
                    <th role="columnheader" scope="col" class="user-select-none" title="After Quantity">af qty
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stats as $stat)
                    <tr>
                        <td>{{ $stat->id }}</td>
                        <td>{{ $stat->date }}</td>
                        <td>{{ $stat->qty_received }}</td>
                        <td>{{ $stat->from }}</td>
                        <td>{{ $stat->supplied }}</td>
                        <td>{{ $stat->to }}</td>
                        <td>{{ $stat->before_qty }}</td>
                        <td>{{ $stat->after_qty }}</td>
                        {{-- <td>{{ $stat }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var table = new DataTable('#stats', {
                processing: true,
                search: {
                    return: true,
                },
                order: [
                    [1, 'asc']
                ],
                pageLength: 200,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excel',
                        text: 'Export Excel <i class="fas fa-file-excel" ></i>',
                        className: 'btn btn-success text-capitalize',
                        title: '{{ $data->name }} analysis',
                        filename: '{{ $data->name }}_analysis',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        text: 'Save <i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger text-capitalize',
                        title: '{{ $data->name }} analysis',
                        filename: '{{ $data->name }}_analysis',
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        text: 'Print <i class="fas fa-print"></i>',
                        className: 'btn btn-primary text-capitalize',
                        title: '{{ $data->name }} analysis',
                        filename: '{{ $data->name }}_analysis',
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7]
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
