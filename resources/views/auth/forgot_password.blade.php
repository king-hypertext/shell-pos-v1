@extends('app.auth')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card px-5 pb-5 border-0 mt-5" style="max-width: 520px">
                <form id="forgot-password" method="POST" autocomplete="off">
                    @csrf
                    <div class="text-center">
                        <h5 class="h4 text-uppercase text-danger">forgot password</h5>
                    </div>
                    <div class="text-center text-primary py-2 fw-normal ">
                        Enter correct credentials to reset your password
                    </div>
                    @if ($errors->any())
                        <ul class="list-unstyled alert alert-danger alert-dismissible ">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <div class="form-outline mb-4">
                        <input required type="date" autofocus name="date_of_birth" id="date_of_birth"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="date_of_birth">Date of Birth <span
                                class="text-danger">*</span></label>
                    </div>
                    <div class="form-outline mb-4">
                        <input required type="number" oninput(this.type='password') name="secret_code" id="secret_code"
                            class="form-control form-control-lg" />
                        <label class="form-label" for="secret_code">Secret Code <span class="text-danger">*</span></label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn text-capitalize  btn-lg btn-primary btn-block">
                            <span id="loading-icon" class="fas fa-spinner fa-spin"></span>
                            forgot password
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
            $('#forgot-password').on('submit', function(e) {
                $('#loading-icon').show();
                $(this).attr('action', '/auth/forgot-password');
                return true;
            })
        })
    </script>
@endsection
