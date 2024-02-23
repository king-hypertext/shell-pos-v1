<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('icon.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ url('icon.png') }}">
    <title>Q-IMS | {{ $title ?? 'DASHBOARD' }}</title>
    <link rel="manifest" href="{{ url('manifest.json') }}" />
    <link rel="stylesheet" href="{{ url('assets/fontawesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/jquery-ui-1.13.2/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/jquery-ui-1.13.2/jquery-ui.structure.min.css') }}" />
    <link rel="stylesheet"
        href="{{ url('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/select2/css/select2-bootstrap-5-theme.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/alert/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/mdb/mdb.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/index.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/animate.css/animate.min.css') }}" />
    <script src="{{ url('assets/plugins/alert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/external/jquery/jquery.js') }}"></script>
</head>

<body style="background-color: var(--bs-gray-200);height: 100vh!important;">
    @php
        use App\Models\Suppliers;
        $s_id = Suppliers::orderBy('created_at', 'DESC')->first()->id;
    @endphp
    <div class="container-fluid px-0 text-black mb-3 ">
        {{-- sidenav --}}
        @include('app.sidenav')
        <div class="main-content">
            {{-- navbar --}}
            @include('app.navbar')
            <div class="container-fluid mt-4">
                @yield('content')
            </div>
        </div>
    </div>
    @include('app.back_to_top')
    @include('app.footer')
    @include('modals.logout')
    <div id="back-drop" class="back-drop"></div>
    <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('assets/plugins/mdb/js/mdb.min.js') }}"></script>
    <script src="{{ url('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ url('sw.js') }}"></script>
    <script type="text/javascript">
        (function() {
            console.log("js loaded");
            document.querySelectorAll(".nav-item > a").forEach((target) => {
                new mdb.Ripple(target, {
                    rippleColor: "#fff",
                    rippleDuration: "1000ms",
                });
            });
            document.querySelectorAll('a[target="_blank"]').forEach((a) => {
                a.setAttribute('rel', 'noopener noreferrer');
            });
            $(window).scroll(() => {
                $(window).scrollTop() > 100 ? $('.scrollTop').fadeIn() : $('.scrollTop').fadeOut()
            })
            $('.scrollTop').click(e => {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 0)
                return 0;
            });
            setInterval(() => {
                document.querySelector("[data-date-time]").innerHTML =
                    new Date().toUTCString();
            }, 1000);
        })();
        $(document).ready(function() {
            $('.side-wrapper').scroll(function() {
                var setScroll = $('.side-wrapper').scrollTop();
                setScroll >= 45 ? $('.logo-content').addClass('scroll') : $('.logo-content').removeClass(
                    'scroll');
            });
            $(document).on('click', 'button#nav-toggler', () => {
                $(".side-wrapper").toggleClass("show");
                $(".back-drop").toggleClass("show");
                $("button#nav-toggler > i").toggleClass("fas fa-bars");
                $("button#nav-toggler > i").toggleClass("fas fa-x");
            });
            $(document).on('click', '#back-drop', () => {
                $(".side-wrapper").toggleClass("show");
                $(".back-drop").toggleClass("show");
                $("button#nav-toggler > i").toggleClass("fas fa-bars");
                $("button#nav-toggler > i").toggleClass("fas fa-x");
            });
            // $.each($('form'), (i,form)=>{
            //     form.setAttribute('autocorrect', false);
            // })
            // dropdown icon change
            $('.nav-item > a.dropdown[data-bs-toggle="collapse"]')
                .on("click", (e) => {
                    e = e.currentTarget.children[1].children[0].classList;
                    e.toggle("fa-chevron-right");
                    e.toggle("fa-chevron-down");
                });

            $.fn.dataTable.ext.errMode = 'none';
            $(document).on('error.dt', function(e, settings, techNote, message) {
                console.log('An error has been reported by DataTables: ', message);
            });
        });

        function openPopup(href) {
            var screenWidth = window.screen.width,
                screenHeight = window.screen.height;
            var popupWidth = (screenWidth - 55),
                popupHeight = (screenHeight - 45);
            var popupLeft = 10,
                popupTop = 10;
            window.open(href, "Popup Window", "width=" + popupWidth + ",height=" + popupHeight +
                ",left=" + popupLeft + ",top=" + popupTop);
        }
    </script>
    @yield('script')
</body>

</html>
