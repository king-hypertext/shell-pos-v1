@extends('app.auth')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card p-5 border-0 mt-5" style="max-width: 520px">
                <form id="login" method="POST" autocomplete="off">
                    @csrf
                    <div class="text-center">
                        <img src="{{ url('icon.png') }}" style="height: 80px" alt="logo">
                        <h5 class="h4 text-uppercase text-danger">shell medyak mr b.</h5>
                    </div>
                    <h3 class="text-center text-primary py-2">
                        Login to your account
                    </h3>
                    @if (session('login'))
                        <div class="alert alert-warning text-warning">{{ session('login') }}</div>
                    @endif
                    @if (session('error'))
                        <h6 class="h6 text-danger text-center">{{ session('error') }}</h6>
                    @endif
                    <div class="form-outline mb-4">
                        <input required type="text" autofocus name="username" id="username"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="username">Username</label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="password" name="password" id="password"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            <span id="btn-icon" class="fas fa-spinner fa-spin"></span>
                            Secure Login
                        </button>
                    </div>
                </form>
                <div class="form-text">
                    Forgotten Password? <a href="{{ url('/auth/reset-password') }}" title="Click to reset your password"
                        class="btn-link">Reset</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $('#btn-icon').hide();
            $('#login').on('submit', function(e) {
                e.preventDefault();
                $('#btn-icon').show();
                var username = e.currentTarget[1].value,
                    password = e.currentTarget[2].value;
                $.ajax({
                    url: "/login",
                    type: "POST",
                    data: {
                        _token: e.currentTarget[0].value,
                        username: username,
                        password: password
                    },
                    success: function(data) {
                        $('#btn-icon').hide();
                        console.log(data);
                        window.open(data.data, '_self');
                    },
                    error: function(error) {
                        $('#btn-icon').hide();
                        console.log(error);
                    }
                })
            });
        });
    </script>
@endsection

</html>
