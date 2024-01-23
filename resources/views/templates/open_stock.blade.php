<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Open Stock</title>
</head>

<body>
    @php
        use Illuminate\Support\Number;
    @endphp
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

        table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 1rem;
            border: 1px solid #000;
        }

        th,
        td,
        tr {
            padding-left: 1rem;
            border: 1px solid #000;
        }

        th:not(.title) {
            text-align: left;
        }

        th.title {
            font-weight: 600 !important;
            font-size: 16pt;
            text-transform: uppercase;
        }
    </style>
    <table>
        <thead>
            <tr>
                <th class="title" colspan="5"> Open Stock {{ $date }}</h2>
                </th>
            </tr>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total(GHS)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td style="font-weight: 600;">{{ Number::format($product->price * $product->quantity, 2, 8) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
