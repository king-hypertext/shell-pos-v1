<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="{{ url('icon.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ url('icon.png') }}" />
    <title>SHELL POS | {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/mdb/mdb.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/index.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/animate.css/animate.min.css') }}">
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/external/jquery/jquery.js') }}"></script>
</head>

<body style="background-color: var(--bs-gray-200)">
    @yield('content')
    <script src="{{ url('assets/plugins/mdb/js/mdb.min.js') }}"></script>
    @yield('script')
</body>

</html>
