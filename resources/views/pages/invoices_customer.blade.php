@extends('app.index')
@section('content')
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid mt-4">
        <h2 class="h2 text-uppercase">worker invoices</h2>
        <div class="table-responsive">
            <table class="table table-hover" id="order_invoice">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>#INVOICE NUMBER</th>
                        <th>WORKER</th>
                        <th>AMOUNT</th>
                        <th>DATE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $index => $invoice)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer }}</td>
                            <td>{{ $invoice->amount }}</td>
                            <td>{{ Carbon::parse($invoice->created_at)->format('Y-M-d') }}</td>
                            <td>
                                <a href="{{ route('view-file.invoice', [$invoice->token]) }}" title="view invoice"
                                    class="btn text-primary" target="_blank">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if (session('error'))
        <script type="text/javascript">
            const showSuccessAlert = Swal.mixin({
                position: 'top',
                toast: true,
                timer: 14000,
                showConfirmButton: false,
                timerProgressBar: false,
            });
            showSuccessAlert.fire({
                icon: 'error',
                text: '{{ session('error') }}',
                padding: '20px',
                width: 'auto'
            });
        </script>
    @endif
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var table = new DataTable('#order_invoice', {
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
                        title: 'INVOICE ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'Save <i class="fas fa-file-pdf"></i>',
                        className: 'btn btn-danger text-capitalize',
                        footer: true,
                        title: 'INVOICE ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Print <i class="fas fa-print"></i>',
                        className: 'btn btn-primary text-capitalize',
                        footer: true,
                        title: 'INVOICE ' + moment((new Date())).format('dddd-Do-MMMM-YYYY'),
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4]
                        }
                    },
                ]
            });

            document.querySelector('input[aria-controls="order_invoice"]').placeholder = "Search table";
            $('input[aria-controls="order_invoice"]').on('keyup', function() {
                table.search(this.value).draw();
            });
        })
    </script>
@endsection
