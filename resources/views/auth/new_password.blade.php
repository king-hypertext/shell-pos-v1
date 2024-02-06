@extends('app.auth')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card px-5 pb-5 border-0 mt-5" style="max-width: 520px">
                <form id="new-password" method="POST" autocomplete="off">
                    @csrf
                    <div class="text-center">
                        {{-- <img src="{{ url('icon.png') }}" style="height: 80px" alt="logo"> --}}
                        <h5 class="h4 text-uppercase text-danger">forgot password</h5>
                    </div>
                    <div class="text-center text-capitalize  text-primary py-2 fw-semibold ">
                        Choose new password
                    </div>
                    @if ($errors->any())
                        <ul class="list-unstyled alert alert-danger alert-dismissible ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    {{-- <div class="h6 alert alert-danger alert-dismissible text-danger text-center" id="login-error"></div> --}}
                    <div class="form-outline mb-4">
                        <input required type="password" autofocus name="password" id="password"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="password_confirmation">Confirm Passsword <span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn text-capitalize  btn-lg btn-primary btn-block">
                            <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                            reset password
                        </button>
                    </div>
                </form>
                <div class="form-text">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#loading-icon').hide();
            $('#new-password').on('submit', function(e) {
                $('#loading-icon').show();
                $(this).attr('action', '/auth/new-password');
                return true;
            })
        })
    </script>
@endsection
