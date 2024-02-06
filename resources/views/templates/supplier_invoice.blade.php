<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="{{ url('icon.png') }}" type="image/x-icon" />
    <title>Invoice</title>
</head>

<body>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 14pt;
            font-weight: 400;
            line-height: 1.5;
            color: #000;
            text-align: left;
            background-color: #fff;
            -webkit-text-size-adjust: 100%;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            vertical-align: top;
            border-collapse: collapse;
            border: 1px solid #222;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            vertical-align: top;
            margin-bottom: 1rem;

        }

        table>tr {
            width: 100%;
        }

        .tr,
        .th,
        .td {
            padding: 0.5rem 0.5rem;
            color: #222;
            background-color: #fff;
            border-bottom-width: 1px;
            border: 1px solid #222;

            /* box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg))); */
        }

        .table>tbody {
            vertical-align: inherit;
        }

        .table>thead {
            vertical-align: bottom;
        }

        .page-header {
            text-align: center;
            font-weight: bolder;
            margin-bottom: 14px;
        }

        .page-image {
            display: block;
            margin: 5px auto;
            text-align: center;
        }

        .page-image img {
            display: inline
        }

        .page-image span {
            margin-bottom: 5px !important;
            padding-bottom: 10px;
            display: block;
        }

        .invoice-header {
            margin: 20px 5px;
        }

        .list-unstyled {
            list-style: none;
            padding-left: 0
        }

        .d-inline {
            display: inline;
        }

        h6 {
            font-size: 14pt;
            font-weight: 600;
        }

        @media print {

            @page {
                size: A4;
                margin: 0.5in;
            }
        }

        .text-bold {
            font-weight: 500;
        }
    </style>
    @php
        use Carbon\Carbon;
    @endphp
    <div class="container-fluid ">
        <div class="page-header">
            Q-POS Invoice
        </div>
        <h5 style="text-align: center"> SHELL MEDYAK MR B.</h5>

        <ul class="list-unstyled ">
            <li>
                <h6 class="d-inline">Date: </h6>{{ Carbon::parse($date)->format('Y-M-d') }}
            </li>
            <li>
                <h6 class="d-inline">Invoice Number: </h6>
                #{{ $invoice_number }}
            </li>
        </ul>
        <table>
            <thead>
                <tr align="left" style="width: 100%">
                    <th style="width: 50%;text-transform: uppercase">from</th>
                    <th style="width: 50%;text-transform: uppercase">to</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ auth()->user()->fullname }}</td>
                </tr>
                <tr>
                    <td>{{ $supplier->contact }}</td>
                    <td>{{ auth()->user()->phone }}</td>
                </tr>
                <tr>
                    <td>{{ $supplier->address }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <h3>Invoice summary</h3>
        <table class="table ">
            <thead>
                <tr class="tr" align="left">
                    <th class="th"><b>#</b></th>
                    <th class="th">Item</th>
                    <th class="th">Price</th>
                    <th class="th">Quantity</th>
                    <th class="th">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $invoice)
                    <tr class="tr">
                        <td class="td">{{ $key + 1 }}</td>
                        <td class="td">{{ $invoice->product }}</td>
                        <td class="td">{{ $invoice->price }}</td>
                        <td class="td">{{ $invoice->quantity }}</td>
                        <td class="td amount">{{ $invoice->amount }}</td>
                    </tr>
                @endforeach
                <tr class="tr">
                    <td class="td text-bold" colspan="4">SubTotal</td>
                    <td class="td text-bold" style="font-weight: 500 !important;">{{ 'GHS ' . $total }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
