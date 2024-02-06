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
                    
                    @if (session('new_password'))
                        <div class="alert alert-info  text-center  text-info">{{ session('new_password') }}</div>
                    @endif
                    <div class="h6 alert alert-danger alert-dismissible text-danger text-center" id="login-error"></div>
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
                    Forgotten Password? <a href="{{ route('forgot.password') }}" title="Click to reset your password"
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
            $('#login-error').hide();
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
                        if (data.error) {
                            $('#login-error').show();
                            $('#login-error').text(data.error);
                        }
                        console.log(data);
                        window.open(data.data, '_self');
                    },
                    error: function(error) {
                        $('#btn-icon').hide();
                        if (error.error) {
                            $('#login-error').show();
                            $('#login-error').text(error.error);
                        }
                        console.log(error);
                    }
                })
            });
        });
    </script>
@endsection
